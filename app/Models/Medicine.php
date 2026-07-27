<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'category',
        'company',
        'price',
        'stock',
        'expiry_date',
    ];
}
