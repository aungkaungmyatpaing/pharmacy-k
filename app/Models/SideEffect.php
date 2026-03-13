<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SideEffect extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'text',
    ];

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'medicine_side_effects')
            ->withTimestamps();
    }
}
