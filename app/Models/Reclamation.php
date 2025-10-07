<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;
    
    public function lead()
    {
        return $this->hasMany('App\Models\Lead','id','id_lead');
    }
}
