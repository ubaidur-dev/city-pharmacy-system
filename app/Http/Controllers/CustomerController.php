<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $customers = Customer::query()
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $q) . '%';
                $w->where('name', 'like', $like)->orWhere('phone', 'like', $like);
            }))
            ->withCount('orders')->withSum('orders', 'total_amount')
            ->orderByDesc('orders_sum_total_amount')->paginate(20)->withQueryString();

        return view('customers', ['customers' => $customers, 'q' => $q, 'refills' => $this->refillsDue()]);
    }

    public function show(Customer $customer)
    {
        $orders = $customer->orders()->latest()->get();

        return view('customer-show', [
            'customer' => $customer,
            'orders' => $orders,
            'spent' => (float) $orders->sum('total_amount'),
            'usual' => $this->purchaseHistory($orders)->map(fn ($dates, $name) => ['name' => $name, 'times' => $dates->count(), 'last' => $dates->max()])->sortByDesc('times')->take(8),
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:40',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date|before:tomorrow',
            'allergies' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
        ]);
        $key = Customer::keyFor($data['phone'] ?? null);
        if ($key && Customer::where('phone_key', $key)->where('id', '!=', $customer->id)->exists()) {
            return back()->withErrors(['phone' => 'Another customer already has this phone number.'])->withInput();
        }
        $customer->update($data + ['phone_key' => $key]);

        return back()->with('success', 'Customer saved.');
    }

    /** medicine name => collection of purchase dates (Y-m-d) */
    private function purchaseHistory($orders)
    {
        $history = [];
        foreach ($orders as $order) {
            foreach ($order->cart_items ?? [] as $line) {
                $history[$line['name']][] = $order->created_at->toDateString();
            }
        }

        return collect($history)->map(fn ($d) => collect($d)->unique()->sort()->values());
    }

    /** Repeat purchases: when a customer's usual interval says they are about to run out. */
    private function refillsDue()
    {
        $due = collect();
        Customer::whereHas('orders')->with('orders')->get()->each(function ($customer) use (&$due) {
            $this->purchaseHistory($customer->orders)->each(function ($dates, $name) use ($customer, &$due) {
                if ($dates->count() < 2) {
                    return;
                }
                $gaps = [];
                for ($i = 1; $i < $dates->count(); $i++) {
                    $gaps[] = Carbon::parse($dates[$i - 1])->diffInDays(Carbon::parse($dates[$i]));
                }
                $avg = max(1, (int) round(array_sum($gaps) / count($gaps)));
                $next = Carbon::parse($dates->last())->addDays($avg);
                if ($next->lte(today()->addDays(3))) {
                    $due->push(['customer' => $customer, 'medicine' => $name, 'every' => $avg, 'due' => $next, 'late' => (int) today()->diffInDays($next, false)]);
                }
            });
        });

        return $due->sortBy('due')->values()->take(30);
    }
}
