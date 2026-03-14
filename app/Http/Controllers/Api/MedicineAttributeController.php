<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Effect;
use App\Models\MedicineCategory;
use App\Models\SideEffect;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class MedicineAttributeController extends Controller
{
    use ApiResponse;

    /**
     * Get all categories
     */
    public function categories(Request $request)
    {
        $query = MedicineCategory::query()->withCount('medicines');

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('slug', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        }

        $categories = $query->orderBy('name')->get();

        return $this->successResponse($categories);
    }

    /**
     * Get all brands
     */
    public function brands(Request $request)
    {
        $query = Brand::query()->withCount('medicines');

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('company', 'LIKE', "%{$search}%");
        }

        $brands = $query->orderBy('name')->get();

        return $this->successResponse($brands);
    }

    /**
     * Get all effects
     */
    public function effects(Request $request)
    {
        $query = Effect::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $effects = $query->orderBy('name')->get();

        return $this->successResponse($effects);
    }

    /**
     * Get all side effects
     */
    public function sideEffects(Request $request)
    {
        $query = SideEffect::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $sideEffects = $query->orderBy('name')->get();

        return $this->successResponse($sideEffects);
    }
}
