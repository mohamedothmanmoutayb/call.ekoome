<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingStock extends Model
{
    use HasFactory;
    
    public function tagier()
    {
        return $this->hasMany('App\Models\Tagier'  ,'id' , 'id_tagier');
    }
    
    public function tagie()
    {
        return $this->hasOne('App\Models\Tagier'  ,'id' , 'id_tagier');
    }
    
    public function stock()
    {
        return $this->hasMany('App\Models\Stock'  ,'id' , 'id_stock');
    }
}
