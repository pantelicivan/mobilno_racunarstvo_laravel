<?php

namespace App;

use App\Ad;
use App\Order;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
