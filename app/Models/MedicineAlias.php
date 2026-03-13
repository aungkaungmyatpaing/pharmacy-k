<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineAlias extends Model
{
    protected $fillable = [
        'medicine_id',
        'alias',
        'language',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
