<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineSideEffect extends Model
{
    protected $fillable = [
        'medicine_id',
        'side_effect_id',
    ];

    public function medicine ()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function sideEffect ()
    {
        return $this->belongsTo(SideEffect::class);
    }
}
