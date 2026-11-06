<?php

namespace App\Http\Controllers;

use App\Models\StoreSetting;
use Illuminate\Http\Request;

class StoreSettingController extends Controller
{
    public function edit()
    {
        return view('store-settings', ['settings' => StoreSetting::current()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => 'required|string|max:120',
            'currency' => 'required|string|max:10',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'low_stock_threshold' => 'required|integer|min:0|max:100000',
            'expiry_warning_days' => 'required|integer|min:1|max:730',
            'loyalty_earn_per' => 'required|numeric|min:0|max:1000000',
            'loyalty_point_value' => 'required|numeric|min:0|max:1000000',
        ]);

        StoreSetting::current()->update($data);
        \App\Models\AuditLog::record('settings.changed', 'Store settings updated', $data);

        return back()->with('success', 'Store settings saved.');
    }
}
