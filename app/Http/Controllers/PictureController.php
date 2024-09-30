<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePictureRequest;
use App\Http\Resources\PictureResource;
use App\Models\Picture;
use Illuminate\Http\Request;
use Exception;
use symfony\Component\HttpFoundation\Response;
class PictureController extends Controller
{
    public function index()
    {
        $pictures = Picture::all();
        if (!$pictures) {
            return response()->json(["Error" => "No picture found"], 404);
        }
        return response()->json(PictureResource::collection($pictures), 200);
    }

    public function show($id)
    {
        $picture = Picture::find($id);
        if ($picture) {
            return response()->json(new PictureResource($picture), 200);
        }
        return response()->json(["Error" => "Picture not found"], 404);
    }
    public function store(StorePictureRequest $request)
    {
        $data = $request->validated();
        $image = $data->file('image');
        $picture = new Picture();
        $picture->name = $image->getPath();
        $data["image"]->move(public_path('images'), $picture->name);
        $picture->product_id = $data["product_id"];
        $picture->save();
        return response()->json(["Message" => "Picture was added"], 200);
        /*  */
    }
    public function update(StorePictureRequest $request, $id)
    {
        $data = $request->validated();
        $picture = Picture::find($id);
        if ($picture) {
            $picture->name = time() . '.' . $data["image"]->extension();
            $data["image"]->move(public_path('images'), $picture->name);
            $picture->product_id = $data["product_id"];
            $picture->image = 'images/' . $picture->name;
            $picture->update();
            return response()->json(["Message" => "Picture was updated"], 200);
        } else {
            return response()->json(["Error" => "Picture not found"], 404);
        }
    }

    public function delete($id)
    {
        $picture = Picture::find($id);
        if ($picture) {
            $picture->delete();
            return response()->json(["Message" => "Picture was deleted"], 200);
        }
        return response()->json(["Error" => "Picture not found"], 404);

    }
}