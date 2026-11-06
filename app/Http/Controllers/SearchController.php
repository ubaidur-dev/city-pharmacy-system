<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $q) . '%';

        return Medicine::where('name', 'like', $like)->orWhere('company', 'like', $like)->orWhere('category', 'like', $like)
            ->orderByRaw('name like ? desc', [str_replace(['%', '_'], ['\\%', '\\_'], $q) . '%'])
            ->orderBy('name')->limit(8)
            ->get(['id', 'name', 'company', 'price', 'stock', 'expiry_date'])
            ->map(fn ($m) => [
                'id' => $m->id, 'name' => $m->name, 'company' => $m->company,
                'price' => (float) $m->price, 'stock' => $m->stock, 'expiry' => (string) $m->expiry_date,
                'url' => "/medicines/{$m->id}/batches",
            ]);
    }
}
