<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\Order;
use App\Models\StoreSetting;
use App\Models\AdminSecurity;
use App\Models\AdminSetting;
use App\Models\ThemeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class MedicineController
{
    public function __construct()
    {
        // Admin Security Sharing
        $adminSetting = AdminSecurity::first();
        if (!$adminSetting) {
            $adminSetting = AdminSecurity::create([
                'email' => 'ubaid@citypharmacy.com',
                'password' => Hash::make('password123')
            ]);
        }
        View::share('adminSetting', $adminSetting);
        View::share('profileSetting', AdminSetting::first());

        // Global Theme Settings Sharing (Auto Load for Layout)
        $globalTheme = ThemeSetting::first();
        if (!$globalTheme) {
            $globalTheme = ThemeSetting::create([
                'primary_color' => 'navy',
                'interface_mode' => 'light'
            ]);
        }
        if ($globalTheme->primary_color === 'emerald') {
            $globalTheme->update(['primary_color' => 'navy']);
        }
        View::share('globalTheme', $globalTheme);
    }

    public function profileView(Request $request)
    {
        $admin = AdminSetting::first() ?? new AdminSetting(['name' => $request->user()->name, 'email' => $request->user()->email]);
        return view('profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'designation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $admin = AdminSetting::first() ?? new AdminSetting();
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }
        $admin->fill($data)->save();
        $request->user()->update(['name' => $data['name']]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        return $this->updateSecurityPassword($request);
    }

    public function securityView()
    {
        $admin = AdminSecurity::first();
        return view('security', compact('admin'));
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        $admin = AdminSecurity::first();

        if (!$admin) {
            $admin = new AdminSecurity();
        }

        $admin->email = $request->email;
        $admin->save();

        // Login now runs on the users table, so keep it in step.
        $request->user()?->update(['email' => $request->email]);

        return redirect()->back()->with('success', 'Email address updated successfully in database!');
    }

    public function updateSecurityPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Your current password does not match our records!']);
        }

        // Login runs on the users table (hashed by the model cast).
        $request->user()->update(['password' => $request->new_password]);

        return redirect()->back()->with('success', 'Password successfully changed and secured in database!');
    }

    // ==========================================
    // THEME & COLOR SETTINGS METHODS
    // ==========================================

    public function themeView()
    {
        $theme = ThemeSetting::first();
        return view('theme', compact('theme'));
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'primary_color'  => 'required|string|max:50',
            'interface_mode' => 'required|string|max:50',
        ]);

        $theme = ThemeSetting::first();

        if (!$theme) {
            $theme = new ThemeSetting();
        }

        $theme->primary_color = $request->primary_color;
        $theme->interface_mode = $request->interface_mode;
        $theme->save();

        return redirect()->back()->with('success', 'Theme & appearance preferences updated successfully in database!');
    }

    // ==========================================
    // YOUR EXISTING MEDICINE METHODS
    // ==========================================

    public function indexView(Request $request)
    {
        $query = Medicine::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('category', 'like', '%' . $searchTerm . '%')
                  ->orWhere('company', 'like', '%' . $searchTerm . '%');
            });
        }

        $medicines = $query->paginate(15)->appends($request->all());

        return view('medicines', compact('medicines'));
    }

    public function storeView(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:64|unique:medicines,barcode',
            'generic_name' => 'nullable|string|max:255',
            'is_controlled' => 'sometimes|boolean',
            'category' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'expiry_date' => 'required|date',
        ]);

        $validated['is_controlled'] = $request->boolean('is_controlled');

        DB::transaction(function () use ($validated) {
            $medicine = Medicine::create($validated);
            $medicine->batches()->create([
                'batch_no' => 'OPENING',
                'expiry_date' => $validated['expiry_date'],
                'quantity' => max(0, (int) $validated['stock']),
            ]);
        });

        return redirect('/medicines')->with('success', 'Medicine added successfully!');
    }

    public function updateView(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:64|unique:medicines,barcode,' . $medicine->id,
            'generic_name' => 'nullable|string|max:255',
            'is_controlled' => 'sometimes|boolean',
            'category' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'expiry_date' => 'required|date',
        ]);

        $validated['is_controlled'] = $request->boolean('is_controlled');

        DB::transaction(function () use ($medicine, $validated) {
            $diff = (int) $validated['stock'] - $medicine->stock;
            if ((float) $validated['price'] !== (float) $medicine->price) {
                AuditLog::record('medicine.price', "{$medicine->name}: price {$medicine->price} -> {$validated['price']}");
            }
            if ($diff !== 0) {
                AuditLog::record('stock.adjusted', "{$medicine->name}: stock adjusted by {$diff} from the edit form");
            }
            $details = collect($validated)->except(['stock', 'expiry_date'])->all();
            $medicine->update($details);

            if ($diff > 0) {
                $medicine->addBatch('ADJ-' . now()->format('ymd-His'), $validated['expiry_date'], $diff);
            } elseif ($diff < 0) {
                $medicine->deductFefo(-$diff, false);
            } elseif ($medicine->batches()->where('quantity', '>', 0)->count() === 1) {
                // One live batch: the expiry typed here is for that batch.
                $medicine->batches()->where('quantity', '>', 0)->update(['expiry_date' => $validated['expiry_date']]);
                $medicine->syncFromBatches();
            }
        });

        return redirect('/medicines')->with('success', 'Medicine updated successfully!');
    }

    public function destroyView($id)
    {
        $medicine = Medicine::findOrFail($id);
        AuditLog::record('medicine.deleted', "Deleted {$medicine->name} (stock {$medicine->stock})", ['id' => $medicine->id]);
        $medicine->delete();

        return redirect('/medicines')->with('success', 'Medicine deleted successfully!');
    }

    public function suppliersView()
    {
        $suppliers = Medicine::select('company')->distinct()->get();
        return view('suppliers', compact('suppliers'));
    }

    public function stockAlertsView()
    {
        $settings = StoreSetting::current();

        $emptyStockMedicines = Medicine::where('stock', 0)->get();
        $lowStockMedicines = Medicine::where('stock', '>', 0)->where('stock', '<', $settings->low_stock_threshold)->get();
        // Only medicines that still have units on hand can be "expiring".
        $expiringMedicines = Medicine::where('stock', '>', 0)
            ->where('expiry_date', '<=', now()->addDays($settings->expiry_warning_days))->get();

        return view('stock-alerts', compact('emptyStockMedicines', 'lowStockMedicines', 'expiringMedicines'));
    }

    public function salesBillingView()
    {
        $medicines = Medicine::all();
        $orders = Order::with('returns')->orderBy('created_at', 'desc')->get(); 
        
        return view('sales-billing', compact('medicines', 'orders'));
    }

    public function generateBillView(Request $request)
    {
        $validated = $request->validate([
            'patient_name'     => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'paid_amount'      => 'nullable|numeric|min:0',
            'channel'          => 'nullable|string',
            'payment_method'   => 'required|string',
            'redeem_points'    => 'nullable|integer|min:0',
            'rx_prescriber'    => 'nullable|string|max:255',
            'rx_number'        => 'nullable|string|max:100',
            'cart_items'       => 'required|array|min:1',
            'cart_items.*.id'    => 'nullable|numeric',
            'cart_items.*.name'  => 'required|string|max:255',
            'cart_items.*.price' => 'required|numeric|min:0',
            'cart_items.*.qty'   => 'required|integer|min:1',
        ], ['payment_method.required' => 'Please select a payment method.']);

        $settings = StoreSetting::current();

        try {
            $order = DB::transaction(function () use ($request, $validated, $settings) {
                $subtotal = 0;
                $items = [];

                foreach ($validated['cart_items'] as $i => $item) {
                    $price = (float) $item['price'];
                    $batches = [];
                    $medicine = null;

                    if (isset($item['id']) && is_numeric($item['id'])) {
                        $medicine = Medicine::lockForUpdate()->find($item['id']);
                        if (!$medicine) {
                            throw new \RuntimeException('Item #' . ($i + 1) . ' (' . $item['name'] . ') not found in inventory.');
                        }
                        if ($medicine->sellableStock() < $item['qty']) {
                            throw new \RuntimeException("Insufficient sellable stock for {$medicine->name}. Available (not expired): {$medicine->sellableStock()}, requested: {$item['qty']}.");
                        }
                        // The database price always wins over whatever the browser sent.
                        $price = (float) $medicine->price;
                        $batches = $medicine->deductFefo((int) $item['qty']);
                    }

                    if (!empty($medicine) && $medicine->is_controlled && (empty($validated['rx_prescriber']) || empty($validated['rx_number']))) {
                        throw new \RuntimeException("{$medicine->name} is a controlled drug: enter the prescriber and prescription number.");
                    }

                    $subtotal += $price * $item['qty'];
                    $items[] = array_merge($item, ['price' => $price, 'batches' => $batches]);
                }

                $discount = round($subtotal * ((float) ($validated['discount_percent'] ?? 0)) / 100, 2);
                $tax = round(($subtotal - $discount) * $settings->tax_rate / 100, 2);
                $total = round($subtotal - $discount + $tax, 2);

                $channel = $validated['channel'] ?? 'customer';
                $key = $channel === 'customer' ? Customer::keyFor($validated['phone'] ?? null) : null;
                $customer = $key ? Customer::firstOrCreate(['phone_key' => $key], ['name' => $validated['patient_name'], 'phone' => $validated['phone']]) : null;
                if ($customer) {
                    $customer = Customer::lockForUpdate()->find($customer->id);
                }

                // Loyalty: redeem what the customer has (never more than the bill), then earn on what is actually paid.
                $loyaltyOn = $settings->loyalty_earn_per > 0 && $customer;
                $redeemed = 0;
                if ($loyaltyOn && $settings->loyalty_point_value > 0) {
                    $redeemed = min((int) ($validated['redeem_points'] ?? 0), $customer->points, (int) floor($total / $settings->loyalty_point_value));
                    $total = round($total - $redeemed * $settings->loyalty_point_value, 2);
                }
                $earned = $loyaltyOn ? (int) floor($total / $settings->loyalty_earn_per) : 0;
                if ($customer && ($earned || $redeemed)) {
                    $customer->update(['points' => $customer->points - $redeemed + $earned]);
                }

                return Order::create([
                    'customer_id'    => $customer?->id,
                    'rx_prescriber'  => $validated['rx_prescriber'] ?? null,
                    'rx_number'      => $validated['rx_number'] ?? null,
                    'points_earned'   => $earned,
                    'points_redeemed' => $redeemed,
                    'invoice_number' => 'PENDING',
                    'patient_name'   => $validated['patient_name'],
                    'phone'          => $validated['phone'] ?? '',
                    'channel'        => $validated['channel'] ?? 'customer',
                    'total_items'    => array_sum(array_column($items, 'qty')),
                    'subtotal'       => $subtotal,
                    'discount'       => $discount,
                    'tax'            => $tax,
                    'total_amount'   => $total,
                    'paid_amount'    => $validated['paid_amount'] ?? $total,
                    'payment_method' => $validated['payment_method'],
                    'cart_items'     => $items,
                ]);
            });
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'errors' => [$e->getMessage()]], 422);
        }

        // Invoice number comes from the row id, so it can never collide.
        $order->update(['invoice_number' => 'INV-' . now()->format('Y') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT)]);
        AuditLog::record('sale.created', "{$order->invoice_number} for {$order->total_amount}", ['order_id' => $order->id, 'items' => $order->total_items]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Invoice generated, stock updated and saved to history successfully!',
                'order'   => $order
            ], 200);
        }

        return back()->with('success', 'Invoice generated, stock updated and saved to history successfully!');
    }

    public function destroyOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice not found'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'History record deleted successfully!'
        ], 200);
    }

    public function index(Request $request)
    {
        $query = Medicine::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $medicines = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $medicines
        ], 200);
    }

    public function getByCompany($company)
    {
        $medicines = Medicine::where('company', $company)->get();

        if ($medicines->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No medicines found for this company'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $medicines
        ], 200);
    }

    public function show($id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json([
                'status' => 'error',
                'message' => 'Medicine not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $medicine
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'expiry_date' => 'required|date',
        ]);

        $medicine = Medicine::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Medicine added successfully',
            'data' => $medicine
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json([
                'status' => 'error',
                'message' => 'Medicine not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'company' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'stock' => 'sometimes|required|integer',
            'expiry_date' => 'sometimes|required|date',
        ]);

        $medicine->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Medicine updated successfully',
            'data' => $medicine
        ], 200);
    }

    public function destroy($id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json([
                'status' => 'error',
                'message' => 'Medicine not found'
            ], 404);
        }

        $medicine->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Medicine deleted successfully'
        ], 200);
    }
}