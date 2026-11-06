<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderReturn extends Model
{
    protected $fillable = ['order_id', 'user_id', 'reason', 'refund_amount', 'items'];

    protected $casts = ['items' => 'array', 'refund_amount' => 'float'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
