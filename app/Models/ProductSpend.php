<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpend extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->hasone('App\Models\Product','id','id_product');
    }

    public function ScopeTotalSpend($query,$id)
    {
        $total = SpeendAd::where('id_product_spend',$id)->sum('amount');
        return $total;
    }

    public function ScopeRevenue($query,$product)
    {
        $revenue = Lead::join('lead_products','lead_products.id_lead','leads.id')->where('status_confirmation','confirmed')->where('lead_products.id_product',$product)->sum('lead_products.lead_value');
        return $revenue;
    }
}
