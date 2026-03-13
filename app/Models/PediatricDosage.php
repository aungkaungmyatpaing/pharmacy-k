<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PediatricDosage extends Model
{
    protected $fillable = [
        'medicine_id',
        'age_group',
        'weight_kg_min',
        'weight_kg_max',
        'slug',
        'text',
    ];

    protected $casts = [
        'weight_kg_min' => 'decimal:1',
        'weight_kg_max' => 'decimal:1',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
