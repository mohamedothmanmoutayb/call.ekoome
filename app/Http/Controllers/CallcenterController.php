<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DateTime;
use App\Models\Lead;
use App\Models\User;
use App\Models\Citie;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Countrie;
use App\Models\historystatu;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\Input;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;

class CallcenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */  
    public function index(Request $request) 
    {

            $agents_all = User::join('history_status', function($join)
            {$join->on('users.id', '=', 'history_status.id_user');}) 
            ->where('id_role',3)->where('users.country_id', Auth::user()->country_id)
            ->select('users.id','users.name')
            ->groupBy('users.id','users.name'); 
             $date1 = date('Y-m-d');
             $date2 = date('Y-m-d', strtotime("1 days"));    //dd($agents_all->get());     
             $agents_details = clone $agents_all ; 
             return view('backend.callcenter.index', compact('agents_details','agents_all','date1','date2'));
       
    }
    public function filter(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'date1' => "required",
            'date2' => "required",
            'agent' => "required|nullable",
        ]); 
        if ($validator->fails()) {
          
            return redirect('callcenter.index')->withErrors($validator)->withInput();
        }
             $agents_all = User::join('history_status', function($join)
            {$join->on('users.id', '=', 'history_status.id_user');}) 
            ->where('id_role',3)->where('users.country_id', Auth::user()->country_id)
            ->select('users.id','users.name')
            ->groupBy('users.id','users.name'); 
             $agents_details = clone $agents_all ;  
             if($request->agent != 'all'){
                 $agents_details->where('users.id',$request->agent);
             } 
             $agent_id =$request->agent;    
             $date1 = $request->date1  ;
             $date2 = $request->date2 ;   
            return view('backend.callcenter.index', compact('agents_details','agents_all','date1','date2','agent_id'));
    }
    public function filterx(Request $request)
    {
         
        $input = $request->all();
        $validator = Validator::make($input, [
            'date' => "required",
            'date2' => "required",
            'agent' => "required|nullable",
        ]); 
    //   dd($input);
        if ($validator->fails()) {
          
            return redirect('callcenter.index')->withErrors($validator)->withInput();
        }
        // try {  
             $agents_all = User::CheckLeads()->where('id_role',3)->where('users.country_id', Auth::user()->country_id)->select('users.id','name',DB::raw('count(history_status.id_lead) as total_lead'));    
             $agents_details = clone $agents_all ;  
             if($request->agent != 'all'){
                 $agents_details->where('users.id',$request->agent);
                 
             }
             $agent_id =$request->agent;    
             $date = date('Y-m-d' ,$request->date);  
             $date2 = date('Y-m-d' ,$request->date2); 
            // dd($agents_details->toSql(), $agents_details->getBindings(),$agents_details->get());   
            return view('backend.callcenter.index', compact('agents_details','agents_all','date','date2','agent_id'));
  
        
            // } catch (\Exception $e) {
            //      dd
            //     return redirect('callcenter.index');
           
            // }
        
       
    }
    public function test(Request $request)
    {
        // select `users`.`id`, `name`, count(history_status.id_lead) as total_lead from `users` inner join `history_status` on `users`.`id` = `history_status`.`id_user` where `id_role` = 3 and `users`.`id` = 89 and `users`.`country_id` = 11 group by `users`.`name`, `users`.`id`;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function details(Request $request, $id)
    {
        //    try { 
            $url = explode('&' , $id); 
            $id_assigned = $url[0];  
            $sellers = User::where('id_role',2)->get();
            $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
            $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
            $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
            $cities = Citie::where('id_country', Auth::user()->country_id)->get();
            $countries = Countrie::where('id', Auth::user()->country_id)->get();
            $customer = $request->customer;
            $ref = $request->ref;
            $phone = $request->phone1;
            $phone2 = $request->phone2;
            $conf = $request->confirmation;
            $leads = Lead::with('product','country','cities','leadproduct')->where('leads.deleted_at',0)->where('id_country', Auth::user()->country_id)->where('id_assigned', $id_assigned)->orderBy('id', 'DESC');
        
            if(!empty($customer)){
                $leads = $leads->where('name',$customer);
            }
            if($request->seller){
                $leads = $leads->where('id_user',$request->seller);
            }
            if(!empty($ref)){
                $leads = $leads->where('n_lead',$ref);
            }
            if(!empty($phone)){
                $leads = $leads->where('phone',$phone);
            }
            if(!empty($phone2)){
                $leads = $leads->where('phone2',$phone2);
            }
            if(!empty($conf)){
                $leads = $leads->where('status_confirmation',$conf);
            }
            if(!empty($request->livraison)){
                $leads = $leads->where('status_livrison',$request->livraison);
            }
            if(!empty($store)){
                $leads = $leads->whereIn('id_product',explode(",",$productstore[0]['id']));
            }
            if($request->agent){
                $leads = $leads->where('id_assigned',$request->agent);
            }
            if($request->date){
                // dd($request->date);
                $parts = explode(' - ' , $request->date);
                //dd($parts);
                $date_from = $parts[0];
                $date_two = $parts[1];
                if($date_two == $date_from){
                    $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
                }else{
                    $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
                }
            }
            if(isset($id)){
                $url = explode('&' , $id);
                $id = $url[0];
                if(!empty($url[1])){
                    $date_from = $url[1];
                    $date_two = $url[2];
                
                    if($date_two == $date_from){
                        $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
                    }else{
                        $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
                    }
                }else{
                    
                }
    
             }
             
            $items = $request->items ?? 10; 
            $leads= $leads->get();
            
          
          
            $agents = User::where('country_id', Auth::user()->country_id)->where('users.id',$id_assigned);
             $query = Lead::where('id_assigned',$id_assigned) ; 
            $query_source  = $query->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));      
            $delivered = clone $query_source; 
            $delivered =  $delivered->where('status_confirmation', 'LIKE', '%confirmed%')->where('status_livrison', 'LIKE', '%delivered%')
            ->count(); 
    
            return view('backend.callcenter.details', compact('agents','delivered','id_assigned','products','productss','cities','countries','leads','items','sellers','date_from','date_two')); 
       
    }
}
