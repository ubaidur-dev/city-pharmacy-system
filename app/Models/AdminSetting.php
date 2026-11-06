<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    // Yahan hum Laravel ko bata rahe hain ke in fields mein data dalne ki ijazat hai
    protected $fillable = [
        'name',
        'email',
        'phone',
        'designation',
        'address',
        'bio',
        'profile_image'
    ];
}