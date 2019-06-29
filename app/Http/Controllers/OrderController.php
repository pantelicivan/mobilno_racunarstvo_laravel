<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Order;
use App\TempOrderItem;
use App\OrderItem;
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

        $user = Auth::user();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $temp_order_items = $user->temp_order_items;

        if($order = new Order($data)) {
            DB::beginTransaction();
                try {
                    $order->save();

                    foreach ($temp_order_items as $temp_order_item) {
                        $order_item = new OrderItem();
                        $order_item->user_id = $user->id;
                        $order_item->ad_id = $temp_order_item->ad_id;
                        $order_item->title = $temp_order_item->title;
                        $order_item->quantity = $temp_order_item->quantity;
                        $order_item->price = $temp_order_item->price;
                        $order_item->order_id = $order->id;
                        $order_item->save();
                        $temp_order_item->delete();
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        "status" => false,
                        "message" => $e->getMessage()
                    ], 400)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
                }


                return response()->json([
                    "status" => true,
                    "message" => "Order successfully created."
                ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
        }
    }

    public function index()
    {
        $user_id = Auth::user()->id;;
        $orders = Order::where('user_id', $user_id)->get();

        return response()->json([
            "status" => true,
            "message" => OrderCollection::collection($orders)
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }
}
