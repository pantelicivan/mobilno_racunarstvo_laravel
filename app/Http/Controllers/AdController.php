<?php

namespace App\Http\Controllers;

use Auth;
use App\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ads = Ad::all();
        return response()->json([
            "status" => true,
            "ads" => $ads
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(),
        [
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422)->header('Access-Control-Allow-Origin','*');
        }

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        Ad::create($data);


        return response()->json([
            "status" => true,
            "message" => 'Your ad has been successfully created.'
        ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ad  $ad
     * @return \Illuminate\Http\Response
     */
    public function show(Ad $ad)
    {
        //if($ad) {
            return response()->json([
                "status" => true,
                "message" => $ad
            ], 200)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
        // } else {
        //     return response()->json([
        //         "status" => false,
        //         "message" => "The ad could not be found."
        //     ], 400)->header('Content-Type', 'application/json')->header('Access-Control-Allow-Origin','*');
        // }
    }
}
