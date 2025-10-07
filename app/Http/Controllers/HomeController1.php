<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Models\Zone;
use App\Models\Lead;
use App\Models\User;
use App\Models\Citie;
use App\Models\Stock;
use App\Models\Sheet;
use App\Models\Client;
use App\Models\Product;
use App\Models\Zipcode;
use App\Models\Countrie;
use App\Models\Province;
use App\Models\Warehouse;
use Google\Service\Sheets;
use Illuminate\Support\Str;
use App\Models\LeadProduct;
use Illuminate\Http\Request;
use App\Models\HistoryStatu;
use App\Models\WarehouseCitie;
use App\Models\ShopifyIntegration;
use App\Http\Services\TwilioService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Services\GoogleSheetServices;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->middleware('auth');
        $this->twilioService = $twilioService;
    }



    public function sendUpsellMessage(Request $request)
    {
        $userPhone = "+212701130792"; // User's WhatsApp number
        $message = "do you want your order?";

        $buttons = [
            [
                'type' => 'reply',
                'reply' => [
                    'id' => 'yes', // Unique ID for the button
                    'title' => 'Yes', // Button text
                ],
            ],
            [
                'type' => 'reply',
                'reply' => [
                    'id' => 'no', // Unique ID for the button
                    'title' => 'No', // Button text
                ],
            ],
        ];

        $response = $this->twilioService->sendWhatsAppMessageWithButtons($userPhone, $message, $buttons);

        return response()->json([
            'message' => 'Upsell message sent!',
            'response' => $response,
        ]);
    }

    public function handleUserResponse(Request $request)
    {
        $userResponse = strtolower($request->input('Body')); // User's reply (YES/NO)
        $userPhone = $request->input('From'); // User's WhatsApp number

        if ($userResponse === 'yes') {
            $this->twilioService->sendWhatsAppMessageWithButtons($userPhone, "Great! Here's a link to our premium features: [Link]");
        } elseif ($userResponse === 'no') {
            $this->twilioService->sendWhatsAppMessageWithButtons($userPhone, "No problem! Let us know if you change your mind.");
        } else {
            $this->twilioService->sendWhatsAppMessageWithButtons($userPhone, "Invalid response. Please reply YES or NO.");
        }

        return response()->json(['message' => 'Response handled successfully.']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index(Request $request)
    // {
    //     // $userPhone = "+212718751708"; // User's WhatsApp number
    //     // $message = "Thank you for your purchase! Would you like to explore our premium features?";

    //     // // Define the buttons
    //     // $buttons = [
    //     //     [
    //     //         'type' => 'reply',
    //     //         'reply' => [
    //     //             'id' => 'yes', // Unique ID for the button
    //     //             'title' => 'Yes', // Button text
    //     //         ],
    //     //     ],
    //     //     [
    //     //         'type' => 'reply',
    //     //         'reply' => [
    //     //             'id' => 'no', // Unique ID for the button
    //     //             'title' => 'No', // Button text
    //     //         ],
    //     //     ],
    //     // ];

    //     // $response = $this->twilioService->sendWhatsAppMessageWithButtons($userPhone, $message, $buttons);
    //     // return response()->json([
    //     //     'message' => 'Upsell message sent!',
    //     //     'response' => $response,
    //     // ]);

    //     // $buttonId = "yes"; // User's reply (YES/NO)
    //     // $userPhone = "+212718751708"; // User's WhatsApp number

    //     // if ($buttonId === 'yes') {
    //     //     dd($this->twilioService->sendWhatsAppMessageWithButtons($userPhone, "Great! Here's a link to our premium features: [https://mehdi-call.palace-agency.com/]"));
    //     // } elseif ($buttonId === 'no') {
    //     //     $this->twilioService->sendWhatsAppMessageWithButtons($userPhone, "No problem! Let us know if you change your mind.");
    //     // } else {
    //     //     $this->twilioService->sendWhatsAppMessageWithButtons($userPhone, "Invalid response. Please reply YES or NO.");
    //     // }

    //     // return response()->json(['message' => 'Response handled successfully.']);
    //     $listagent = User::where('country_id', Auth::user()->country_id)->where('id_role',3)->select('id')->get()->toarray();
    //     if(Auth::user()->id_role != "3"){
    //         $listagent = $listagent;
    //     }
    //     $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    //     $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    //     $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    //     $cities = Citie::where('id_country', Auth::user()->country_id)->get();
    //     $lead = Lead::where('type','seller')->with('product','country','cities')->where('status_confirmation','new lead')->orderBy('n_lead', 'DESC')->limit(1)->where('deleted_at',0)->first();
    //     $date = Carbon::now();  
    //     $total_upsel = LeadProduct::join('leads','leads.id','lead_products.id_lead')->where('status_confirmation','confirmed')->where('isupsell',1);
    //     if(Auth::user()->id_role == 3){
    //         $total_upsel = $total_upsel->where('id_assigned', Auth::user()->id);
    //     }
    //     if(!empty($request->date)){
    //         $parts = explode(' - ' , $request->date);
    //         $date_from = date('Y-m-d', strtotime($parts[0]));
    //         $date_two = date('Y-m-d', strtotime($parts[1]));
    //     }else{
    //         $date_from = date('Y-m-d');
    //         $date_two = date('Y-m-d');
    //     }
    //     if($date_from == $date_two){
    //         $total_upsel = $total_upsel->whereDate('last_status_change',$date_from);
    //     }else{
    //         $total_upsel = $total_upsel->whereDate('last_status_change','>=',$date_from)->whereDate('last_status_change', '<=' , $date_two);
    //     }
        
    //     $total_upsel = $total_upsel->sum('lead_products.lead_value');
    //     $confirmed = Lead::where('leads.status_confirmation','confirmed');
    //     if($date_from == $date_two){
    //         $confirmed = $confirmed->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $confirmed = $confirmed->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //     }
    //     if( Auth::user()->id_role == 3){
    //         $confirmed = $confirmed->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $confirmed = $confirmed->select('leads.id_assigned','leads.id')->distinct('leads.id')->count();
        

    //     $noanswer = Lead::where('leads.status_confirmation','like','%'.'no answer'.'%');
    //     if($date_from == $date_two){
    //         $noanswer = $noanswer->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $noanswer = $noanswer->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //     }
        
    //     if( Auth::user()->id_role == 3){
    //         $noanswer = $noanswer->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $noanswer = $noanswer->select('leads.id_assigned','leads.id')->distinct('leads.id')->count();

    //     $calllater = Lead::where('leads.status_confirmation','like','%'.'call later'.'%');
    //     if($date_from == $date_two){
    //         $calllater = $calllater->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $calllater = $calllater->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //     }
    //     if( Auth::user()->id_role == 3){
    //         $calllater = $calllater->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $calllater = $calllater->select('leads.id_assigned','leads.id')->distinct('leads.id')->count();

    //     $duplicated = Lead::where('leads.status_confirmation','like','%'.'duplicated'.'%');
    //     if($date_from == $date_two){
    //         $duplicated = $duplicated->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $duplicated = $duplicated->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //     }
    //     if( Auth::user()->id_role == 3){
    //         $duplicated = $duplicated->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $duplicated = $duplicated->select('leads.id_assigned','leads.id')->distinct('leads.id')->count();



    //     $totlead = Lead::where('status_confirmation','new lead');
    //     if($date_from == $date_two){
    //         $totlead = $totlead->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $totlead = $totlead->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //     }
        
    //     if( Auth::user()->id_role == 3){
    //         $totlead = $totlead->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $totlead = $totlead->count();

    //     $confirmedarray = Lead::where('leads.status_confirmation','confirmed');
    //     if($date_from == $date_two){
    //         $confirmedarray = $confirmedarray->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $confirmedarray = $confirmedarray->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //     }
    //     if( Auth::user()->id_role == 3){
    //         $confirmedarray = $confirmedarray->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $confirmedarray = $confirmedarray->select('leads.id_assigned','leads.id')->distinct('leads.id')->select('leads.id')->get();

    //     $totdelivered = Lead::where('status_confirmation','confirmed')->where('status_livrison','delivered')->wherein('id',$confirmedarray);
        
    //     if( Auth::user()->id_role == 3){
    //         $totdelivered = $totdelivered->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $totdelivered = $totdelivered->count();

    //     $totrejected = Lead::where('status_confirmation','confirmed')->where('status_livrison','rejected')->wherein('id',$confirmedarray);
        
    //     if( Auth::user()->id_role == 3){
    //         $totrejected = $totrejected->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $totrejected = $totrejected->count();

    //     $totreturned = Lead::where('status_confirmation','confirmed')->where('status_livrison','returned')->wherein('id',$confirmedarray);
        
    //     if( Auth::user()->id_role == 3){
    //         $totreturned = $totreturned->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $totreturned = $totreturned->count();

    //     $totproccessing= Lead::where('status_confirmation','confirmed')->where('status_livrison','proseccing')->wherein('id',$confirmedarray);
        
    //     if( Auth::user()->id_role == 3){
    //         $totproccessing = $totproccessing->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $totproccessing = $totproccessing->count();

    //     $totNew = Lead::where('status_confirmation','confirmed')->wherein('id',$confirmedarray);
        
    //     if( Auth::user()->id_role == 3){
    //         $totNew = $totNew->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $totNew = $totNew->count();

    //     $totUnpacked = Lead::where('status_confirmation','confirmed')->wherein('status_livrison',['unpacked','item packed','picking proccess'])->wherein('id',$confirmedarray);
        
    //     if( Auth::user()->id_role == 3){
    //         $totUnpacked = $totUnpacked->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $totUnpacked = $totUnpacked->count();

    //     $cancelled = Lead::where('leads.status_confirmation','canceled');
    //     if($date_from == $date_two){
    //         $cancelled = $cancelled->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $cancelled = $cancelled->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //     }
    //     if( Auth::user()->id_role == 3){
    //         $cancelled = $cancelled->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $cancelled = $cancelled->select('leads.id_assigned','leads.id')->distinct('leads.id')->count();

    //     $cancelledbysystem = Lead::where('leads.status_confirmation','canceled by system');
    //     if($date_from == $date_two){
    //         $cancelledbysystem = $cancelledbysystem->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $cancelledbysystem = $cancelledbysystem->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //     }
    //     if( Auth::user()->id_role == 3){
    //         $cancelledbysystem = $cancelledbysystem->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $cancelledbysystem = $cancelledbysystem->select('leads.id_assigned','leads.id')->distinct('leads.id')->count();

    //     $wrong = Lead::where('leads.status_confirmation','wrong')->where('id_country',Auth::user()->country_id);
    //     if($date_from == $date_two){
    //         $wrong = $wrong->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $wrong = $wrong->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //     }
    //     if( Auth::user()->id_role == 3){
    //         $wrong = $wrong->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $wrong = $wrong->select('leads.id_assigned','leads.id')->distinct('leads.id')->count();

    //     $neworder = Lead::where('status_confirmation','new order')->where('id_country',Auth::user()->country_id)->count();

    //     $total = Lead::where('id_country',Auth::user()->country_id);
    //     if($date_from == $date_two){
    //         $total = $total->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $total = $total->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //     }
    //     if( Auth::user()->id_role == 3){
    //         $total = $total->where('leads.id_assigned', Auth::user()->id);
    //     }
    //     $total = $total->select('leads.id_assigned','leads.id')->distinct('leads.id')->count();
    //     $order_canceled = Lead::where('type','seller')->with('product')->where('status_confirmation','confirmed')->where('status_livrison','rejected');
    //     if(Auth::user()->id_role == 3){
    //         $order_canceled = $order_canceled->where('id_assigned', Auth::user()->id);
    //     }
    //     $order_canceled = $order_canceled->where('deleted_at',0)->orderby('id','desc')->take(5)->get();
    //     $order_no_delivry = Lead::where('type','seller')->with('product')->where('status_confirmation','confirmed')->where('status_livrison','incident');
    //     if(Auth::user()->id_role == 3){
    //         $order_no_delivry = $order_no_delivry->where('id_assigned', Auth::user()->id);
    //     }
    //     $order_no_delivry = $order_no_delivry->where('deleted_at',0)->take(10)->get();
    //     //dd($confirmed);
    //     $top_agent = Lead::where('type','seller')->join('users','users.id','=','leads.id_assigned')->where('status_confirmation','confirmed')->where('country_id', Auth::user()->country_id);
    //     if($date_from == $date_two){
    //         $top_agent = $top_agent->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $top_agent = $top_agent->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
    //     }
    //     $top_agent = $top_agent->select(\DB::raw('count(leads.id) as total , users.id as id , users.name as n '))->orderby('total','desc')->groupBy('n','id')->limit('5')->get();
    //     $bad_agent = Lead::where('type','seller')->join('users','users.id','=','leads.id_assigned')->where('status_confirmation','confirmed')->where('country_id', Auth::user()->country_id);
    //     if($date_from == $date_two){
    //         $bad_agent = $bad_agent->whereDate('leads.created_at',$date_from);
    //     }else{
    //         $bad_agent = $bad_agent->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
    //     }
        
    //     $bad_agent = $bad_agent->select(\DB::raw('count(leads.id) as total , users.id as id , users.name as n '))->orderby('total','asc')->groupBy('n','id')->limit('5')->get();
    //     //dd($top_agent);
    //     // $date_from = $date_from->format('Y-m-d');
    //     // $date_two = $date_two->format('Y-m-d');
    //     $time = Lead::where('type','seller')->where('id_country', Auth::user()->country_id)
    //             ->where('status_confirmation','confirmed')
    //             ->whereDate('last_status_change', Carbon::now()->format('Y-m-d'));
    //     if(Auth::user()->id_role == 3){
    //         $time = $time->where('id_assigned',Auth::user()->id);
    //     }
    //     $time = $time->select( DB::raw('COUNT(id) as count ,  HOUR(last_status_change) as hour'));
    //     $confirmedtotal = $time->count();
    //     $time = $time->groupBy('hour')->get();

    //     $canceled = Lead::where('type','seller')->where('id_country', Auth::user()->country_id)
    //                 ->where('status_confirmation','canceled')
    //                 ->whereDate('last_status_change', Carbon::now()->format('Y-m-d'))
    //                 ->select( DB::raw('COUNT(id) as count ,  HOUR(last_status_change) as hour'));
    //     if(Auth::user()->id_role == 3){
    //         $canceled = $canceled->where('id_assigned',Auth::user()->id);
    //     }
    //     $canceledtotal = $canceled->count();
    //     $canceled = $canceled->groupBy('hour')->get();
        
    //     // delivered chart
    //     $getconfirmed = Lead::where('leads.status_confirmation','confirmed');
    //     if(!empty($request->date)){
    //         if($date_from == $date_two){
    //             $getconfirmed = $getconfirmed->whereDate('leads.created_at',$date_from);
    //         }else{
    //             $getconfirmed = $getconfirmed->whereDate('leads.created_at','>=',$date_from)->whereDate('leads.created_at', '<=' , $date_two);
    //         }
    //     }else{
    //         $getconfirmed = $getconfirmed->whereDate('leads.created_at', $date->format('Y-m-d'));
    //     }
    //     if( Auth::user()->id_role == 3){
    //         $getconfirmed = $getconfirmed->where('leads.id_assigned', Auth::user()->id);
    //     }else{
    //         $getconfirmed = $getconfirmed->whereIn('leads.id_assigned',$listagent);
    //     }
    //     $getconfirmed = $getconfirmed->select('leads.id')->get()->toarray();
        
    //     $delivered = Lead::wherein('id',$getconfirmed)->where('status_confirmation','confirmed')->where('status_livrison','delivered')->count();
    //     $returned = Lead::wherein('id',$getconfirmed)->where('status_confirmation','confirmed')->where('status_livrison','returned')->count();
    //     return view('backend.dashboard', compact('date_from','date_two','total','lead','cities','products','productss','calllater','product','confirmed','cancelled','cancelledbysystem','noanswer','totdelivered','totproccessing','totrejected','totreturned','totUnpacked','totlead','top_agent','bad_agent','order_canceled','order_no_delivry','total_upsel','time','confirmedtotal','canceled','canceledtotal','delivered','returned','totNew'));
    // }
    public function index(Request $request)
    {
        $listagent = User::where('country_id', Auth::user()->country_id)
                        ->where('id_role', 3)
                        ->select('id')
                        ->get()
                        ->toarray();
        
        if(Auth::user()->id_role != "3"){
            $listagent = $listagent;
        }

        // Date handling
        $date = Carbon::now();
        if(!empty($request->date)) {
            $parts = explode(' - ', $request->date);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            $date_to = date('Y-m-d', strtotime($parts[1]));
        } else {
            $date_from = date('Y-m-d');
            $date_to = date('Y-m-d');
        }

        // Get statistics data
        [$total, $confirmed, $noanswer, $calllater, $duplicated, $totlead, $cancelled, $cancelledbysystem, $wrong, $neworder] = $this->getConfirmationStats($request, $date_from, $date_to, $listagent);
        
        // Get delivery stats
        [$totdelivered, $totrejected, $totreturned, $totproccessing, $totUnpacked, $totNew] = $this->getDeliveryStats($date_from, $date_to, $listagent);
        
        // Get agent performance data
        [$top_agent, $bad_agent] = $this->getAgentPerformance($date_from, $date_to);
        
        // Get hourly stats
        [$time, $confirmedtotal, $canceled, $canceledtotal] = $this->getHourlyStats();
        
        $hourlyConfirmedData = [];
        foreach ($time as $item) {
            $hourlyConfirmedData[$item->hour] = $item->count;
        }

        $hourlyCanceledData = [];
        foreach ($canceled as $item) {
            $hourlyCanceledData[$item->hour] = $item->count;
        }

        $completeHourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $completeHourlyData['confirmed'][$i] = $hourlyConfirmedData[$i] ?? 0;
            $completeHourlyData['canceled'][$i] = $hourlyCanceledData[$i] ?? 0;
        }
        
        $total_upsel = $this->getUpsellTotal($date_from, $date_to, $listagent);
        
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        $lead = Lead::where('type','seller')
                  ->with('product','country','cities')
                  ->where('status_confirmation','new lead')
                  ->orderBy('n_lead', 'DESC')
                  ->limit(1)
                  ->where('deleted_at',0)
                  ->first();
        
        $order_canceled = Lead::where('type','seller')
                            ->with('product')
                            ->where('status_confirmation','confirmed')
                            ->where('status_livrison','rejected');
        
        if(Auth::user()->id_role == 3){
            $order_canceled = $order_canceled->where('id_assigned', Auth::user()->id);
        }
        
        $order_canceled = $order_canceled->where('deleted_at',0)
                                       ->orderby('id','desc')
                                       ->take(5)
                                       ->get();
        
        $order_no_delivry = Lead::where('type','seller')
                              ->with('product')
                              ->where('status_confirmation','confirmed')
                              ->where('status_livrison','incident');
        
        if(Auth::user()->id_role == 3){
            $order_no_delivry = $order_no_delivry->where('id_assigned', Auth::user()->id);
        }
        
        $order_no_delivry = $order_no_delivry->where('deleted_at',0)
                                           ->take(10)
                                           ->get();

        $delivered = $this->getDeliveredStats($request, $date_from, $date_to, $listagent);
        $returned = $this->getReturnedStats($request, $date_from, $date_to, $listagent);

        return view('backend.dashboard', compact(
            'date_from', 'date_to', 'total', 'lead', 'cities', 'products', 'productss', 
            'calllater', 'product', 'confirmed', 'cancelled', 'cancelledbysystem', 
            'noanswer', 'totdelivered', 'totproccessing', 'totrejected', 'totreturned', 
            'totUnpacked', 'totlead', 'top_agent', 'bad_agent', 'order_canceled', 
            'order_no_delivry', 'total_upsel', 'time', 'confirmedtotal', 'canceled', 
            'canceledtotal', 'delivered', 'returned', 'totNew','completeHourlyData'
        ));
    }
    
    protected function getConfirmationStats($request, $date_from, $date_to, $listagent)
    {
        $baseQuery = function($query) use ($date_from, $date_to, $listagent) {
            if($date_from == $date_to) {
                $query->whereDate('leads.created_at', $date_from);
            } else {
                $query->whereDate('leads.created_at', '>=', $date_from)
                     ->whereDate('leads.created_at', '<=', $date_to);
            }
            
            if(Auth::user()->id_role == 3) {
                $query->where('leads.id_assigned', Auth::user()->id);
            }
            
            return $query->select('leads.id_assigned','leads.id')->distinct('leads.id');
        };
        
        $confirmed = Lead::where('leads.status_confirmation','confirmed');
        $confirmed = $baseQuery($confirmed)->count();
        
        $noanswer = Lead::where('leads.status_confirmation','like','%no answer%');
        $noanswer = $baseQuery($noanswer)->count();
        
        $calllater = Lead::where('leads.status_confirmation','like','%call later%');
        $calllater = $baseQuery($calllater)->count();
        
        $duplicated = Lead::where('leads.status_confirmation','like','%duplicated%');
        $duplicated = $baseQuery($duplicated)->count();
        
        $totlead = Lead::where('status_confirmation','new lead');
        $totlead = $baseQuery($totlead)->count();
        
        $cancelled = Lead::where('leads.status_confirmation','canceled');
        $cancelled = $baseQuery($cancelled)->count();
        
        $cancelledbysystem = Lead::where('leads.status_confirmation','canceled by system');
        $cancelledbysystem = $baseQuery($cancelledbysystem)->count();
        
        $wrong = Lead::where('leads.status_confirmation','wrong')
                    ->where('id_country', Auth::user()->country_id);
        $wrong = $baseQuery($wrong)->count();
        
        $neworder = Lead::where('status_confirmation','new order')
                       ->where('id_country', Auth::user()->country_id)
                       ->count();
        
        $total = Lead::where('id_country', Auth::user()->country_id);
        $total = $baseQuery($total)->count();
        
        return [$total, $confirmed, $noanswer, $calllater, $duplicated, $totlead, $cancelled, $cancelledbysystem, $wrong, $neworder];
    }
    
    protected function getDeliveryStats($date_from, $date_to, $listagent)
    {
        $confirmedarray = Lead::where('leads.status_confirmation','confirmed');
        
        if($date_from == $date_to) {
            $confirmedarray = $confirmedarray->whereDate('leads.created_at', $date_from);
        } else {
            $confirmedarray = $confirmedarray->whereDate('leads.created_at', '>=', $date_from)
                                          ->whereDate('leads.created_at', '<=', $date_to);
        }
        
        if(Auth::user()->id_role == 3) {
            $confirmedarray = $confirmedarray->where('leads.id_assigned', Auth::user()->id);
        }
        
        $confirmedarray = $confirmedarray->select('leads.id')->get();
        
        $totdelivered = Lead::where('status_confirmation','confirmed')
                           ->where('status_livrison','delivered')
                           ->whereIn('id', $confirmedarray);
        
        if(Auth::user()->id_role == 3) {
            $totdelivered = $totdelivered->where('leads.id_assigned', Auth::user()->id);
        }
        $totdelivered = $totdelivered->count();
        
        $totrejected = Lead::where('status_confirmation','confirmed')
                          ->where('status_livrison','rejected')
                          ->whereIn('id', $confirmedarray);
        
        if(Auth::user()->id_role == 3) {
            $totrejected = $totrejected->where('leads.id_assigned', Auth::user()->id);
        }
        $totrejected = $totrejected->count();
        
        $totreturned = Lead::where('status_confirmation','confirmed')
                          ->where('status_livrison','returned')
                          ->whereIn('id', $confirmedarray);
        
        if(Auth::user()->id_role == 3) {
            $totreturned = $totreturned->where('leads.id_assigned', Auth::user()->id);
        }
        $totreturned = $totreturned->count();
        
        $totproccessing = Lead::where('status_confirmation','confirmed')
                             ->where('status_livrison','proseccing')
                             ->whereIn('id', $confirmedarray);
        
        if(Auth::user()->id_role == 3) {
            $totproccessing = $totproccessing->where('leads.id_assigned', Auth::user()->id);
        }
        $totproccessing = $totproccessing->count();
        
        $totNew = Lead::where('status_confirmation','confirmed')
                     ->whereIn('id', $confirmedarray);
        
        if(Auth::user()->id_role == 3) {
            $totNew = $totNew->where('leads.id_assigned', Auth::user()->id);
        }
        $totNew = $totNew->count();
        
        $totUnpacked = Lead::where('status_confirmation','confirmed')
                          ->whereIn('status_livrison',['unpacked','item packed','picking proccess'])
                          ->whereIn('id', $confirmedarray);
        
        if(Auth::user()->id_role == 3) {
            $totUnpacked = $totUnpacked->where('leads.id_assigned', Auth::user()->id);
        }
        $totUnpacked = $totUnpacked->count();
        
        return [$totdelivered, $totrejected, $totreturned, $totproccessing, $totUnpacked, $totNew];
    }
    
    protected function getAgentPerformance($date_from, $date_to)
    {
        $top_agent = Lead::where('type','seller')
                        ->join('users','users.id','=','leads.id_assigned')
                        ->where('status_confirmation','confirmed')
                        ->where('country_id', Auth::user()->country_id);
        
        if($date_from == $date_to) {
            $top_agent = $top_agent->whereDate('leads.created_at', $date_from);
        } else {
            $top_agent = $top_agent->whereDate('leads.created_at', '>=', $date_from)
                                 ->whereDate('leads.created_at', '<=', $date_to);
        }
        
        $top_agent = $top_agent->select(DB::raw('count(leads.id) as total, 
                                               users.id as id, 
                                               users.name as n,
                                               SUM(CASE WHEN leads.status_livrison = "delivered" THEN 1 ELSE 0 END) as delivered_count'))
                              ->orderby('total','desc')
                              ->groupBy('n','id')
                              ->limit(5)
                              ->get();
        
        $bad_agent = Lead::where('type','seller')
                        ->join('users','users.id','=','leads.id_assigned')
                        ->where('status_confirmation','confirmed')
                        ->where('country_id', Auth::user()->country_id);
        
        if($date_from == $date_to) {
            $bad_agent = $bad_agent->whereDate('leads.created_at', $date_from);
        } else {
            $bad_agent = $bad_agent->whereDate('leads.created_at', '>=', $date_from)
                                 ->whereDate('leads.created_at', '<=', $date_to);
        }
        
        $bad_agent = $bad_agent->select(DB::raw('count(leads.id) as total, 
                                        users.id as id, 
                                        users.name as n,
                                        SUM(CASE WHEN leads.status_livrison = "delivered" THEN 1 ELSE 0 END) as delivered_count'))
                              ->orderby('total','asc')
                              ->groupBy('n','id')
                              ->limit(5)
                              ->get();
        
        return [$top_agent, $bad_agent];
    }
    
    protected function getHourlyStats()
    {
        $time = Lead::where('type','seller')
                   ->where('id_country', Auth::user()->country_id)
                   ->where('status_confirmation','confirmed')
                   ->whereDate('last_status_change', Carbon::now()->format('Y-m-d'));
        
        if(Auth::user()->id_role == 3) {
            $time = $time->where('id_assigned', Auth::user()->id);
        }
        
        $time = $time->select(DB::raw('COUNT(id) as count, HOUR(last_status_change) as hour'));
        $confirmedtotal = $time->count();
        $time = $time->groupBy('hour')->get();
        
        $canceled = Lead::where('type','seller')
                       ->where('id_country', Auth::user()->country_id)
                       ->where('status_confirmation','canceled')
                       ->whereDate('last_status_change', Carbon::now()->format('Y-m-d'))
                       ->select(DB::raw('COUNT(id) as count, HOUR(last_status_change) as hour'));
        
        if(Auth::user()->id_role == 3) {
            $canceled = $canceled->where('id_assigned', Auth::user()->id);
        }
        
        $canceledtotal = $canceled->count();
        $canceled = $canceled->groupBy('hour')->get();
        
        return [$time, $confirmedtotal, $canceled, $canceledtotal];
    }
    
    protected function getUpsellTotal($date_from, $date_to, $listagent)
    {
        $total_upsel = LeadProduct::join('leads','leads.id','lead_products.id_lead')
                                 ->where('status_confirmation','confirmed')
                                 ->where('isupsell',1);
        
        if(Auth::user()->id_role == 3) {
            $total_upsel = $total_upsel->where('id_assigned', Auth::user()->id);
        }
        
        if($date_from == $date_to) {
            $total_upsel = $total_upsel->whereDate('last_status_change', $date_from);
        } else {
            $total_upsel = $total_upsel->whereDate('last_status_change', '>=', $date_from)
                                      ->whereDate('last_status_change', '<=', $date_to);
        }
        
        return $total_upsel->sum('lead_products.lead_value');
    }
    
    protected function getDeliveredStats($request, $date_from, $date_to, $listagent)
    {
        $getconfirmed = Lead::where('leads.status_confirmation','confirmed');
        
        if(!empty($request->date)) {
            if($date_from == $date_to) {
                $getconfirmed = $getconfirmed->whereDate('leads.created_at', $date_from);
            } else {
                $getconfirmed = $getconfirmed->whereDate('leads.created_at', '>=', $date_from)
                                           ->whereDate('leads.created_at', '<=', $date_to);
            }
        } else {
            $getconfirmed = $getconfirmed->whereDate('leads.created_at', Carbon::now()->format('Y-m-d'));
        }
        
        if(Auth::user()->id_role == 3) {
            $getconfirmed = $getconfirmed->where('leads.id_assigned', Auth::user()->id);
        } else {
            $getconfirmed = $getconfirmed->whereIn('leads.id_assigned', $listagent);
        }
        
        $getconfirmed = $getconfirmed->select('leads.id')->get()->toarray();
        
        return Lead::whereIn('id', $getconfirmed)
                 ->where('status_confirmation','confirmed')
                 ->where('status_livrison','delivered')
                 ->count();
    }
    
    protected function getReturnedStats($request, $date_from, $date_to, $listagent)
    {
        $getconfirmed = Lead::where('leads.status_confirmation','confirmed');
        
        if(!empty($request->date)) {
            if($date_from == $date_to) {
                $getconfirmed = $getconfirmed->whereDate('leads.created_at', $date_from);
            } else {
                $getconfirmed = $getconfirmed->whereDate('leads.created_at', '>=', $date_from)
                                           ->whereDate('leads.created_at', '<=', $date_to);
            }
        } else {
            $getconfirmed = $getconfirmed->whereDate('leads.created_at', Carbon::now()->format('Y-m-d'));
        }
        
        if(Auth::user()->id_role == 3) {
            $getconfirmed = $getconfirmed->where('leads.id_assigned', Auth::user()->id);
        } else {
            $getconfirmed = $getconfirmed->whereIn('leads.id_assigned', $listagent);
        }
        
        $getconfirmed = $getconfirmed->select('leads.id')->get()->toarray();
        
        return Lead::whereIn('id', $getconfirmed)
                 ->where('status_confirmation','confirmed')
                 ->where('status_livrison','returned')
                 ->count();
    }
    
    public function ajaxDashboard(Request $request)
    {
        if ($request->has('date_from') && $request->has('date_to')) {
            $date_from = $request->input('date_from');
            $date_to = $request->input('date_to');
        } elseif (!empty($request->date)) {
            $parts = explode(' - ', $request->date);
            $date_from = date('Y-m-d', strtotime($parts[0]));
            $date_to = date('Y-m-d', strtotime($parts[1]));
        } else {
            $date_from = date('Y-m-d');
            $date_to = date('Y-m-d');
        }
        
        if (strtotime($date_from) > strtotime($date_to)) {
            [$date_from, $date_to] = [$date_to, $date_from];
        }

        $listagent = User::where('country_id', Auth::user()->country_id)
                        ->where('id_role', 3)
                        ->select('id')
                        ->get()
                        ->toarray();
        
        if(Auth::user()->id_role != "3"){
            $listagent = $listagent;
        }

        [$total, $confirmed, $noanswer, $calllater, $duplicated, $totlead, $cancelled, $cancelledbysystem, $wrong, $neworder] = $this->getConfirmationStats($request, $date_from, $date_to, $listagent);
        [$totdelivered, $totrejected, $totreturned, $totproccessing, $totUnpacked, $totNew] = $this->getDeliveryStats($date_from, $date_to, $listagent);
        [$top_agent, $bad_agent] = $this->getAgentPerformance($date_from, $date_to);
        [$time, $confirmedtotal, $canceled, $canceledtotal] = $this->getHourlyStats();
        $total_upsel = $this->getUpsellTotal($date_from, $date_to, $listagent);
        $delivered = $this->getDeliveredStats($request, $date_from, $date_to, $listagent);
        $returned = $this->getReturnedStats($request, $date_from, $date_to, $listagent);

        $hourlyConfirmedData = [];
        foreach ($time as $item) {
            $hourlyConfirmedData[$item->hour] = $item->count;
        }

        $hourlyCanceledData = [];
        foreach ($canceled as $item) {
            $hourlyCanceledData[$item->hour] = $item->count;
        }

        $completeHourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $completeHourlyData['confirmed'][$i] = $hourlyConfirmedData[$i] ?? 0;
            $completeHourlyData['canceled'][$i] = $hourlyCanceledData[$i] ?? 0;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'confirmed' => $confirmed,
                'noanswer' => $noanswer,
                'calllater' => $calllater,
                'totlead' => $totlead,
                'cancelled' => $cancelled,
                'top_agents' => $top_agent,
                'bad_agents' => $bad_agent,
                'cancelledbysystem' => $cancelledbysystem,
                'totdelivered' => $totdelivered,
                'totproccessing' => $totproccessing,
                'totrejected' => $totrejected,
                'totreturned' => $totreturned,
                'totUnpacked' => $totUnpacked,
                'totNew' => $totNew,
                'hourlyData' => $completeHourlyData,
                'confirmedtotal' => $confirmedtotal,
                'canceledtotal' => $canceledtotal,
                'delivered' => $delivered,
                'returned' => $returned,
                'total_upsel' => $total_upsel,
                'date_from' => $date_from,
                'date_to' => $date_to
            ]
        ]);
    }
    
    public function zones(Request $request,$id)
    {
        $empData['data'] = Zone::where('id_city',$id)->select('id','zones.name')->get();
        
        return response()->json($empData);
    }
    
    public function cities(Request $request)
    {
        
        $warehouses = WarehouseCitie::where('city_id',$request->id)
                            ->select('warehouse_id')
                            ->get();
        $idleads = LeadProduct::where('id_lead',$request->lead)->select('id_product')->get();
        foreach($idleads as $v_product){
            $checkstock = Stock::where('id_product',$v_product)->wherein('id_warehouse',$warehouses)->where('qunatity','>=',0)->get();
            if(!$checkstock->isempty()){
                $empData['data'] = Warehouse::wherein('id',$warehouses)->select('id','name')->get();
            }else{
                $checkstock = Stock::where('id_product',$request->product)->where('qunatity','>=',0)->select('id_warehouse')->get('id_warehouse');
                $empData['data'] = Warehouse::wherein('id',$checkstock)->select('id','name')->get();
                
            }
        }
        // $checkstock = Stock::wherein('id_product',$idlead)->wherein('id_warehouse',$warehouses)->where('qunatity','>=',0)->get();
        // if(!$checkstock->isempty()){
        //     $empData['data'] = Warehouse::wherein('id',$warehouses)->select('id','name')->get();
        // }else{
        //     $checkstock = Stock::where('id_product',$request->product)->where('qunatity','>=',0)->select('id_warehouse')->get();
        //     $empData['data'] = Warehouse::wherein('id',$checkstock)->select('id','name')->get();
        // }
        
        return response()->json($empData);
    }

    public function countries($id)
    {
        $data = [
            'country_id' => $id
        ];
        $countries = User::where('id', Auth::user()->id)->update($data);
        return redirect()->route('home');
    }
}
