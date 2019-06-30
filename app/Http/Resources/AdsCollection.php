<?php

namespace App\Http\Resources;

use App\Http\Resources\UsersCollection;
use Illuminate\Http\Resources\Json\Resource;

class AdsCollection extends Resource
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
            'title' => $this->title,
            'description' => $this->description,
            'user' => $this->user->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'img_url' => $this->img_url,
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
        ];
    }
}
