<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderCollection;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),
        [
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422)->header('Access-Control-Allow-Origin','*');
        }

        Order::create($request->all());

        return response()->json([
            "status" => true,
            "message" => "Order successfully created."
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }

    public function index()
    {
        $user_id = 1; // Auth::user()->id
        $orders = Order::where('user_id', $user_id)->get();

        return OrderCollection::collection($orders);

        // return response()->json([
        //     "status" => true,
        //     "message" => OrderCollection::collection($orders)
        // ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }
}
