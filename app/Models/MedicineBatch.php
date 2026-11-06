<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineBatch extends Model
{
    protected $fillable = ['medicine_id', 'batch_no', 'expiry_date', 'quantity', 'cost_price'];

    protected $casts = ['expiry_date' => 'date:Y-m-d'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date->lt(today());
    }
}
