<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Lead;
use App\Models\Stock;
use App\Models\Citie;
use App\Models\Product;
use App\Models\HistoryStatu;
use App\Models\LeadProduct;
use App\Models\Permission;
use App\Models\AgentRole;
use App\Models\RoleHasPermission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\ExportStaff;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        if(Auth::user()->id_role != "3"){

            $staffs = User::where('id_role','3')->where('users.is_active',1)->get();
            $roles = AgentRole::get();
            return view('backend.staffs.index', compact('staffs','roles'));
        }
        return back();
    }

    public function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'telephone' => $request->phone,
            'email' => $request->email,
            'country_id' => Auth::user()->country_id,
            'id_role' => '3',
            'is_active' => '1',
            'password' => Hash::make($request->password),
        ];
        User::insert($data);
        return response()->json(['success'=>true]);
    }

    public function inactive(Request $request)
    {
        $data = [
            'is_active' => '0',
        ];
        User::where('id', $request->id)->update($data);
        return response()->json(['success'=>true]);
    }

    public function active(Request $request)
    {
        $data = [
            'is_active' => '1',
        ];
        User::where('id', $request->id)->update($data);
        return response()->json(['success'=>true]);
    }

    public function roles(Request $request)
    {
        if(Auth::user()->id_role != "3"){
            $users = AgentRole::with(['users'])->get();
            //dd($users);
            return view('backend.staffs.roles', compact('users'));
        }
        return back();
    }

    public function rolestore(Request $request)
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        AgentRole::insert($data);
        return response()->json(['success'=>true]);
    }

    public function permissions()
    {
        if(Auth::user()->id_role != "3"){
            $roles = AgentRole::get();
            $users = AgentRole::with(['permission' => function($query){
                $query->with('permission');
            }])->get();//dd($users);
            return view('backend.staffs.permissions', compact('roles','users'));
        }
        return back();
    }

    public function permissionstore(Request $request)
    {
        /*dd($request->all());
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->save();*/

        //dd($permission->id);
        foreach($request->permission as $v_permission){
            $data = [
                'permission_id' => $v_permission,
                'role_id' => $request->id_role,
            ];
            RoleHasPermission::insert($data);
        }

        return Redirect()->back();
        
    }

    public function reset(Request $request)
    {
        $data = [
            'password' => Hash::make(123456),
        ];
        User::where('id',$request->id)->update($data);
        return response()->json(['success'=>true]);
    }

    public function profile()
    {
        $user = User::with('role')->where('id', Auth::user()->id)->get();
        $user = $user->first();

        return view('backend.profile', compact('user'));
    }
    
    public function updateprofile(Request $request, User $user)
    {
        if(!empty($request->fullname) && !empty($request->phone)){
            if(!empty($request->newpass)){
                if($request->newpass != $request->conpass){
                    return response()->json(['pass'=>'pass']);
                }else{
                    $data = [
                        'name' => $request->fullname,
                        'company' => $request->company,
                        'telephone' => $request->phone,
                        'password' => Hash::make($request->newpass),
                    ];
                    User::where('id', Auth::user()->id)->update($data);
                    return response()->json(['success'=>true]);
                }
            }else{
                
                $data = [
                    'name' => $request->fullname,
                    'company' => $request->company,
                    'telephone' => $request->phone,
                ];
                User::where('id', Auth::user()->id)->update($data);
                return response()->json(['success'=>true]);
            }
        }else{
            return response()->json(['success'=> 'remplier']);
        }
    }

    // public function performence(Request $request,$id)
    // {        
    //     $leadss = Product::join('lead_products','lead_products.id_product','products.id')->join('leads','leads.id','lead_products.id_lead')->where('leads.id_assigned',$id)->where('status_confirmation','confirmed')->select('products.sku AS sku' ,'products.image As image' , 'products.name AS product ' , 'products.link AS link' , \DB::raw('sum(lead_products.quantity) AS quantity'))->groupby('products.sku','products.image','products.name','products.link')->get();
        
    //     //dd($leads);
    //     $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    //     $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    //     $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    //     $cities = Citie::where('id_country', Auth::user()->country_id)->get();
    //     $date = Carbon::now();
    //     $lead = Lead::where('type','seller')->with('product','country','cities')->where('status_confirmation','new order')->orderBy('n_lead', 'DESC')->limit(1)->first();

    //             $total_lead_value = Lead::where('id_assigned', $id)->sum('lead_value');
    //         $total_upsel = LeadProduct::with(['leads' => function($query){
    //             $query->where('id_assigned', $id);
    //         }])->sum('lead_value');
    //         $upsell = Lead::join('lead_products','lead_products.id_lead','leads.id')->where('type','seller')->where('id_assigned', $id)->sum('lead_products.lead_value');
    //         //dd($upsell);
    //         $total = $total_lead_value + $total_upsel ;
    //         $total_lead = Lead::where('id_assigned', $id);
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             $total_lead = $total_lead->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
    //         }
    //         $total_lead = $total_lead->count();
            
    //         $confirmed = HistoryStatu::join('leads','leads.id','history_status.id_lead')->where('history_status.status','confirmed');
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             if($date_from == $date_two){
    //                 $confirmed = $confirmed->whereDate('history_status.created_at',$date_from);
    //             }else{
    //                 $confirmed = $confirmed->whereBetween('history_status.created_at',[$date_from , $date_two]);
    //             }
    //         }else{
    //             $confirmed = $confirmed->whereDate('history_status.created_at', $date->format('Y-m-d'));
    //         }

    //         $confirmed = $confirmed->where('history_status.id_user', $id)->select('history_status.id_user','history_status.id_lead')->distinct('history_status.id_lead')->count();

    //         $canceled = HistoryStatu::join('leads','leads.id','history_status.id_lead')->where('history_status.status','like','%'.'canceled'.'%');
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             if($date_from == $date_two){
    //                 $canceled = $canceled->whereDate('history_status.created_at',$date_from);
    //             }else{
    //                 $canceled = $canceled->whereBetween('history_status.created_at',[$date_from , $date_two]);
    //             }
    //         }else{
    //             $canceled = $canceled->whereDate('history_status.created_at', $date->format('Y-m-d'));
    //         }
    //         $canceled = $canceled->where('history_status.id_user', $id)->select('history_status.id_user','history_status.id_lead')->distinct('history_status.id_lead')->count();

    //         $noanswer = HistoryStatu::join('leads','leads.id','history_status.id_lead')->where('history_status.status','like','%'.'no answer'.'%');
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             if($date_from == $date_two){
    //                 $noanswer = $noanswer->whereDate('history_status.created_at',$date_from);
    //             }else{
    //                 $noanswer = $noanswer->whereBetween('history_status.created_at',[$date_from , $date_two]);
    //             }
    //         }else{
    //             $noanswer = $noanswer->whereDate('history_status.created_at', $date->format('Y-m-d'));
    //         }
    //         $noanswer = $noanswer->where('history_status.id_user', $id)->select('history_status.id_user','history_status.id_lead')->distinct('history_status.id_lead')->count();
            
    //         $delivred = Lead::where('id_assigned', $id)->where('status_livrison','delivred');
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             $delivred = $delivred->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
    //         }
    //         $delivred = $delivred->count();

    //         $calllater = HistoryStatu::join('leads','leads.id','history_status.id_lead')->where('history_status.status','like','%'.'call later'.'%');
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             if($date_from == $date_two){
    //                 $calllater = $calllater->whereDate('history_status.created_at',$date_from);
    //             }else{
    //                 $calllater = $calllater->whereBetween('history_status.created_at',[$date_from , $date_two]);
    //             }
    //         }else{
    //             $calllater = $calllater->whereDate('history_status.created_at', $date->format('Y-m-d'));
    //         }
    //         $calllater = $calllater->where('history_status.id_user', $id)->select('history_status.id_user','history_status.id_lead')->distinct('history_status.id_lead')->count();

    //         $process = Lead::where('id_assigned', $id)->where('status_confirmation','confirmed')->where('status_livrison','unpacked');
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             $process = $process->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
    //         }
    //         $process = $process->count();
    //         $rate_confirmed = Lead::where('id_assigned', $id)->where('status_confirmation','confirmed');
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             $rate_confirmed = $rate_confirmed->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
    //         }
    //         if($total_lead == 0){
    //             $rate_confirmed = 0;
    //         }else{
    //             $rate_confirmed = round(((float)$rate_confirmed->count() / $total_lead) * 100) ;
    //         }
    //         $rate_canceled = Lead::where('id_assigned', $id)->where('status_confirmation','canceled');
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             $rate_canceled = $rate_canceled->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
    //         }
    //         if($total_lead == 0){
    //             $rate_canceled = 0;
    //         }else{
    //             $rate_canceled = round(((float)$rate_canceled->count() / $total_lead) * 100) ;
    //         }
            
    //         $rate_noanswer = Lead::where('id_assigned', $id)->wherein('status_confirmation',['no answer' , 'no answer 2' , 'no answer 3' , 'no answer 4' , 'no answer 5' , 'no answer 6' , 'no answer 7' , 'no answer 8' , 'no answer 9']);
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             $rate_noanswer = $rate_noanswer->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
    //         }
    //         if($total_lead == 0){
    //             $rate_noanswer = 0 ;
    //         }else{
    //             $rate_noanswer = round(((float)$rate_noanswer->count() / $total_lead) * 100) ;
    //         }
            
    //         $rate_delivred = Lead::where('id_assigned', $id)->where('status_livrison','delivred');
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             $rate_delivred = $rate_delivred->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
    //         }
    //         if($total_lead == 0){
    //             $rate_delivred = 0;
    //         }else{
    //             $rate_delivred = round(((float)$rate_delivred->count() /  $total_lead) * 100);
    //         }
    //         $upsel = Lead::join('lead_products','lead_products.id_lead','=','leads.id')->where('isupsell','1')->where('status_confirmation','confirmed')->where('id_assigned', $id)->where('id_country', Auth::user()->country_id);
    //         if(!empty($request->date)){
    //             $parts = explode(' - ' , $request->date);
    //             $date_from = $parts[0];
    //             $date_two = $parts[1];
    //             $upsel = $upsel->whereBetween('leads.last_status_change', [$date_from , $date_two]);
    //         }
    //         if($confirmed == 0){
    //             $upsel = 0;
    //         }else{
    //             $upsel = round(((float)$upsel->count() /  $confirmed ) * 100);
    //         }
    //         $order_canceled = Lead::with('product')->where('status_confirmation','canceled')->where('id_assigned', $id)->get();
    //         $order_no_delivry = Lead::with('product')->where('status_confirmation','confirmed')->where('status_livrison','unpacked')->where('id_assigned', $id)->get();

            
    //     $neworder = Lead::where('status_confirmation','new order')->get();
    //     $proo = Product::get();
    //     $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    //     $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    //     $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    //     $cities = Citie::where('id_country', Auth::user()->country_id)->get();
    //     $customer = $request->customer;
    //     $ref = $request->ref;
    //     $phone = $request->phone1;
    //     $city = $request->city;
    //     $product = $request->id_prod;
    //     $confirmation = $request->confirmation;
    //     $leads = Lead::with('product','leadpro','country','cities','livreur')->where('id_country', Auth::user()->country_id)->where('id_assigned',$id)->orderBy('id', 'DESC');
    //     if($request->items){
    //         $items = $request->items;
    //     }else{
    //         $items = 10;
    //     }
    //     if(!empty($request->ids)){//dd($request->ids);
    //         $leads = $leads->where('id_assigned',$request->ids);
    //     }
    //     if(!empty($customer)){
    //         $leads = $leads->where('name',$customer);
    //     }
    //     if(!empty($ref)){
    //         $leads = $leads->where('n_lead',$ref);
    //     }
    //     if(!empty($phone)){
    //         $leads = $leads->where('phone','like','%'.$phone.'%');
    //     }
    //     if(!empty($phone)){
    //         $leads = $leads->where('phone2','like','%'.$phone.'%');
    //     }
    //     if(!empty($city)){
    //         $leads = $leads->where('id_city',$city);
    //     }
    //     if(!empty($product)){
    //         $leads = $leads->where('id_product',$product);
    //     }
    //     if(!empty($confirmation)){
    //         $leads = $leads->whereIn('status_confirmation',$confirmation);
    //     }
    //     if(!empty($request->date)){
    //         $parts = explode(' - ' , $request->date);
    //         $date_from = $parts[0];
    //         $date_two = $parts[1];
    //         if($date_two == $date_from){
    //             $leads = $leads->whereDate('last_contact', $date_two);
    //         }else{
    //             $leads = $leads->whereBetween('last_contact', [$date_from , $date_two]);
    //         }
            
    //     }
    //     $leads= $leads->paginate($items);
       
       
    //     return view('backend.staffs.performence', compact('leadss','lead','upsel','total_lead','cities','products','productss','calllater','product','rate_confirmed','rate_canceled','noanswer','rate_noanswer','rate_delivred','confirmed','upsell','canceled','delivred','process','order_canceled','order_no_delivry','id','leads','items'));
    // }

    public function performence(Request $request,$id)
    {        
        $leadss = Product::join('lead_products','lead_products.id_product','products.id')
                            ->join('leads','leads.id','lead_products.id_lead')
                            ->where('leads.type','seller')
                            ->where('leads.id_assigned',$id)
                            ->where('status_confirmation','confirmed')
                            ->select('products.sku AS sku' ,'products.image As image' , 'products.name AS product ' , 'products.link AS link' , \DB::raw('sum(lead_products.quantity) AS quantity'))->groupby('products.sku','products.image','products.name','products.link')->get();
                            
        //dd($leads);
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        $date = Carbon::now();
        $lead = Lead::with('product','country','cities')->where('type','seller')->where('status_confirmation','new order')->orderBy('n_lead', 'DESC')->limit(1)->first();

                $total_lead_value = Lead::where('id_assigned', $id)->where('type','seller')->sum('lead_value');
            $total_upsel = LeadProduct::with(['leads' => function($query){
                $query->where('id_assigned', $id);
            }])->sum('lead_value');
            $upsell = Lead::join('lead_products','lead_products.id_lead','leads.id')->where('type','seller')->where('id_assigned', $id)->sum('lead_products.lead_value');
            //dd($upsell);
            $total = $total_lead_value + $total_upsel ;
            $total_lead = Lead::where('id_assigned', $id)->where('type','seller');
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                $total_lead = $total_lead->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
            }
            $total_lead = $total_lead->count();
            
            $confirmed = HistoryStatu::join('leads','leads.id','history_status.id_lead')->where('leads.type','seller')->where('history_status.status','confirmed');
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                if($date_from == $date_two){
                    $confirmed = $confirmed->whereDate('history_status.created_at',$date_from);
                }else{
                    $confirmed = $confirmed->whereBetween('history_status.created_at',[$date_from , $date_two]);
                }
            }else{
                $confirmed = $confirmed->whereDate('history_status.created_at', $date->format('Y-m-d'));
            }

            $confirmed = $confirmed->where('history_status.id_user', $id)->select('history_status.id_user','history_status.id_lead')->distinct('history_status.id_lead')->count();

            $canceled = HistoryStatu::join('leads','leads.id','history_status.id_lead')->where('leads.type','seller')->where('history_status.status','like','%'.'canceled'.'%');
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                if($date_from == $date_two){
                    $canceled = $canceled->whereDate('history_status.created_at',$date_from);
                }else{
                    $canceled = $canceled->whereBetween('history_status.created_at',[$date_from , $date_two]);
                }
            }else{
                $canceled = $canceled->whereDate('history_status.created_at', $date->format('Y-m-d'));
            }
            $canceled = $canceled->where('history_status.id_user', $id)->select('history_status.id_user','history_status.id_lead')->distinct('history_status.id_lead')->count();

            $noanswer = HistoryStatu::join('leads','leads.id','history_status.id_lead')->where('leads.type','seller')->where('history_status.status','like','%'.'no answer'.'%');
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                if($date_from == $date_two){
                    $noanswer = $noanswer->whereDate('history_status.created_at',$date_from);
                }else{
                    $noanswer = $noanswer->whereBetween('history_status.created_at',[$date_from , $date_two]);
                }
            }else{
                $noanswer = $noanswer->whereDate('history_status.created_at', $date->format('Y-m-d'));
            }
            $noanswer = $noanswer->where('history_status.id_user', $id)->select('history_status.id_user','history_status.id_lead')->distinct('history_status.id_lead')->count();
            
            $delivred = Lead::where('id_assigned', $id)->where('type','seller')->where('status_livrison','delivred');
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                $delivred = $delivred->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
            }
            $delivred = $delivred->count();

            $calllater = HistoryStatu::join('leads','leads.id','history_status.id_lead')->where('leads.type','seller')->where('history_status.status','like','%'.'call later'.'%');
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                if($date_from == $date_two){
                    $calllater = $calllater->whereDate('history_status.created_at',$date_from);
                }else{
                    $calllater = $calllater->whereBetween('history_status.created_at',[$date_from , $date_two]);
                }
            }else{
                $calllater = $calllater->whereDate('history_status.created_at', $date->format('Y-m-d'));
            }
            $calllater = $calllater->where('history_status.id_user', $id)->select('history_status.id_user','history_status.id_lead')->distinct('history_status.id_lead')->count();

            $process = Lead::where('id_assigned', $id)->where('type','seller')->where('status_confirmation','confirmed')->where('status_livrison','unpacked');
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                $process = $process->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
            }
            $process = $process->count();
            $rate_confirmed = Lead::where('id_assigned', $id)->where('type','seller')->where('status_confirmation','confirmed');
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                $rate_confirmed = $rate_confirmed->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
            }
            if($total_lead == 0){
                $rate_confirmed = 0;
            }else{
                $rate_confirmed = round(((float)$rate_confirmed->count() / $total_lead) * 100) ;
            }
            $rate_canceled = Lead::where('id_assigned', $id)->where('type','seller')->where('status_confirmation','canceled');
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                $rate_canceled = $rate_canceled->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
            }
            if($total_lead == 0){
                $rate_canceled = 0;
            }else{
                $rate_canceled = round(((float)$rate_canceled->count() / $total_lead) * 100) ;
            }
            
            $rate_noanswer = Lead::where('id_assigned', $id)->where('type','seller')->wherein('status_confirmation',['no answer' , 'no answer 2' , 'no answer 3' , 'no answer 4' , 'no answer 5' , 'no answer 6' , 'no answer 7' , 'no answer 8' , 'no answer 9']);
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                $rate_noanswer = $rate_noanswer->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
            }
            if($total_lead == 0){
                $rate_noanswer = 0 ;
            }else{
                $rate_noanswer = round(((float)$rate_noanswer->count() / $total_lead) * 100) ;
            }
            
            $rate_delivred = Lead::where('id_assigned', $id)->where('type','seller')->where('status_livrison','delivred');
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                $rate_delivred = $rate_delivred->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
            }
            if($total_lead == 0){
                $rate_delivred = 0;
            }else{
                $rate_delivred = round(((float)$rate_delivred->count() /  $total_lead) * 100);
            }
            $upsel = Lead::join('lead_products','lead_products.id_lead','=','leads.id')->where('type','seller')->where('isupsell','1')->where('status_confirmation','confirmed')->where('id_assigned', $id)->where('id_country', Auth::user()->country_id);
            if(!empty($request->date)){
                $parts = explode(' - ' , $request->date);
                $date_from = $parts[0];
                $date_two = $parts[1];
                $upsel = $upsel->whereBetween('leads.last_status_change', [$date_from , $date_two]);
            }
            if($confirmed == 0){
                $upsel = 0;
            }else{
                $upsel = round(((float)$upsel->count() /  $confirmed ) * 100);
            }
            $order_canceled = Lead::with('product')->where('type','seller')->where('status_confirmation','canceled')->where('id_assigned', $id)->get();
            $order_no_delivry = Lead::with('product')->where('type','seller')->where('status_confirmation','confirmed')->where('status_livrison','unpacked')->where('id_assigned', $id)->get();

            
        $neworder = Lead::where('status_confirmation','new order')->get();
        $proo = Product::get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        $customer = $request->customer;
        $ref = $request->ref;
        $phone = $request->phone1;
        $city = $request->city;
        $product = $request->id_prod;
        $confirmation = $request->confirmation;
        $leads = Lead::with('product','leadpro','country','cities','livreur')->where('type','seller')->where('id_country', Auth::user()->country_id)->where('id_assigned',$id)->orderBy('id', 'DESC');
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
        }
        if(!empty($request->ids)){//dd($request->ids);
            $leads = $leads->where('id_assigned',$request->ids);
        }
        if(!empty($customer)){
            $leads = $leads->where('name',$customer);
        }
        if(!empty($ref)){
            $leads = $leads->where('n_lead',$ref);
        }
        if(!empty($phone)){
            $leads = $leads->where('phone','like','%'.$phone.'%');
        }
        if(!empty($city)){
            $leads = $leads->where('id_city',$city);
        }
        if(!empty($product)){
            $leads = $leads->where('id_product',$product);
        }
        if(!empty($confirmation)){
            $leads = $leads->whereIn('status_confirmation',$confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereBetween('created_at', [date('Y-m-d' , strtotime($date_from)) , date('Y-m-d' , strtotime($date_two))]);
            }
        }
        $leads= $leads->paginate($items);
       
       
        return view('backend.staffs.performence', compact('leadss','lead','upsel','total_lead','cities','products','productss','calllater','product','rate_confirmed','rate_canceled','noanswer','rate_noanswer','rate_delivred','confirmed','upsell','canceled','delivred','process','order_canceled','order_no_delivry','id','leads','items'));
    }
    public function export(Request $request)
    {
        $id = $request->ids;
        foreach($id as $v_id){
            $data[] = User::whereIn('id',explode(",",$v_id))->get();
        }
    }

    public function exportdownload(Request $request,$allids)
    {
        //dd($allids);
        $allids = json_decode($allids);
        $id = $allids;
        $id = new ExportStaff([$id]);
        return Excel::download($id, 'Staff.xlsx');
    }
}
