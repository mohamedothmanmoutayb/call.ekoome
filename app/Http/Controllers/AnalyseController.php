<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Lead;
use App\Models\User;
use App\Models\Store;
use Carbon\Carbon;
use App\Models\SpeendAd;
use App\Models\ProductSpend;
use App\Models\LeadProduct;
use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ShippingCompany;
use App\Models\LastmilleIntegration;
use App\Models\Countrie;
use Google\Service\Dfareporting\Country;

class AnalyseController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $idd = Auth::id();
        $role3Ids = User::where('id_role', 3)->pluck('id')->toArray();
        $id1 = array_merge([$idd], $role3Ids);

        $listseller = User::where('id_manager', Auth::user()->id)->select('id')->get()->toarray();
        if(Auth::user()->id_role != "2"){
            $listseller = $listseller;
        }
        $products = Product::where('id_country', Auth::user()->country_id)->get();
        // $total = Lead::where('type','seller')->where('id_country', Auth::user()->country_id)->where('deleted_at',0)->where('id_user', Auth::user()->id)->where('status_livrison','unpacked')->where(function($query){ $query->where('id_assigned', $id1)->orWhere('id_assigned', null); });

         if(Auth::user()->id_role != "3"){
            $total = Lead::where('type','seller')->where('id_country', Auth::user()->country_id)->where('deleted_at',0)->whereIn('id_assigned', $id1);
        }
        if($request->call_product){
            $total = $total->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $total = $total->whereDate('created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $total = $total->whereDate('leads.created_at','>=',$date_from)->whereDate('created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $total = $total->where('id_assigned',$request->call_agents);
        }
        $total = $total->count();
        $confirmed = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('deleted_at',0)->whereIn('id_assigned', $id1);
        if($request->call_product){
            $confirmed = $confirmed->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $partscall = explode(' - ' , $request->date_call);
            if(count($partscall) == 1){
                $date_1_call = $partscall[0];
                $date_2_call = $partscall[0];
            }else{
                $date_1_call = $partscall[0];
                $date_2_call = $partscall[1];
            }
        }else{
            $date_1_call = date('d-m-Y', -1);
            $date_2_call = date('d-m-Y');
        }
        
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $confirmed = $confirmed->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $confirmed = $confirmed->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $confirmed = $confirmed->where('id_assigned',$request->call_agents);
        }
        $confirmed = $confirmed->count();
        $rejected = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','canceled')->where('deleted_at',0)->whereIn('id_assigned', $id1);
        if($request->call_product){
            $rejected = $rejected->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $rejected = $rejected->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $rejected = $rejected->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $rejected = $rejected->where('id_assigned',$request->call_agents);
        }
        $rejected = $rejected->count();
        $canceledbysysteme = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','canceled by system')->where('deleted_at',0)->whereIn('id_assigned', $id1);
        if($request->call_product){
            $canceledbysysteme = $canceledbysysteme->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $canceledbysysteme = $canceledbysysteme->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $canceledbysysteme = $canceledbysysteme->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $canceledbysysteme = $canceledbysysteme->where('id_assigned',$request->call_agents);
        }
        $canceledbysysteme = $canceledbysysteme->count();
        // $new = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','new order')->where('deleted_at',0)->where('id_user', Auth::user()->id);
        $new = Lead::where('type','seller')
        ->where('id_country', Auth::user()->country_id)
        ->where('status_confirmation','new order')
        ->where('status_livrison','unpacked')
        ->where('id_assigned',null)
        ->where('deleted_at',0);

        if($request->call_product){
            $new = $new->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $new = $new->whereDate('created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $new = $new->whereDate('leads.created_at','>=',$date_from)->whereDate('created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $new = $new->where('id_assigned',$request->call_agents);
        }
        $new = $new->count();
        $duplicated = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','duplicated')->where('deleted_at',0)->whereIn('id_assigned', $id1);
        if($request->call_product){
            $duplicated = $duplicated->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $duplicated = $duplicated->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $duplicated = $duplicated->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $duplicated = $duplicated->where('id_assigned',$request->call_agents);
        }
        $duplicated = $duplicated->count();
        $wrong = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','wrong')->where('deleted_at',0)->whereIn('id_assigned', $id1);
        if($request->call_product){
            $wrong = $wrong->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $wrong = $wrong->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $wrong = $wrong->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $wrong = $wrong->where('id_assigned',$request->call_agents);
        }
        $wrong = $wrong->count();
        $outofstock = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','outofstock')->where('deleted_at',0)->whereIn('id_assigned', $id1);
        if($request->call_product){
            $outofstock = $outofstock->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $outofstock = $outofstock->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $outofstock = $outofstock->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $outofstock = $outofstock->where('id_assigned',$request->call_agents);
        }
        $outofstock = $outofstock->count();
        $noanswer = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','like','%'.'no answer'.'%')->where('deleted_at',0)->whereIn('id_assigned', $id1);
        if($request->call_product){
            $noanswer = $noanswer->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $noanswer = $noanswer->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $noanswer = $noanswer->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $noanswer = $noanswer->where('id_assigned',$request->call_agents);
        }
        $noanswer = $noanswer->count();
        $call = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','call later')->where('deleted_at',0)->whereIn('id_assigned', $id1);
        if($request->call_product){
            $call = $call->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $call = $call->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $call = $call->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $call = $call->where('id_assigned',$request->call_agents);
        }
        $call = $call->count();
        $area = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','out of area')->where('deleted_at',0)->whereIn('id_assigned', $id1);
        if($request->call_product){
            $area = $area->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $area = $area->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $area = $area->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $area = $area->where('id_assigned',$request->call_agents);
        }
        $area = $area->count();

        $blacklist = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','black listed')->where('deleted_at',0)->whereIn('id_assigned', $id1);
        if($request->call_product){
            $blacklist = $blacklist->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $blacklist = $blacklist->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $blacklist = $blacklist->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $blacklist = $blacklist->where('id_assigned',$request->call_agents);
        }
        $blacklist = $blacklist->count();
        //chart
        $chartconfirmed = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartconfirmed = $chartconfirmed->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartconfirmed = $chartconfirmed->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartconfirmed = $chartconfirmed->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartconfirmed = $chartconfirmed->where('id_assigned',$request->call_agents);
        }
        $chartconfirmed = $chartconfirmed->where('deleted_at',0)->count();
        $chartrejected = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','canceled')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartrejected = $chartrejected->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartrejected = $chartrejected->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartrejected = $chartrejected->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartrejected = $chartrejected->where('id_assigned',$request->call_agents);
        }
        $chartrejected = $chartrejected->where('deleted_at',0)->count();

        $chartcanceledbysestem = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','canceled by system')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartcanceledbysestem = $chartcanceledbysestem->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartcanceledbysestem = $chartcanceledbysestem->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartcanceledbysestem = $chartcanceledbysestem->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartcanceledbysestem = $chartcanceledbysestem->where('id_assigned',$request->call_agents);
        }
        $chartcanceledbysestem = $chartcanceledbysestem->where('deleted_at',0)->count();

        $chartnew = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','new order')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartnew = $chartnew->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartnew = $chartnew->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartnew = $chartnew->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartnew = $chartnew->where('id_assigned',$request->call_agents);
        }
        $chartnew = $chartnew->where('deleted_at',0)->count();
        $chartduplicated = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','duplicated')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartduplicated = $chartduplicated->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartduplicated = $chartduplicated->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartduplicated = $chartduplicated->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartduplicated = $chartduplicated->where('id_assigned',$request->call_agents);
        }
        $chartduplicated = $chartduplicated->where('deleted_at',0)->count();
        $chartwrong = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','wrong')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartwrong = $chartwrong->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartwrong = $chartwrong->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartwrong = $chartwrong->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartwrong = $chartwrong->where('id_assigned',$request->call_agents);
        }
        $chartwrong = $chartwrong->where('deleted_at',0)->count();
        $chartoutofstock = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','outofstock')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartoutofstock = $chartoutofstock->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartoutofstock = $chartoutofstock->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartoutofstock = $chartoutofstock->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartoutofstock = $chartoutofstock->where('id_assigned',$request->call_agents);
        }
        $chartoutofstock = $chartoutofstock->where('deleted_at',0)->count();
        $chartnoanswer = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','like','%'.'no answer'.'%')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartnoanswer = $chartnoanswer->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartnoanswer = $chartnoanswer->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartnoanswer = $chartnoanswer->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartnoanswer = $chartnoanswer->where('id_assigned',$request->call_agents);
        }
        $chartnoanswer = $chartnoanswer->where('deleted_at',0)->count();
        $chartcall = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','call later')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartcall = $chartcall->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartcall = $chartcall->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartcall = $chartcall->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartcall = $chartcall->where('id_assigned',$request->call_agents);
        }
        $chartcall = $chartcall->where('deleted_at',0)->count();
        $chartarea = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','out of area')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartarea = $chartarea->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartarea = $chartarea->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartarea = $chartarea->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartarea = $chartarea->where('id_assigned',$request->call_agents);
        }
        $chartarea = $chartarea->where('deleted_at',0)->count();

        $chartblackList = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','black listed')->whereIn('id_assigned', $id1);
        if($request->call_product){
            $chartblackList = $chartblackList->where('id_product',$request->call_product);
        }
        if($request->date_call){
            $parts = explode(' - ' , $request->date_call);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartblackList = $chartblackList->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartblackList = $chartblackList->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        if($request->call_agents){
            $chartblackList = $chartblackList->where('id_assigned',$request->call_agents);
        }
        $chartblackList = $chartblackList->where('deleted_at',0)->count();





        //shipping
        //chart shipping
        $orders = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('id_user', Auth::user()->id);
        if($request->shipped_product){
            $orders = $orders->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $orders = $orders->whereDate('created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $orders = $orders->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $orders = $orders->where('deleted_at',0)->count();
        //chart shipping
        $chartunpacked = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','unpacked');
        if($request->shipped_product){
            $chartunpacked = $chartunpacked->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartunpacked = $chartunpacked->whereDate('created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartunpacked = $chartunpacked->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $chartunpacked = $chartunpacked->where('deleted_at',0)->count();

        $chartdelivered = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','delivered')->where('id_user', Auth::user()->id);
        if($request->shipped_product){
            $chartdelivered = $chartdelivered->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartdelivered = $chartdelivered->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartdelivered = $chartdelivered->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $chartdelivered = $chartdelivered->where('deleted_at',0)->count();
        $chartpacked = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','item packed');
        // if(Auth::user()->id_role != "2"){
        //     $chartpacked = $chartpacked->whereIn('id_user', $listseller);
        // }else{
            $chartpacked = $chartpacked->where('id_user', Auth::user()->id);
        // }
        if($request->shipped_product){
            $chartpacked = $chartpacked->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartpacked = $chartpacked->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartpacked = $chartpacked->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $chartpacked = $chartpacked->where('deleted_at',0)->count();

        $chartshipped = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','shipped');
        // if(Auth::user()->id_role != "2"){
        //     $chartshipped = $chartshipped->whereIn('id_user', $listseller);
        // }else{
            $chartshipped = $chartshipped->where('id_user', Auth::user()->id);
        //}
        if($request->shipped_product){
            $chartshipped = $chartshipped->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartshipped = $chartshipped->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartshipped = $chartshipped->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $chartshipped = $chartshipped->where('deleted_at',0)->count();
        
        $chartproseccing = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','proseccing');
        // if(Auth::user()->id_role != "2"){
        //     $chartproseccing = $chartproseccing->whereIn('id_user', $listseller);
        // }else{
            $chartproseccing = $chartproseccing->where('id_user', Auth::user()->id);
        // }
        if($request->shipped_product){
            $chartproseccing = $chartproseccing->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartproseccing = $chartproseccing->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartproseccing = $chartproseccing->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $chartproseccing = $chartproseccing->where('deleted_at',0)->count();

        $chartcanceled = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','rejected');
        // if(Auth::user()->id_role != "2"){
        //     $chartcanceled = $chartcanceled->whereIn('id_user', $listseller);
        // }else{
            $chartcanceled = $chartcanceled->where('id_user', Auth::user()->id);
        // }
        if($request->shipped_product){
            $chartcanceled = $chartcanceled->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartcanceled = $chartcanceled->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartcanceled = $chartcanceled->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $chartcanceled = $chartcanceled->where('deleted_at',0)->count();
        $chartreturned = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','returned');
        // if(Auth::user()->id_role != "2"){
        //     $chartreturned = $chartreturned->whereIn('id_user', $listseller);
        // }else{
            $chartreturned = $chartreturned->where('id_user', Auth::user()->id);
        // }
        if($request->shipped_product){
            $chartreturned = $chartreturned->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartreturned = $chartreturned->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartreturned = $chartreturned->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $chartreturned = $chartreturned->where('deleted_at',0)->count();
        $charttransit = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','in transit');
        // if(Auth::user()->id_role != "2"){
        //     $charttransit = $charttransit->whereIn('id_user', $listseller);
        // }else{
            $charttransit = $charttransit->where('id_user', Auth::user()->id);
        // }
        if($request->shipped_product){
            $charttransit = $charttransit->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $charttransit = $charttransit->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $charttransit = $charttransit->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $charttransit = $charttransit->where('deleted_at',0)->count();
        $chartpicking = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','picking process');
        // if(Auth::user()->id_role != "2"){
        //     $chartpicking = $chartpicking->whereIn('id_user', $listseller);
        // }else{
            $chartpicking = $chartpicking->where('id_user', Auth::user()->id);
        // }
        if($request->shipped_product){
            $chartpicking = $chartpicking->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartpicking = $chartpicking->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartpicking = $chartpicking->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $chartpicking = $chartpicking->where('deleted_at',0)->count();
        $chartincident = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','incident');
        // if(Auth::user()->id_role != "2"){
        //     $chartincident = $chartincident->whereIn('id_user', $listseller);
        // }else{
            $chartincident = $chartincident->where('id_user', Auth::user()->id);
        //}
        if($request->shipped_product){
            $chartincident = $chartincident->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartincident = $chartincident->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartincident = $chartincident->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $chartincident = $chartincident->where('deleted_at',0)->count();
        $chartindelivery = Lead::where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','in delivery');
        // if(Auth::user()->id_role != "2"){
        //     $chartindelivery = $chartindelivery->whereIn('id_user', $listseller);
        // }else{
            $chartindelivery = $chartindelivery->where('id_user', Auth::user()->id);
        //}
        if($request->shipped_product){
            $chartindelivery = $chartindelivery->where('id_product',$request->shipped_product);
        }
        if($request->date_shipped){
            $parts = explode(' - ' , $request->date_shipped);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $chartindelivery = $chartindelivery->whereDate('leads.created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $chartindelivery = $chartindelivery->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at','<=',$date_two);
            }
        }
        $chartindelivery = $chartindelivery->where('deleted_at',0)->count();

        $quantity_stock = Stock::join('products','products.id','stocks.id_product')->with('products')->where('id_country', Auth::user()->country_id);
        // if(Auth::user()->id_role != "2"){
        //     $quantity_stock = $quantity_stock->whereIn('id_user', $listseller);
        // }else{
            $quantity_stock = $quantity_stock->where('id_user', Auth::user()->id);
        //}
        $quantity_stock = $quantity_stock->orderby('stocks.created_at','desc')->get();
        $low_stock_product = Product::join('stocks','stocks.id_product','products.id')->where('products.id_country', Auth::user()->country_id);
        // if(Auth::user()->id_role != "2"){
        //     $low_stock_product = $low_stock_product->whereIn('id_user', $listseller);
        // }else{
            $low_stock_product = $low_stock_product->where('id_user', Auth::user()->id);
        //}
        $low_stock_product = $low_stock_product->select(DB::raw('products.name, products.image , products.low_stock, SUM(stocks.qunatity) AS quantity'))->groupBy('name','image','low_stock')->get();
        $import_product = Product::join('stocks','stocks.id_product','products.id')->where('products.id_country', Auth::user()->country_id);
        // if(Auth::user()->id_role != "2"){
        //     $import_product = $import_product->whereIn('id_user', $listseller);
        // }else{
            $import_product = $import_product->where('id_user', Auth::user()->id);
        //}
        $import_product = $import_product->select(DB::raw('products.name, products.image, SUM(stocks.qunatity) AS quantity '))->groupBy('name','image')->get();






        // net profite

        $stores = Store::where('id_country',Auth::user()->country_id)->where('id_user',Auth::user()->id)->get();
        $agents = User::where('country_id',Auth::user()->country_id)->where('id_manager',Auth::user()->id)->where('id_role','3')->get();
        $shippings = LastmilleIntegration::where('id_country',Auth::user()->country_id)->where('id_user',Auth::user()->id)->get();
        $productspeend = ProductSpend::where('country_id',Auth::user()->country_id);
        if($request->profite_product){
            $productspeend = $productspeend->where('id_product',$request->profite_product);
        }
        $productspeend = $productspeend->select('id')->get();
        $speendads = SpeendAd::where('country_id',Auth::user()->country_id)->wherein('id_product_spend',$productspeend)->where('user_id',Auth::user()->id);
        if($request->profit_date){
            $parts = explode(' - ' , $request->profit_date);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $speendads = $speendads->whereDate('date', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $speendads = $speendads->whereDate('date','>=',$date_from)->whereDate('date','<=',$date_two);
            }
        }
        // else{
        //     $speendads = $speendads->whereDate('date','>=',Carbon::now()->startOfMonth()->format('Y-m-d'))->whereDate('date','<=',Carbon::now()->format('Y-m-d'));
        // }
        $speendads = $speendads->sum('amount');
        //calculate revenue
        $orderdev = Lead::where('id_user',Auth::user()->id)->where('id_country',Auth::user()->country_id);
        if($request->profit_date){
            $parts = explode(' - ' , $request->profit_date);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $orderdev = $orderdev->whereDate('created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $orderdev = $orderdev->whereDate('created_at','>=',$date_from)->whereDate('created_at','<=',$date_two);
            }
        }
        // else{
        //     $orderdev = $orderdev->whereDate('created_at','>=',Carbon::now()->startOfMonth()->format('Y-m-d'))->whereDate('created_at','<=',Carbon::now()->format('Y-m-d'));
        // }
        if($request->profite_product){
            $orderdev = $orderdev->where('id_product',$request->profite_product);
        }
        $totalead = $orderdev->count();
        if($request->profite_agents){
            $orderdev = $orderdev->where('id_assigned',$request->profite_agents);
        }
        $orderdev = $orderdev->where('status_confirmation','confirmed');
        $totaleadconfirmed = $orderdev->count();
        $revenuesconfirmed = $orderdev->sum('lead_value');
        $orderdev = $orderdev->wherein('status_livrison',['delivered','returned']);
        $totaldev = $orderdev->count();
        $feessdelivered = $orderdev->sum('fees_livrison');
        $revenues = $orderdev->sum('lead_value');
        
        //calculate fees products
        $orderdev = $orderdev->get();
        $sumproduct = 0;
        foreach($orderdev as $v_order){
            $leadorder = LeadProduct::where('id_lead',$v_order->id)->get();
            foreach($leadorder as $v_pro){
                $product = Product::where('id',$v_pro->id_product)->first();
                $sumproduct = $sumproduct + ($v_pro->quantity * $product->price);
            }
        }

        $expensse = Expense::where('id_country',Auth::user()->country_id)->where('id_user',Auth::user()->id);
        if($request->profit_date){
            $parts = explode(' - ' , $request->profit_date);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            if(count($parts) == 1){
                $expensse = $expensse->whereDate('created_at', $date_from);
            }else{
                $date_two = date('Y-m-d', strtotime($parts[1]));
                $expensse = $expensse->whereDate('created_at','>=',$date_from)->whereDate('created_at','<=',$date_two);
            }
        }
        // else{
        //     $expensse = $expensse->whereDate('created_at','>=',Carbon::now()->startOfMonth()->format('Y-m-d'))->whereDate('created_at','<=',Carbon::now()->format('Y-m-d'));
        // }
        $expensse = $expensse->sum('amount');
    
        //cost per lead
        if($totalead != 0){
            $costperlead = ( ($speendads + $sumproduct )) / $totalead;
        }else{
            $costperlead = 0;
        }
        //cost per Confirmed
        if($totaleadconfirmed != 0){
            $costperconfirmed = ( ($speendads + $sumproduct)) / $totaleadconfirmed;
        }else{
            $costperconfirmed = 0;
        }
        //cost per delivered
        if($totaldev != 0){
            $costperdelivered = ( ($speendads + $sumproduct)) / $totaldev;
        }else{
            $costperdelivered = 0;
        }

        $totalspeend = $speendads + $sumproduct + $expensse + $feessdelivered;
        if($totalspeend != 0){
            $roi = (($revenues / $totalspeend ) * 100);
        }else{
            $roi = 0;
        }
        

        $country = Countrie::where('id',Auth::user()->country_id)->first();
        $lastmille = LastMilleIntegration::where('id_country',Auth::user()->country_id)->where('id_user', Auth::user()->id)->select('id_lastmile')->get();
        $companys = ShippingCompany::wherein('id',$lastmille)->get();
        $call_product = $request->call_product;

        return view('backend.analyses.index', compact('products','total','country','confirmed','rejected','canceledbysysteme','new','duplicated','wrong','outofstock','noanswer','call','area','blacklist','chartconfirmed','chartrejected','chartcanceledbysestem','chartnew','chartduplicated','chartwrong','chartoutofstock','chartnoanswer','chartcall','chartarea','chartblackList','orders','chartunpacked','chartdelivered','chartpacked','chartshipped','chartproseccing','chartcanceled','chartreturned','charttransit','chartpicking','chartincident','chartindelivery','quantity_stock','low_stock_product','import_product','stores','agents','shippings','speendads','sumproduct','costperlead','costperconfirmed','costperdelivered','revenues','feessdelivered','expensse','lastmille','companys','date_1_call','date_2_call','roi','call_product'));
    }

    public function details(Request $request)
    {
        $agent = $request->id_agent;
        $date_1_call = $request->date1;
        $date_2_call = $request->date2;
        $call_product = $request->call_product;

        $leads = Lead::where('id_assigned',$agent)->where('status_confirmation','confirmed');
            if($date_1_call == $date_2_call){
                $leads = $leads->whereDate('created_at',date('Y-m-d', strtotime($date_1_call)));
            }else{
                $leads = $leads->whereDate('created_at','>=',date('Y-m-d', strtotime($date_1_call)))->whereDate('created_at','<=',date('Y-m-d', strtotime($date_2_call)));
            }
            if($call_product){
                $leads = $leads->where('id_product',$call_product);
            }
        $leads = $leads->groupby('quantity')->select(DB::raw('count(id) as count'),'quantity')->get();
        $products = Product::where('id_country', Auth::user()->country_id)->get();
        $country = Countrie::where('id',Auth::user()->country_id)->first();

        return view('backend.analyses.details', compact('leads','products','country','date_1_call','date_2_call','agent','call_product'));
    }
}
