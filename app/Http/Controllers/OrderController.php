<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::get();
        if ($orders->count() == 0) {
            return response()->json(["Error" => "No orders found"], 404);
        }
        return response()->json($orders, 200);
    }

    public function show($id)
    {
        $order = Order::with("products")->find($id);
        if ($order) {
            return response()->json($order, 200);
        }
        return response()->json(["Error" => "Order not found"], 404);
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $order = new Order();
            $order->user_id = Auth::id();
            $order->save();
        foreach ($data as $key => $value) {

            $product = Product::find($value["product_id"]);

            if ($product->stock < $value["quantity"]) {
                return response()->json(['Message' => 'Insufficient stock'], 400);
            }

            $order->products()->attach($value["product_id"], ["quantity" => $value["quantity"]]);

            $product->stock -= $value["quantity"];
            $product->update();
            
        }
        return response()->json(["Message" => "Order was added","Order" =>new OrderResource($order)], 200);

    }

    public function update(StoreOrderRequest $request, $id)
    {
        $data = $request->validated();
        $order = Order::find($id);
        if ($order) {
            $order->products()->detach($data["product_id"]);
            $order->products()->attach($data["product_id"], ['quantity' => $data["quantity"]]);
            return response()->json(["Message" => "Order was updated", "Order" => $order->products()->find($data["product_id"])], 200);
        } else {
            return response()->json(["Error" => "Order not found"], 404);

        }

    }

    public function delete($id)
    {

        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return response()->json(["Message" => "Order was deleted"], 200);
        } else {
            return response()->json(["Error" => "Order not found"], 404);
        }
    }

}