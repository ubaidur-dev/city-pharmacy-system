<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = ['po_number', 'supplier_id', 'status', 'notes', 'total_cost', 'received_at'];

    protected $casts = ['received_at' => 'datetime'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function orderedTotal(): float
    {
        return (float) $this->items->sum(fn ($i) => $i->quantity * $i->unit_cost);
    }
}
