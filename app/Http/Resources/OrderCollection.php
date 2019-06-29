<?php

namespace App\Http\Resources;

//use OrderItemsCollection;
use Illuminate\Http\Resources\Json\Resource;

class OrderCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'note' => $this->note,
            'user_id' => $this->user_id,
            'order_items' => OrderItemsCollection::collection($this->order_items),
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
        ];
    }
}
