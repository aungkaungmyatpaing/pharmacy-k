<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineCategory extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name', 'slug', 'description'];

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'category_medicine', 'category_id', 'medicine_id')->withTimestamps();
    }
}
