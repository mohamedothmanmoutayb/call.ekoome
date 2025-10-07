<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadProduct extends Model
{
    use HasFactory;

    protected $fillable = ['id_lead','isupsell','id_product','isupselle_seller','date_delivred','livrison','outstock','outofstock','isreturn','quantity','lead_value','total'];

    
    public function product()
    {
        return $this->hasMany('App\Models\Product','id','id_product');
    }
    
    public function leads()
    {
        return $this->hasMany('App\Models\Lead','id','id_lead');
    }
}
