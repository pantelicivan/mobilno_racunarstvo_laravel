<?php

namespace App;

use App\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
    protected $guarded = [];

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
