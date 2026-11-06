<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name', 'contact_person', 'phone', 'email', 'address'];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    /** What we still owe: received goods minus payments made. */
    public function balance(): float
    {
        return round((float) $this->purchaseOrders()->where('status', 'received')->sum('total_cost')
            - (float) $this->payments()->sum('amount'), 2);
    }
}
