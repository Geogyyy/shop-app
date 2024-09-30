<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Storage;
class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        if (!$categories) {
            return response()->json(["Error" => "No categories found"], 404);

        }
        return response()->json(CategoryResource::collection($categories), 200);

    }

    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json(new CategoryResource($category), 200);
        }
        return response()->json(["Error" => "category not found"], 404);
    }
    public function store(StoreCategoryRequest $request)
    {

        $data = $request->validated();
        $category = new Category();
        $category->name = $data["name"];
        $category->is_active = $data["is_active"];
        $category->order_id = $data["order_id"];
        if (isset($data["parent_id"])) {
            $category->parent_id = $data["parent_id"];
        } else {
            $category->parent_id = null;
        }
        $category->save();
        return response()->json(["Message" => "Category was added"], 200);

    }
    public function update(Request $request, $id)
    {

        $data = $request->validated();
        $category = Category::find($id);
        if ($category) {
            $category->name = $data["name"];
            $category->is_active = $data["is_active"];
            $category->order_id = $data["order_id"];
            $category->parent_id = $data["parent_id"];
            $category->update();
            return response()->json(["Message" => "Category was updated"], 200);
        } else {
            return response()->json(["Error" => "Category not found"], 404);
        }

    }

    public function delete($id)
    {

        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json(["Message" => "Category was deleted"], 200);
        } else {
            return response()->json(["Error" => "Category not found"], 404);
        }

    }
    public function findSubcategories($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(["Error" => "Parent category not found"], 404);
        }



        $categories = Category::where("parent_id", $id)->get();
        //$categories = DB::table("categories")->where("parent_id", $id)->get();



        if ($categories->count() > 0) {
            return response()->json(CategoryResource::collection($categories), 200);
        }
        return response()->json(["Error" => "No subcategory found"], 404);
    }
    public function getTree()
    {
        return $this->getSubTree(null);
    }
    public function getSubTree($id)
    {
        $categories = Category::where("parent_id", $id)->get();

        if ($categories->count() == 0)
            return "";
        $message = "<ul> \n";

        foreach ($categories as $category) {

            $message .= "<li>  \n" . $category->name . " (" . $category->id . ") \n";
            $message .= $this->getSubTree($category->id) . "</li>";

        }
        $message .= "</ul>";
        return $message;

    }

}

