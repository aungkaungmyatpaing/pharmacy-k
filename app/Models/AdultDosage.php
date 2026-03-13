<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdultDosage extends Model
{
    protected $fillable = [
        'medicine_id',
        'slug',
        'text',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
