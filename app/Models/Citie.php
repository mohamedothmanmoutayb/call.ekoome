<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citie extends Model
{
    use HasFactory;

    public function scopeByProvince($query, $provinceId)
    {
        return $query->where('id_province', $provinceId);
    }
}
