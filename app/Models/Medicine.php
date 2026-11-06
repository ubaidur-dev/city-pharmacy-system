<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'barcode',
        'generic_name',
        'is_controlled',
        'category',
        'company',
        'price',
        'stock',
        'expiry_date',
    ];

    protected $casts = ['is_controlled' => 'boolean'];

    public function batches()
    {
        return $this->hasMany(MedicineBatch::class);
    }

    /** Receive new stock as a batch and refresh the cached totals. */
    public function addBatch(string $batchNo, string $expiry, int $qty, ?float $cost = null): MedicineBatch
    {
        $batch = $this->batches()->create([
            'batch_no' => $batchNo,
            'expiry_date' => $expiry,
            'quantity' => $qty,
            'cost_price' => $cost,
        ]);
        $this->syncFromBatches();

        return $batch;
    }

    /** Units that can legally be sold today (not expired). */
    public function sellableStock(): int
    {
        return (int) $this->batches()->whereDate('expiry_date', '>=', today())->sum('quantity');
    }

    /**
     * Take stock first-expiry-first-out. Returns the batches used.
     * Must run inside a transaction; locks the rows it touches.
     *
     * @return array<int, array{batch_no: string, expiry_date: string, qty: int}>
     */
    public function deductFefo(int $qty, bool $sellableOnly = true): array
    {
        $query = $this->batches()->where('quantity', '>', 0)->orderBy('expiry_date')->orderBy('id')->lockForUpdate();
        if ($sellableOnly) {
            $query->whereDate('expiry_date', '>=', today());
        }

        $used = [];
        $remaining = $qty;
        foreach ($query->get() as $batch) {
            if ($remaining <= 0) {
                break;
            }
            $take = min($remaining, $batch->quantity);
            $batch->decrement('quantity', $take);
            $used[] = ['batch_no' => $batch->batch_no, 'expiry_date' => $batch->expiry_date->toDateString(), 'qty' => $take];
            $remaining -= $take;
        }

        if ($remaining > 0) {
            throw new RuntimeException("Insufficient stock for {$this->name}.");
        }

        $this->syncFromBatches();

        return $used;
    }

    /** stock = all units on hand, expiry_date = earliest expiry that still has units. */
    public function syncFromBatches(): void
    {
        $live = $this->batches()->where('quantity', '>', 0);
        $this->forceFill([
            'stock' => (int) (clone $live)->sum('quantity'),
            'expiry_date' => (clone $live)->min('expiry_date') ?? $this->batches()->max('expiry_date') ?? $this->expiry_date,
        ])->save();
    }
}
