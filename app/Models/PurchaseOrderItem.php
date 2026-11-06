<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['purchase_order_id', 'medicine_id', 'quantity', 'unit_cost', 'received_quantity'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
