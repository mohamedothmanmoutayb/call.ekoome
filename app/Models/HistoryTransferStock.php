<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryTransferStock extends Model
{
    use HasFactory;

    public function stock()
    {
        return $this->hasMany('App\Models\Stock','id','id_stock');
    }

    public function users()
    {
        return $this->hasMany('App\Models\User','id','id_user');
    }

    public function newtagier()
    {
        return $this->hasMany('App\Models\Tagier','id','new_tagier');
    }

    public function lasttagier()
    {
        return $this->hasMany('App\Models\Tagier','id','last_tagier');
    }
}
