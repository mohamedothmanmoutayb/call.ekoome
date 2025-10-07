<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ShippingCompany;

class SuiviController extends Controller
{
    public function index(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();

        
        $leads = Lead::where('type', 'seller')
            ->with('product', 'leadpro', 'country', 'cities', 'livreur','shippingcompany')
            ->where('status_livrison', 'proseccing')
            ->where('id_country', Auth::user()->country_id)
            ->where('deleted_at', 0)
            ->orderBy('id', 'DESC');

        $shippingCompanies = ShippingCompany::all();
    
        if(!empty($request->customer)){
            $leads = $leads->where('name', $request->customer);
        }
        if(!empty($request->ref)){
            $leads = $leads->where('n_lead', $request->ref);
        }
        if(!empty($request->phone1)){
            $leads = $leads->where(function($query) use ($request) {
                $query->where('phone', 'like', '%'.$request->phone1.'%')
                    ->orWhere('phone2', 'like', '%'.$request->phone1.'%');
            });
        }
        if(!empty($request->city)){
            $leads = $leads->where('id_city', $request->city);
        }
        if(!empty($request->id_prod)){
            $leads = $leads->where('id_product', $request->id_prod);
        }
        if(!empty($request->shipping_company)){
            $leads = $leads->where('id_last_mille', $request->shipping_company);
        }
        // if(!empty($request->date)){
        //     $parts = explode(' - ', $request->date);
        //     $date_from = \Carbon\Carbon::createFromFormat('m/d/Y', trim($parts[0]))->format('Y-m-d');
        //     $date_two  = \Carbon\Carbon::createFromFormat('m/d/Y', trim($parts[1]))->format('Y-m-d');
        //     $leads = $leads->whereBetween('date_confirmed', [$date_from, $date_two]);
        // }
        
        $allLeads = $leads->get();

        
        $yesterdayLeads = $allLeads->filter(function($lead) {
            return $lead->created_at->isYesterday();
        });
        
        $twoDaysAgoLeads = $allLeads->filter(function($lead) {
            return $lead->created_at->isSameDay(now()->subDays(2));
        });
        
        $threeDaysAgoLeads = $allLeads->filter(function($lead) {
            return $lead->created_at->isSameDay(now()->subDays(3));
        });
        
        $olderLeads = $allLeads->filter(function($lead) {
            return $lead->created_at->lt(now()->subDays(3));
        });

        
        $items = $request->items ?: 10;
        
        $yesterdayLeads = new LengthAwarePaginator(
            $yesterdayLeads->forPage(1, $items),
            $yesterdayLeads->count(),
            $items,
            1,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        $twoDaysAgoLeads = new LengthAwarePaginator(
            $twoDaysAgoLeads->forPage(1, $items),
            $twoDaysAgoLeads->count(),
            $items,
            1,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        $threeDaysAgoLeads = new LengthAwarePaginator(
            $threeDaysAgoLeads->forPage(1, $items),
            $threeDaysAgoLeads->count(),
            $items,
            1,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        $olderLeads = new LengthAwarePaginator(
            $olderLeads->forPage(1, $items),
            $olderLeads->count(),
            $items,
            1,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        $type = "Processing";
        return view('backend.suivi.index', compact('proo', 'products', 'productss', 'yesterdayLeads', 'twoDaysAgoLeads', 'threeDaysAgoLeads', 'olderLeads', 'items', 'type','leads','shippingCompanies','allLeads'));
    }
}
