<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineEffect extends Model
{
    protected $fillable = [
        'medicine_id',
        'effect_id',
    ];

    public function medicine ()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function effect ()
    {
        return $this->belongsTo(Effect::class);
    }
}
