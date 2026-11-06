<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = ['store_name', 'currency', 'tax_rate', 'low_stock_threshold', 'expiry_warning_days', 'loyalty_earn_per', 'loyalty_point_value'];

    protected $casts = ['tax_rate' => 'float', 'loyalty_earn_per' => 'float', 'loyalty_point_value' => 'float'];

    public static function current(): self
    {
        return static::first() ?? static::create([]);
    }
}
