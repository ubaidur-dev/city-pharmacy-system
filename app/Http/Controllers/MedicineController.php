<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController
{
    public function indexView(Request $request)
    {
        $query = Medicine::query();

        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->search_name . '%');
        }

        if ($request->filled('search_category')) {
            $query->where('category', 'like', '%' . $request->search_category . '%');
        }

        if ($request->filled('search_company')) {
            $query->where('company', 'like', '%' . $request->search_company . '%');
        }

        $medicines = $query->paginate(12);
        return view('medicines', compact('medicines'));
    }

    public function storeView(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'expiry_date' => 'required|date',
        ]);

        Medicine::create($validated);

        return redirect('/medicines')->with('success', 'Medicine added successfully!');
    }

    public function updateView(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'expiry_date' => 'required|date',
        ]);

        $medicine->update($validated);

        return redirect('/medicines')->with('success', 'Medicine updated successfully!');
    }

    public function destroyView($id)
    {
        $medicine = Medicine::findOrFail($id);
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
        $lowStockMedicines = Medicine::where('stock', '<', 10)->get();
        $expiringMedicines = Medicine::where('expiry_date', '<=', now()->addMonths(3))->get();

        return view('stock-alerts', compact('lowStockMedicines', 'expiringMedicines'));
    }

    public function salesBillingView()
    {
        $medicines = Medicine::all();
        return view('sales-billing', compact('medicines'));
    }

    public function generateBillView(Request $request)
    {
        return back()->with('success', 'Invoice generated successfully!');
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