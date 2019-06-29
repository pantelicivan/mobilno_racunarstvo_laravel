<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Order;
use App\TempOrderItem;
use App\OrderItem;
use App\SmsNotification;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
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

        if(!$temp_order_items->first()) {
            return response()->json([
                "status" => false,
                "message" => "You don't have any order items."
            ], 400)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
        }

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

                    $sms = new SmsNotification();
                    $sms->user_to_notify = $order_item->ad->user->id;
                    $sms->user_that_ordered = $order_item->user_id;
                    $sms->order_item_id = $order_item->id;
                    $sms->save();
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    "status" => false,
                    "message" => $e->getMessage()
                ], 400)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
            }

            self::sendSms();

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

    public function sendSms()
    {
        $account_sid = 'AC0d62313b8f8ebe8af4fec4b7f37ef942';
        $auth_token = 'db987adcd82b474b94456a0e7f5a2c5a';
        $client = new Client($account_sid, $auth_token);

        $notifications = SmsNotification::all();
        foreach ($notifications as $notification) {
            $user_to_notify = $notification->user_to_notify()->first();
            $user_that_ordered = $notification->user_that_ordered()->first();
            $order_item = $notification->order_item()->first();
            $order = $order_item->order;

            $message = "Korisnik : " . $user_that_ordered->name . ", je narucio vas artikal : "
                     . $order_item->title . '. Kolicina : ' . $order_item->quantity . " , Adresa : "
                     . $order->address . " , Napomena : " . $order->note;

            $client->messages->create(
                // Where to send a text message (your cell phone?)
                $user_to_notify->mobile_phone,
                array(
                    'from' => "+17273466582",
                    'body' => $message,
                )
            );
        }
    }
}
