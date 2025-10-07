<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use Auth;

class Product extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->hasMany('App\Models\User'  ,'id' , 'id_user');
    }

    public function seller()
    {
        return $this->hasOne('App\Models\User'  ,'id' , 'id_user');
    }

    public function imports()
    {
        return $this->hasMany('App\Models\Import'  ,'id_product' , 'id');
    }

    public function upselles()
    {
        return $this->hasMany('App\Models\Upsel'  ,'id_product' , 'id');
    }

    public function leadpro()
    {
        return $this->hasMany('App\Models\LeadProduct'  ,'id_product' , 'id');
    }

    public function stock()
    {
        return $this->hasOne('App\Models\Stock','id_product','id');
    }

    public function stocks()
    {
        return $this->hasOne('App\Models\Stock','id_product','id');
    }

    public function scopeCountLead($query,$id)
    {
        $id = $this->id;
        $date = new DateTime();
        $date = $date->format('Y-m-d');
        $lead = LeadProduct::where('id_product',$id)->whereDate('date_delivred','=',$date)->where('livrison','=','unpacked')->where('outstock',0)->where('isreturn',0)->where('outofstock',0)->sum('quantity');
        return $lead;
    }

    public function scopeCountLeadG($query,$id)
    {
        $id = $this->id;
        $lead = LeadProduct::where('id_product',$id)->where('date_delivred','!=',Null)->where('livrison','=','unpacked')->where('outstock',0)->where('isreturn',0)->where('outofstock',0)->sum('quantity');
        return $lead;
    }

    public function scopeMapping($query,$id)
    {
        $id = $this->id;
        $stock = Stock::where('id_product',$id)->first();
        if(!empty($stock->id)){
            $map = MappingStock::where('id_stock',$stock->id)->first();
            if(!empty($map)){
                $tag = Tagier::where('id',$map->id_tagier)->first();
                //dd($tag);
                $diff = $stock->quantity_accept - $map->quantity_map;
                return $tag->cod_bar ;
            }else{
                return 0;
            }

        }else{
            return 0;
        }
    }

    public function scopeNotMapping($query,$id)
    {
        $id = $this->id;
        $stock = Stock::where('id_product',$id)->first();
        if(!empty($stock->id)){
            $map = MappingStock::where('id_stock',$stock->id)->first();
            if(!empty($map)){
                $tag = Tagier::where('id',$map->id_tagier)->first();
                //dd($tag);
                $diff = $stock->quantity_accept - $map->quantity_map;
                return $diff;
            }else{
                return 0;
            }

        }else{
            return 0;
        }
    }

    public function scopeQuantityTotal($query,$id)
    {
        $id = $this->id;
        $stock = Stock::where('id_product',$id)->first();
        if(!empty($stock->id)){
            $map = MappingStock::where('id_stock',$stock->id)->first();
            if(!empty($map)){
                $tag = Tagier::where('id',$map->id_tagier)->first();
                //dd($tag);
                $diff = $stock->quantity_accept;
                return $diff;
            }else{
                return 0;
            }

        }else{
            return 0;
        }
    }

    public function scopeCheckStock($query,$id)
    {
        $id = $this->id;
        $stock = Stock::where('id_product',$id)->first();
        if(!empty($stock->id)){
            return $stock->id;
        }else{
            return "0";
        }

    }

    public function scopeCountStock($query,$id)
    {
        $id = $this->id;
        $stock = Stock::where('id_product',$id)->first();
        if(!empty($stock->id)){
            $mapping = MappingStock::where('id_stock',$stock->id)->first();
            if(!empty($mapping->id)){
                return (string)$mapping->quantity;
            }else{
                return "0";
            }
        }else{
            $stock = 0;
            return $stock;
        }

    }

    public function scopeDeffMApping($query,$id)
    {
        $id = $this->id;
        $stock = Stock::where('id_product',$id)->where('id_warehouse',Auth::user()->country_id)->first();
        if(!empty($stock->id)){
            $mapping = MappingStock::where('id_stock',$stock->id)->first();
            if(!empty($mapping->id)){
                return  $stock->quantity_accept - $mapping->quantity_map;
            }else{
                return $stock->quantity_accept;
            }
        }else{
            $stock = 0;
            return $stock;
        }
    }
}
