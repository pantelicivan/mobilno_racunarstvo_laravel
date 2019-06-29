<?php

namespace App\Http\Controllers;

use App\User;
use App\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    
    public function index(Request $request)
    {
        //$user = Auth::user();
        $user = User::whereId($request['user_id'])->first();

        return response()->json([
            "status" => true,
            "order_items" => $user->order_items
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }

    public function destroy(Request $request)
    {
        $validator = \Validator::make($request->all(),
        [
            'order_item_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422)->header('Access-Control-Allow-Origin','*');
        }

        OrderItem::whereId($request['order_item_id'])->delete();

        return response()->json([
            "status" => true,
            "message" => "Order item succesfully deleted."
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }
}
