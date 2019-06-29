<?php

namespace App;

use App\User;
use App\OrderItem;
use Illuminate\Database\Eloquent\Model;

class SmsNotification extends Model
{
    public function user_to_notify()
    {
        return $this->belongsTo(User::class, 'user_to_notify');
    }

    public function user_that_ordered()
    {
        return $this->belongsTo(User::class, 'user_that_ordered');
    }

    public function order_item()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
