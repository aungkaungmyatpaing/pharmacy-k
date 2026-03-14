<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class MedicineController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the medicines.
     * 
     * Handles search, filtering, and pagination for the medicine dictionary.
     */
    public function index(Request $request)
    {
        $query = Medicine::query()
            ->with([
                'brand:id,name',
                'categories:id,name',
                'effects:id,name',
                'sideEffects:id,name',
                'aliases:id,medicine_id,alias',
                'adultDosages',
                'pediatricDosages'
            ]);

        // 1. Keyword Search (Mainly for Dictionary Lookup)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $term = "%{$search}%";

                // Search in core fields
                $q->where('name', 'LIKE', $term)
                    ->orWhere('chemical_name', 'LIKE', $term)
                    ->orWhere('moa', 'LIKE', $term)
                    ->orWhere('dosage_form', 'LIKE', $term)

                    // Search in related models
                    ->orWhereHas('brand', function ($qBrand) use ($term) {
                        $qBrand->where('name', 'LIKE', $term);
                    })
                    ->orWhereHas('aliases', function ($qAlias) use ($term) {
                        $qAlias->where('alias', 'LIKE', $term);
                    })
                    ->orWhereHas('categories', function ($qCat) use ($term) {
                        $qCat->where('name', 'LIKE', $term);
                    })
                    ->orWhereHas('effects', function ($qEffect) use ($term) {
                        $qEffect->where('name', 'LIKE', $term);
                    })
                    ->orWhereHas('sideEffects', function ($qSideEffect) use ($term) {
                        $qSideEffect->where('name', 'LIKE', $term);
                    });
            });
        }

        // 2. Filter by Category
        if ($categoryId = $request->input('category_id')) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                // Ensure it's an array for multiple categories filter if provided
                if (is_array($categoryId)) {
                    $q->whereIn('categories.id', $categoryId);
                } else {
                    $q->where('categories.id', $categoryId);
                }
            });
        }

        // 3. Filter by Brand
        if ($brandId = $request->input('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        // 4. Filter by Dosage Form
        if ($dosageForm = $request->input('dosage_form')) {
            $query->where('dosage_form', 'LIKE', "%{$dosageForm}%");
        }

        // 5. Filter by Doctor Prescription Status
        if ($request->has('doctor_prescription')) {
            $query->where('doctor_prescription', filter_var($request->input('doctor_prescription'), FILTER_VALIDATE_BOOLEAN));
        }

        // Ordering and pagination
        $sortBy = $request->input('sort_by', 'name'); // default sort by name
        $sortOrder = $request->input('sort_order', 'asc');
        $perPage = $request->input('per_page', 15);

        // Limit sorting columns to prevent SQL injection
        $allowedSortColumns = ['id', 'name', 'chemical_name', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortColumns)) {
            $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');
        }

        $medicines = $query->paginate($perPage)->withQueryString();

        return $this->successResponse($medicines, 'Medicines retrieved successfully.');
    }

    /**
     * Display the specified medicine with full details.
     */
    public function show($id)
    {
        $medicine = Medicine::with([
            'brand',
            'categories',
            'effects',
            'sideEffects',
            'aliases',
            'adultDosages',
            'pediatricDosages'
        ])->find($id);

        if (!$medicine) {
            return $this->errorResponse('Medicine not found.', 404);
        }

        return $this->successResponse($medicine);
    }
}
