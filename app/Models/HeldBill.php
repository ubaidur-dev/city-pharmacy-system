<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeldBill extends Model
{
    protected $fillable = ['user_id', 'label', 'payload'];

    protected $casts = ['payload' => 'array'];
}
