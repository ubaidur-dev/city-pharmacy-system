<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'action', 'summary', 'details'];

    protected $casts = ['details' => 'array', 'created_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Record who did what. Never lets a logging problem break the real action. */
    public static function record(string $action, string $summary, array $details = []): void
    {
        try {
            static::create(['user_id' => auth()->id(), 'action' => $action, 'summary' => $summary, 'details' => $details ?: null]);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
