<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    public function index(Medicine $medicine)
    {
        $batches = $medicine->batches()->orderBy('expiry_date')->get();

        $alternatives = \App\Support\DrugKnowledge::alternatives($medicine, 6);

        return view('batches', compact('medicine', 'batches', 'alternatives'));
    }

    public function store(Request $request, Medicine $medicine)
    {
        $data = $request->validate([
            'batch_no' => 'required|string|max:100',
            'expiry_date' => 'required|date|after:today',
            'quantity' => 'required|integer|min:1|max:1000000',
            'cost_price' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(fn () => $medicine->addBatch($data['batch_no'], $data['expiry_date'], (int) $data['quantity'], $data['cost_price'] ?? null));

        return back()->with('success', "Received {$data['quantity']} units into batch {$data['batch_no']}.");
    }

    public function destroy(Medicine $medicine, MedicineBatch $batch)
    {
        abort_unless($batch->medicine_id === $medicine->id, 404);

        AuditLog::record('batch.removed', "{$medicine->name}: removed batch {$batch->batch_no} ({$batch->quantity} units)");
        DB::transaction(function () use ($medicine, $batch) {
            $batch->delete();
            $medicine->syncFromBatches();
        });

        return back()->with('success', 'Batch removed and stock recalculated.');
    }
}
