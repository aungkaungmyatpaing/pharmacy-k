<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'brand_id',
        'name',
        'chemical_name',
        'doctor_prescription',
        'caution',
        'moa',
        'drug_interactions',
        'storage_conditions',
        'overdose_emergency',
        'contraindications',
        'dosage_form',
        'strength',
        'route_of_administration',
        'pregnancy_category',
        'lactation_safety',
    ];

    protected $casts = [
        'doctor_prescription' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function effects()
    {
        return $this->belongsToMany(Effect::class, 'medicine_effects')
            ->withTimestamps();
    }

    public function sideEffects()
    {
        return $this->belongsToMany(SideEffect::class, 'medicine_side_effects')
            ->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(MedicineCategory::class, 'category_medicine', 'medicine_id', 'category_id')
            ->withTimestamps();
    }

    public function adultDosages()
    {
        return $this->hasMany(AdultDosage::class);
    }

    public function pediatricDosages()
    {
        return $this->hasMany(PediatricDosage::class);
    }

    public function aliases()
    {
        return $this->hasMany(MedicineAlias::class);
    }
}
