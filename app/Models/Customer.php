<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'phone', 'phone_key', 'email', 'date_of_birth', 'allergies', 'notes', 'points'];

    protected $casts = ['date_of_birth' => 'date:Y-m-d'];

    public static function keyFor(?string $phone): ?string
    {
        $key = preg_replace('/\D/', '', (string) $phone);

        return strlen($key) >= 6 ? $key : null;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
