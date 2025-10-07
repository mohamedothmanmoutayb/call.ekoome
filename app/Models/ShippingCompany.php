<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Countrie;
class ShippingCompany extends Model
{
    use HasFactory;
     
    protected $fillable = ['name','countries'];

    protected $casts = [
        'countries' => 'array', // Define the countries attribute as an array
    ];

    public function scopeCountry($query, $id)
    {
        $flag = '';
        $country = Countrie::find($id);
        if ($country) {
            $flag = strtolower($country->flag);
        }
        return '<i class="flag-icon flag-icon-' . $flag . ' fis rounded-circle me-1 fe" style="font-size:20px"></i>';
    }

    public function scopeCountries($query, $ids)
    {
        $countries = array();
        if($ids){
            foreach ($ids as $country) {
                $country = Countrie::find($country);
                $countries[] = '<option value="' . $country->id . '" selected>' . $country->name . '</option>';
            }
            $countries = array_merge($countries, Countrie::whereNotIn('id', $ids)->get()->map(function ($country) {
                return '<option value="' . $country->id . '">' . $country->name . '</option>';
            })->toArray());
        }else{
            $countries = array_merge($countries, Countrie::get()->map(function ($country) {
                return '<option value="' . $country->id . '">' . $country->name . '</option>';
            })->toArray());
        }
       
        //add countries where not exist in the array
       
        return $countries;

    }

    public function lastmilleinteg()
    {
        return $this->hasOne('App\Models\LastmilleIntegration','id_lastmile','id');
    }
   
}
