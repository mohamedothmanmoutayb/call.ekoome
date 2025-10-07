<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedProduct extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->hasone('App\Models\Product','id','id_product');
    }

    public function agent()
    {
        return $this->hasone('App\Models\User','id','id_agent');
    }
}
