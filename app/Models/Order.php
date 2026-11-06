<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'rx_prescriber',
        'rx_number',
        'points_earned',
        'points_redeemed',
        'invoice_number',
        'patient_name',
        'phone',
        'channel',
        'total_items',
        'subtotal',
        'discount',
        'tax',
        'total_amount',
        'paid_amount',
        'payment_method',
        'cart_items'
    ];

    protected $appends = ['returned_map'];

    public function returns()
    {
        return $this->hasMany(OrderReturn::class);
    }

    /** cart line index => units already returned. */
    public function getReturnedMapAttribute(): array
    {
        $map = [];
        foreach ($this->returns as $return) {
            foreach ($return->items as $line) {
                $map[$line['index']] = ($map[$line['index']] ?? 0) + $line['qty'];
            }
        }

        return $map;
    }

    protected $casts = [
        'cart_items' => 'array',
    ];
}