<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\Ad;
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
        DB::beginTransaction();
        $ad = Ad::whereId($request['ad_id'])->first();

        if($request['quantity'] > $ad->quantity) {
            return response()->json([
                "status" => false,
                "message" => "We don't have that much on stock!"
            ], 400)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
        }

        $ad->quantity -= $request['quantity'];
        $ad->save();

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        if($temp_order_item = TempOrderItem::where('ad_id', $ad->id)->where('user_id', Auth::user()->id)->first()) {
            $temp_order_item->quantity += $data['quantity'];
            $temp_order_item->save();
        } else {
            TempOrderItem::create($data);
        }

        DB::commit();
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

        $temp_order_item = TempOrderItem::whereId($request['temp_order_item_id'])->first();
        $ad = Ad::whereId($temp_order_item->ad_id)->first();
        $ad->quantity += $temp_order_item->quantity;
        $ad->save();

        $temp_order_item->delete();

        return response()->json([
            "status" => true,
            "message" => "Order item succesfully deleted."
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }
}
