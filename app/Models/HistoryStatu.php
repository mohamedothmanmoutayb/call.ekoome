<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryStatu extends Model
{
    use HasFactory;

    protected $fillable = ['id_user','id_lead','country_id','id_delivery','status','date','comment'];	

    public function delivery()
    {
        return $this->hasMany('App\Models\User','id','id_user');
    }

    public function call()
    {
        return $this->hasOne('App\Models\User','id','id_assigned');
    }

    public function calluser()
    {
        return $this->hasOne('App\Models\User','id','id_user');
    }

    public function lead()
    {
        return $this->hasOne('App\Models\Lead','id','id_lead');
    }
       public function agent()
    {
        return $this->hasMany('App\Models\Lead','id_assigned','id_user');
    }
}
