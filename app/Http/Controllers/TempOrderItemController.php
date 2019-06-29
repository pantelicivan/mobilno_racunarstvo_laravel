<?php

namespace App\Http\Controllers;

use Auth;
use App\TempOrderItem;
use Illuminate\Http\Request;

class TempOrderItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        return response()->json([
            "status" => true,
            "order_items" => $user->temp_order_items
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),
        [
            'ad_id' => 'required',
            'title' => 'required',
            'quantity' => 'required',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422)->header('Access-Control-Allow-Origin','*');
        }

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        TempOrderItem::create($data);

        return response()->json([
            "status" => true,
            "message" => "Order item succesfully created."
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }

    public function destroy(Request $request)
    {
        $validator = \Validator::make($request->all(),
        [
            'temp_order_item_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422)->header('Access-Control-Allow-Origin','*');
        }

        TempOrderItem::whereId($request['temp_order_item_id'])->delete();

        return response()->json([
            "status" => true,
            "message" => "Order item succesfully deleted."
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }
}
