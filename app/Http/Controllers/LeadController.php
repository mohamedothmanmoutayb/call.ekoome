<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Sheet;
use App\Models\Citie;
use App\Models\Stock;
use App\Models\Upsel;
use App\Models\Import;
use App\Models\Zipcode;
use App\Models\Product;
use App\Models\ShippingCompany;
use App\Models\Province;
use App\Models\Countrie;
use App\Exports\AllLead;
use App\Models\BlackList;
use App\Models\Warehouse;
use Google\Service\Sheets;
use App\Models\LeadProduct;
use App\Models\CountrieFee;
use App\Exports\LeadExport;
use App\Models\HistoryStatu;
use Illuminate\Http\Request;
use App\Models\AssignedProduct;
use App\Models\MappingStock;
use App\Models\Tracking;
use App\Models\HistoryStock;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\CsvImportRequest;
use Maatwebsite\Excel\HeadingRowImport;
use App\Http\Services\GoogleSheetServices;
use Carbon\Carbon;
use Auth;
use DateTime;
USE DB;

class LeadController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $provinces = Province::with('cities')->where('id_country', Auth::user()->country_id)->get();

        $productsassigned = AssignedProduct::where('id_agent',Auth::user()->id)->select('id_product')->get()->toarray();
        
        if(Auth::user()->id_role != "3"){
            $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur','assigned')->where('method_payment','!=','PREPAID')->where('id_country', Auth::user()->country_id)->orderBy('id', 'DESC');
        }else{
            $leads = Lead::where('type','seller')->with('product','leadpro','country','cities')->where('method_payment','!=','PREPAID')->where('id_country', Auth::user()->country_id)->where('status_livrison','unpacked')->where('id_assigned',null);
        }
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
        }
        if(!empty($request->customer)){
            $leads = $leads->where('name',$request->customer);
        }
        if(!empty($request->ref)){
            $leads = $leads->where('n_lead',$request->ref);
        }
        if(!empty($request->phone1)){
            $leads = $leads->where('phone','like','%'.$request->phone1.'%');
        }
        if(!empty($request->city)){
            $leads = $leads->where('id_city',$request->city);
        }
        if(!empty($request->id_prod)){
            $leads = $leads->where('id_product',$request->id_prod);
        }
        if(!empty($request->confirmation)){
            $leads = $leads->where('status_confirmation',$request->confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ', $request->date);
            $date_from = $parts[0];
            $date_to = $parts[1];
            
            $dateField = $request->date_type == 'confirmed' ? 'date_confirmed' : 'created_at';
            
            if($date_to == $date_from){
                $leads = $leads->whereDate($dateField, '=', date('Y-m-d', strtotime($date_from)));
            } else {
                $leads = $leads->whereDate($dateField, '>=', date('Y-m-d', strtotime($date_from)))
                              ->whereDate($dateField, '<=', date('Y-m-d', strtotime($date_to)));
            }
        }
        if(Auth::user()->id_role != "3"){
            
            $leads= $leads->where('deleted_at',0)->paginate($items);
        }else{
            $leads= $leads->where('deleted_at',0)->orderby('id','asc')->limit(1)->get();//dd($leads);
            
        }
        $date = date('Y-m-d');
        $minutesToAdd = 2;
        $datemod = date('Y-m-d H:i', strtotime('+60 minutes', strtotime($date)));
        //check order call later
        $calll = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('method_payment','!=','PREPAID')->where('id_country', Auth::user()->country_id)->where('id_assigned','=', Null)->where('date_call', '<', date('Y-m-d H:i'))->where('status_confirmation','call later')->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->orderby('id','asc')->where('deleted_at',0)->first();
        $noanswer = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('method_payment','!=','PREPAID')->where('id_country', Auth::user()->country_id)->where('id_assigned',Null)->where('date_call', '<', date('Y-m-d H:i'))->where('status_confirmation','like','%'.'no answer'.'%')->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->orderby('id','asc')->where('deleted_at',0)->first();
        $new = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('method_payment','!=','PREPAID')->where('status','=',0)->where('id_country', Auth::user()->country_id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
        //is existe order call later
        $checkauth = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('method_payment','!=','PREPAID')->where('id_country', Auth::user()->country_id)->where('id_assigned','=', Auth::user()->id)->wherein('status_confirmation',['call later','new order','no answer','no answer 2','no answer 3','no answer 4','no answer 5','no answer 6','no answer 7','no answer 8','no answer 9'])->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->orderby('id','asc')->where('deleted_at',0)->first();
        if($new){
            $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('status','=','1')->where('id_country', Auth::user()->country_id)->where('id_assigned', Auth::user()->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
            if($lead){
                //check if assigned this order to this agent
                if($lead->id_assigned == Auth::user()->id){
                    $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('status','=','1')->where('id',$lead->id)->where('id_country', Auth::user()->country_id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->limit(1)->first();
                    Lead::where('method_payment','!=','PREPAID')->where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id]);
                    //check order if nexiste product
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    $listupsel = LeadProduct::with('product')->where('id_lead',$lead->id)->where('isupsell',1)->get();
                    $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
                }
                $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                if(empty($lead->id)){
                    $detailpro = Product::first();
                }
            }else{
                //if not existion new order assigned
                $lea = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('id_country', Auth::user()->country_id)->where('id_assigned','=', Null)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                if(!empty($lea->id)){
                    $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('id',$lea->id)->where('id_country', Auth::user()->country_id)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                    Lead::where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id]);
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                }else{
                    $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('id_country', Auth::user()->country_id)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                    if($lead){
                        Lead::where('method_payment','!=','PREPAID')->where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id]);
                        $detailpro = Product::where('id',$lead->id_product)->first();
                        $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    }else{
                        $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('id_country', Auth::user()->country_id)->where('status','=','0')->where('status_confirmation',['no answer','call later','new order'])->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                        if(!empty($lead->id)){
                            Lead::where('method_payment','!=','PREPAID')->where('type','seller')->where('id',$lead->id)->update(['id_assigned'=> Auth::user()->id]);
                            $detailpro = Product::where('id',$lead->id_product)->first();
                            $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                            $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                        }
                    }
                }
                if(empty($detailupsell)){
                    $detailupsell = Upsel::where('id_product',0)->get();
                }
                $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
                $allproductlead = LeadProduct::with('product')->get();
            }
        }elseif($checkauth){
            if(!empty($checkauth->id_assigned)){
                $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->where('id',$checkauth->id)->where('deleted_at',0)->first();
                $asssigned = array();
                if(!empty($lead->id_product)){
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    Lead::where('type','seller')->where('method_payment','!=','PREPAID')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
                }
            }
            $detailupsell = Upsel::where('id_product',0)->get();
            $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
            $allproductlead = LeadProduct::with('product')->get();
        }elseif(!empty($calll)){
            if(!empty($calll->id_assigned)){
                $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('status_confirmation','call later')->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->where('id',$calll->id)->where('deleted_at',0)->first();
                $asssigned = array();
                $detailpro = Product::where('id',$lead->id_product)->first();
                $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                Lead::where('type','seller')->where('method_payment','!=','PREPAID')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
            }else{
                if(!empty($calll->id)){
                    $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('status_confirmation','call later')->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->where('id',$calll->id)->where('deleted_at',0)->first();                 
                    $asssigned = array();
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    Lead::where('type','seller')->where('method_payment','!=','PREPAID')->where('id',$lead->id)->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
                }
            }
            $detailupsell = Upsel::where('id_product',0)->get();
            $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
            $allproductlead = LeadProduct::with('product')->get();
        }elseif($noanswer){
                if(!empty($noanswer->id_assigned)){
                    if($noanswer->id_assigned == Auth::user()->id){
                        $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('status_livrison','unpacked')->where('date_call', '<', date('Y-m-d H:i'))->where('id_country', Auth::user()->country_id)->where('id',$noanswer->id)->where('deleted_at',0)->orderby('id','asc')->first();
                        $asssigned = array();
                        $detailpro = Product::where('id',$lead->id_product)->first();
                        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                        Lead::where('method_payment','!=','PREPAID')->where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
                    }
                    
                }else{
                    $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('date_call', '<', date('Y-m-d H:i'))->where('id_country', Auth::user()->country_id)->where('id',$noanswer->id)->where('deleted_at',0)->orderby('id','asc')->first();                
                    $asssigned = array();
                    if($lead){
                        $detailpro = Product::where('id',$lead->id_product)->first();
                        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                        Lead::where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
                    }
                }
                // if(empty($lead->id)){
                    $detailupsell = Upsel::where('id_product',0)->get();
                    $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
                    $allproductlead = LeadProduct::with('product')->get();
                // }
        }elseif(!empty($calll)){          
            if(!empty($calll->id_assigned)){
                $lead = Lead::where('type','seller')->where('method_payment','!=','PREPAID')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->where('id',$calll->id)->where('deleted_at',0)->first();
                $asssigned = array();
                $detailpro = Product::where('id',$lead->id_product)->first();
                $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                Lead::where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
            }else{
                if(!empty($calll->id)){
                    $lead = Lead::where('type','seller')->where('method_payment','!=','PREPAID')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('id_country', Auth::user()->country_id)->where('id',$calll->id)->where('deleted_at',0)->first();
                    $asssigned = array();
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    Lead::where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
                }
            }
            $detailupsell = Upsel::where('id_product',0)->get();
            $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
            $allproductlead = LeadProduct::with('product')->get();
        }elseif($new){
            $lead = Lead::where('type','seller')->where('method_payment','!=','PREPAID')->with('product','leadpro','country','cities')->wherein('id_product',$productsassigned)->where('status','=','1')->where('id_country', Auth::user()->country_id)->where('id_assigned', Auth::user()->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
            if($lead){
                //check if assigned this order to this agent
                if($lead->id_assigned == Auth::user()->id){
                    $lead = Lead::where('type','seller')->with('product','leadpro','country','cities')->wherein('id_product',$productsassigned)->where('status','=','1')->where('id',$lead->id)->where('id_country', Auth::user()->country_id)->where('status_livrison','unpacked')->where('deleted_at',0)->limit(1)->first();
                    Lead::where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id]);
                    //check order if nexiste product
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    $listupsel = LeadProduct::with('product')->where('id_lead',$lead->id)->where('isupsell',1)->get();
                    $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
                }
                $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                if(empty($lead->id)){
                    $detailpro = Product::first();
                }
            }else{
                //if not existion new order assigned
                $lea = Lead::where('type','seller')->where('method_payment','!=','PREPAID')->with('product','leadpro','country','cities')->wherein('id_product',$productsassigned)->where('id_country', Auth::user()->country_id)->where('id_assigned','=', Null)->where('status','=','0')->where('status_confirmation','new order')->where('id_country', Auth::user()->country_id)->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                if(!empty($lea->id)){                 
                    $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities')->wherein('id_product',$productsassigned)->where('id',$lea->id)->where('id_country', Auth::user()->country_id)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                    Lead::where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id]);
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                }else{
                    $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities')->wherein('id_product',$productsassigned)->where('id_country', Auth::user()->country_id)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                    if($lead){
                        Lead::where('method_payment','!=','PREPAID')->wherein('id_product',$productsassigned)->where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id]);
                        $detailpro = Product::where('id',$lead->id_product)->first();
                        $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    }else{
                        $lead = Lead::where('method_payment','!=','PREPAID')->wherein('id_product',$productsassigned)->where('type','seller')->with('product','leadpro','country','cities')->where('id_country', Auth::user()->country_id)->where('status','=','0')->where('status_confirmation',['no answer','call later','new order'])->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                        if(!empty($lead->id)){
                            Lead::where('method_payment','!=','PREPAID')->where('type','seller')->where('id',$lead->id)->update(['id_assigned'=> Auth::user()->id]);
                            $detailpro = Product::where('id',$lead->id_product)->first();
                            $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                            $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                        }
                    }
                }
                if(empty($detailupsell)){
                    $detailupsell = Upsel::where('id_product',0)->get();
                }
                $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
                $allproductlead = LeadProduct::with('product')->get();
            }
        }else{
            //if not existe order call later check new order
            $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('status','=','1')->where('id_country', Auth::user()->country_id)->where('id_assigned', Auth::user()->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
            if($lead){
                //check if assigned this order to this agent
                if($lead->id_assigned == Auth::user()->id){
                    $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('status','=','1')->where('id',$lead->id)->where('status_livrison','unpacked')->where('deleted_at',0)->limit(1)->first();
                    Lead::where('method_payment','!=','PREPAID')->where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id]);
                    //check order if nexiste product
                    if($lead->id_product == Null){
                        $data = (new GoogleSheetServices ())->getClient();
                        $client = $data;
                        if($lead->id_product){
                            $v_sheet = Sheet::where('id',$lead->id_sheet)->first();//dd($info);
                            $userss = User::where('id',$lead->id_user)->first();
                            $service = new Sheets($client);
                            $spreadsheetId = $v_sheet->sheetid;
                            $spreadsheetName = $v_sheet->sheetname.'!A2:L';
                            //dd($spreadsheetName);
                            $range = $spreadsheetName;
                            $doc = $service->spreadsheets_values->get($spreadsheetId, $range);
                            $response = $doc;
                            $lastsheet = Lead::where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->orderby('id','desc')->first();
                            $countries = Countrie::where('id',$v_sheet->id_warehouse)->first();
                                    
                            $data = array();
                            if(!empty($response['values'][$index][0])){
                                $data['id_order'] = $response['values'][$index][0];
                            }
                            $data['id_product'] = $v_sheet->id_product;
                            if(!empty($response['values'][$index][2])){
                                $data['name'] = $response['values'][$index][2];
                            }
                            if(!empty($response['values'][$index][4])){
                                $data['phone'] = $countries->negative. $response['values'][$index][4];
                            }
                            if(!empty($response['values'][$index][7])){
                                $data['address'] = $response['values'][$index][7];
                            }
                            if(!empty($response['values'][$index][9])){
                                $data['lead_value'] = $response['values'][$index][9];
                            }
                            if(!empty($response['values'][$index][3])){
                                $data['quantity'] = $response['values'][$index][3];
                            }else{
                                $data['quantity'] = "1";
                            }
                            if(!empty($data['First name']) || !empty($data['phone']) || !empty($data['name']) || !empty($response['values'][$index][4])){
                                Lead::where('type','seller')->where('id',$id)->update($data);
                                $data2 = array();
                                $data2['id_product'] = $v_sheet->id_product;
                                if(!empty($response['values'][$index][3])){
                                    $data2['quantity'] = $response['values'][$index][3];
                                }else{
                                    $data2['quantity'] = "1";
                                }
                                if(!empty($response['values'][$index][9])){
                                    $data2['lead_value'] = $response['values'][$index][9];
                                }
                                LeadProduct::where('id_lead',$id)->update($data2);
                            }
                        }
                    }
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    $listupsel = LeadProduct::with('product')->where('id_lead',$lead->id)->where('isupsell',1)->get();
                    $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
                }
                $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                if(empty($lead->id)){
                    $detailpro = Product::first();
                }
            }else{
                //if not existion new order assigned
                $lea = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('id_country', Auth::user()->country_id)->where('id_assigned','=', 0)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                if(!empty($lea->id)){                 
                    $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->where('id',$lea->id)->where('id_country', Auth::user()->country_id)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                    Lead::where('method_payment','!=','PREPAID')->where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id]);
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                }else{
                    $lead = Lead::where('method_payment','!=','PREPAID')->where('type','seller')->with('product','leadpro','country','cities','stock')->wherein('id_product',$productsassigned)->where('id_country', Auth::user()->country_id)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                    if($lead){
                        Lead::where('method_payment','!=','PREPAID')->wherein('id_product',$productsassigned)->where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id]);
                        $detailpro = Product::where('id',$lead->id_product)->first();
                        $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    }else{
                        $lead = Lead::where('method_payment','!=','PREPAID')->wherein('id_product',$productsassigned)->where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('status','=','0')->where('status_confirmation',['no answer','call later','new order'])->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                        if(!empty($lead->id)){
                            Lead::where('method_payment','!=','PREPAID')->wherein('id_product',$productsassigned)->where('type','seller')->where('id',$lead->id)->update(['id_assigned'=> Auth::user()->id , 'status' => '1']);
                            $detailpro = Product::where('id',$lead->id_product)->first();
                            $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                            $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                        }
                    }
                }
                if(empty($detailupsell)){
                    $detailupsell = Upsel::where('id_product',0)->get();
                }
                $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
                $allproductlead = LeadProduct::with('product')->get();
            }
        }
        if(Auth::user()->id_role != "3"){
            return view('backend.leads.index', compact('proo','products','productss','provinces','leads','lead','detailupsell','items'));
        } else{
            if(!empty($lead)){
                $seller = User::where('id',$lead->id_user)->first();
                $cities = Citie::where('id_country',Auth::user()->country_id)->get();
                $leadproduct = LeadProduct::with('product')->where('isupsell',1)->where('id_lead',$lead->id)->get();
                $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
                $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                $imports = Import::where('id_product',$lead->id_product)->select('warehouse_id')->get();
                $warehouses = Warehouse::where('country_id',Auth::user()->country_id)->wherein('id',$imports)->get();
                Lead::where('id',$lead->id)->update(['status' => 1 , 'id_assigned' => Auth::user()->id]);
            }else{
                $lead = 0;
                $seller = 0;
                $cities = Citie::where('id_country',Auth::user()->country_id)->get();
                $leadproduct = LeadProduct::with('product')->where('id_lead',1)->get();
                $allproductlead = LeadProduct::with('product')->get();
                $productseller = Product::where('id_country', Auth::user()->country_id)->get();
                $detailpro = Product::where('id_country', Auth::user()->country_id)->get();
                $warehouses = Warehouse::where('country_id',Auth::user()->country_id)->get();
            }
            $warehouses = Warehouse::where('country_id',Auth::user()->country_id)->get();

            
            return view('backend.leads.leadagent', compact('proo','products','productss','productseller','provinces','leads','lead','detailpro','detailupsell','listupsel','allproductlead','leadproduct','items','seller','warehouses','cities'));
        }
    }

    public function search(Request $request)
    {
        $leads = Lead::where('type','seller')->with('country','cities','assigned')->where('deleted_at',0);
        if($request->search){
            $leads = $leads->where('n_lead',$request->search)->orwhere('id_order',$request->search)->orwhere('name','like','%'.$request->search.'%')->orwhere('phone','like','%'.$request->search.'%')->orwhere('phone2','like','%'.$request->search.'%')->orwhere('lead_value','like','%'.$request->search.'%');
        }
        
        $leads = $leads->where('deleted_at',0)->orderBy('n_lead', 'DESC')->get();
        $counter = 1;
        $output = "";
        foreach($leads as $key => $v_lead)
        {
            if($v_lead['id_country'] == Auth::user()->country_id){
                $output.=
                '<tr>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="ids" class="custom-control-input checkBoxClass"  value="'.$v_lead['id'].'" id="pid-">
                        <label class="custom-control-label" for="pid-"></label>
                    </div>
                </td>
                <td data-id="'. $v_lead['id'] .'" id="detaillead" class="detaillead" data-toggle="tooltip">'. $v_lead['n_lead'] .'</td>
                <td>';
                    foreach($v_lead['product'] as $v_product){
                    $output.=''. $v_product['name'] .'';
                }
                    $output.='</td>
                <td data-id="'. $v_lead['id'] .'" id="detaillead" class="detaillead" data-toggle="tooltip">'. $v_lead['name'] .'</td>
                <td>';
                    if(!empty($v_lead['id_city'])){
                        foreach($v_lead['cities'] as $v_city){
                        $output .=' <a> '. $v_city['name'] .'</a>';
                        }
                    }else{
                        $output .=' <a> '. $v_lead['city'] .'</a>';
                    }
                    
                $output.='</td>';
                $output.='
                <td><a href="tel:'. $v_lead['phone'] .'">'. $v_lead['phone'] .'</a></td>
                <td>'. $v_lead['lead_value'] .'</td>';                
                $set_selected = '';
                $set_selected2 = '';
                $set_selected3 = '';
                $set_selected4 = '';
                $set_selected5 = '';
                $selected = ['new order'];
                if($v_lead['status_confirmation'] == 'new order'){
                    $set_selected = 'selected';
                }elseif($v_lead['status_confirmation'] == 'confirmed'){
                    $set_selected2 = 'selected';
                }elseif($v_lead['status_confirmation'] == 'no answer'){
                    $set_selected3 = 'selected';
                }elseif($v_lead['status_confirmation'] =='call later' ) {
                    $set_selected4 = 'selected';
                }elseif($v_lead['status_confirmation'] =='canceled' ) {
                    $set_selected5 = 'selected';
                }
                $output.='
                <td>'.$v_lead['status_confirmation'].'</td>
                <td>'.$v_lead['assigned'].'</td>
                <td>';
                    foreach($v_lead['livreur'] as $v_livreur){
                        $output .='<a>'. $v_livreur->name .'</a><br><a>'. $v_livreur->telephone .'</a>';
                    }
                $output.='</td>
                <td>'. \Carbon\carbon::parse($v_lead['created_at'])->diff(\Carbon\carbon::now())->days .'</td>
                <td>
                    <a class="dropdown-item " href="'.  route('leads.refresh', $v_lead['id']) .'"><i class="ti-edit"></i> Refresh Data</a>
                </td>
                </tr>
                ';
            }
        }

        return response($output);
    }

    public function leadsearch(Request $request)
    {
        $leads = Lead::where('type','seller')->with('country','cities','livreur','assigned','historystatu')->where('deleted_at',0);
        if($request->n_lead){
            $leads = $leads->where('n_lead','like','%'.$request->n_lead.'%')->orwhere('name','like','%'.$request->n_lead.'%')->orwhere('phone','like','%'.$request->n_lead.'%')->orwhere('phone2','like','%'.$request->n_lead.'%')->orwhere('lead_value','like','%'.$request->n_lead.'%')->where('deleted_at',0);
        }
        
        $leads = $leads->where('deleted_at',0)->orderBy('n_lead', 'DESC')->get();
        $counter = 1;
        $output = "";
        foreach($leads as $key => $v_lead)
        {
            if($v_lead['id_country'] == Auth::user()->country_id){
            if($v_lead['deleted_at'] == 0){
                $output.=
                '<tr>
               
                <td data-id="'. $v_lead['id'] .'" id="detaillead" class="detaillead" data-toggle="tooltip">'. $v_lead['n_lead'] .'</td>
                <td>';
                    foreach($v_lead['product'] as $v_product){
                    $output.=''. $v_product['name'] .'';
                }
                    $output.='</td>
                <td data-id="'. $v_lead['id'] .'" id="detaillead" class="detaillead" data-toggle="tooltip">'. $v_lead['name'] .'</td>
                <td>';
                    if(!empty($v_lead['id_city'])){
                        foreach($v_lead['cities'] as $v_city){
                        $output .=' <a> '. $v_city['name'] .'</a>';
                        }
                    }else{
                        $output .=' <a> '. $v_lead['city'] .'</a>';
                    }
                    
                $output.='</td>';
                $output.='
                <td><a href="tel:'. $v_lead['phone'] .'">'. $v_lead['phone'] .'</a></td>
                <td>'. $v_lead['lead_value'] .'</td>';
                    
                $set_selected = '';
                $set_selected2 = '';
                $set_selected3 = '';
                $set_selected4 = '';
                $set_selected5 = '';
                $selected = ['new order'];
                if($v_lead['status_confirmation'] == 'new order'){
                    $set_selected = 'selected';
                }elseif($v_lead['status_confirmation'] == 'confirmed'){
                    $set_selected2 = 'selected';
                }elseif($v_lead['status_confirmation'] == 'no answer'){
                    $set_selected3 = 'selected';
                }elseif($v_lead['status_confirmation'] =='call later' ) {
                    $set_selected4 = 'selected';
                }elseif($v_lead['status_confirmation'] =='canceled' ) {
                    $set_selected5 = 'selected';
                }
                if(!empty($v_lead['assigned'])){
                    $assigned = $v_lead['assigned']['name'];
                }else{
                    $assigned = Null;
                }
                $output.='
                <td>'.$v_lead['status_confirmation'].'</td>
                <td>'. $assigned.'</td>
                <td>'. $v_lead['created_at'] .'</td>
                <td>
                    <a class="btn btn-info btn-rounded m-b-10 " data-toggle="tooltip" href="'. route('leads.edit', $v_lead['id']) .'" target="_blank" title="Edit"><i class="mdi mdi-eye"></i></a>
                </td>
                </tr>
                ';
                }
            }
            
        }

        return response($output);
    }

    public function packages()
    {
        $leads = Lead::where('type','seller')->with('leadproducts','leadbyvendor')->where('status_confirmation','confirmed')->where('status_livrison','new order')->where('deleted_at',0)->get()->groupby('id_product');//dd($leads);
        //dd($leads);
        return view('backend.leads.packages', compact('leads'));
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
    // public function store(Request $request)
    // {
    //     $usr = Product::where('id',$request->id)->first();
    //     $date = new DateTime();
    //     $ne = substr(strtoupper(Auth::user()->name), 0 , 1);
    //     $n = substr(strtoupper(Auth::user()->name), -1);
    //     $city = 'CD';
    //     $ct = substr(strtoupper($city), 0 , 1);
    //     $c = substr(strtoupper($city), -1);
    //     $year = substr(strtoupper(date('Y')), 2);
    //     $month = date('m');
    //     $day = date('d');
    //     $last = Lead::orderby('id','DESC')->first();
    //     $fees = CountrieFee::where('id_user',$usr->id_user)->where('id_country', Auth::user()->country_id)->first();
    //     $citie = Citie::where('id',$request->cityid)->first();
    //     $lastmille = ShippingCompany::where('name',$citie->last_mille)->first();
    //     if(empty($last->id)){
    //         $kk = 1;
    //     }else{
    //         $kk = $last->id + 1;
    //     }
    //     $n_lead = $ne.'-'.$n.'-'.str_pad($kk, 5 , '0', STR_PAD_LEFT);
        
    //     $data = [
    //         'market' => 'Manual',
    //         'n_lead' => $n_lead,
    //         'id_user' => $usr->id_user,
    //         'name' => $request->namecustomer,
    //         'phone' => $request->mobile,
    //         'phone2' => $request->mobile2,
    //         'quantity' => $request->quantity,
    //         'lead_value' => $request->total,
    //         'id_last_mille' => $lastmille->id,
    //         'warehouse_id' => $request->warehouse,
    //         'id_product' => $request->id,
    //         'id_country' => Auth::user()->country_id,
    //         'id_city' => $request->cityid,
    //         'address' => $request->address,
    //         'status_confirmation' => 'confirmed',
    //         'date_picking' => new DateTime(),
    //         'id_assigned' => Auth::user()->id,
    //         'status' => 1,
    //         'last_contact' => new DateTime(),
    //         'date_confirmed' => new DateTime(),
    //         'last_status_change' => new DateTime(),
    //         'created_at' => new DateTime(),
    //     ];
    //     Lead::insert($data);
    //     $last_product = Lead::where('n_lead',$n_lead)->first();
    //     $data2 = [
    //         'id_lead' => $last_product->id,
    //         'id_product' => $last_product->id_product,
    //         'quantity' => $last_product->quantity,
    //         'lead_value' => $last_product->lead_value,
    //         'date_delivred' => new DateTime(),
    //     ];
    //     LeadProduct::insert($data2);
    //     $data3 = [
    //         'country_id' => Auth::user()->country_id,
    //         'id_lead' => $last_product->id,
    //         'id_user' => Auth::user()->id,
    //         'status' => "confirmed",
    //         'comment' => "confirmed",
    //         'date' => new DateTime(),
    //     ];
    //     HistoryStatu::insert($data3);
    //     $data4 = [
    //         'country_id' => Auth::user()->country_id,
    //         'id_lead' => $last_product->id,
    //         'id_user' => Auth::user()->id,
    //         'status' => "unpacked",
    //         'comment' => "unpacked",
    //         'date' => new DateTime(),
    //     ];
    //     HistoryStatu::insert($data4);
        
    //     return response()->json(['success'=>true]);
    // }

    public function store(Request $request)
{
    $products = $request->input('products', []);

    if (empty($products)) {
        return response()->json(['success' => false, 'message' => 'No products provided'], 400);
    }

    $firstProduct = collect($products)->first();

    if (!$firstProduct || !isset($firstProduct['product_id'])) {
        return response()->json(['success' => false, 'message' => 'Invalid product data'], 400);
    }

    $firstProductId = $firstProduct['product_id'];
    $firstProduct = Product::find($firstProductId);

    if (!$firstProduct) {
        return response()->json(['success' => false, 'message' => 'Invalid product'], 400);
    }

    $date = new \DateTime();
    $ne = substr(strtoupper(Auth::user()->name), 0, 1);
    $n  = substr(strtoupper(Auth::user()->name), -1);
    $last = Lead::orderBy('id', 'DESC')->first();
    $kk = $last ? $last->id + 1 : 1;
    $n_lead = $ne . $n . '-' . str_pad($kk, 5, '0', STR_PAD_LEFT);

    $lead = Lead::create([
        'n_lead'        => $n_lead,
        'id_user'       => $firstProduct->id_user,
        'id_country'    => Auth::user()->country_id,
        'name'   => $request->namecustomer,  
        'phone'  => $request->mobile,
        'phone2' => $request->mobile2,
        'id_city'=> $request->cityid, 
        'lead_value'    => $request->total, 
        'quantity'    => $request->total_quantity ,
        'market'        => 'Manual',
        'method_payment'=> 'COD',
        'status_confirmation' => 'confirmed',
        'date_picking' => new DateTime(),
        'status' => 1,
        'last_contact' => new DateTime(),
        'date_confirmed' => new DateTime(),
        'last_status_change' => new DateTime(),
        'id_product'    => $firstProduct->id, 
        'id_assigned'   => Auth::user()->id,
        'id_zone'       => $request->id_zone,
        'address'       => $request->address,
        'created_at'    => $date,
    ]);

    foreach ($products as $p) {
        $product = Product::find($p['product_id']);
        if (!$product) continue;

        LeadProduct::create([
            'id_lead'    => $lead->id,
            'id_product' => $product->id,
            'quantity'   => $p['quantity'] ?? 1,
            'lead_value'      => ($p['quantity'] ?? 1) * ($p['price'] ?? 0), 
        ]);
    }


       $last_product = Lead::where('n_lead',$n_lead)->first();

       $data3 = [
            'country_id' => Auth::user()->country_id,
            'id_lead' => $last_product->id,
            'id_user' => Auth::user()->id,
            'status' => "confirmed",
            'comment' => "confirmed",
            'date' => new DateTime(),
        ];
        HistoryStatu::insert($data3);
        $data4 = [
            'country_id' => Auth::user()->country_id,
            'id_lead' => $lead->id,
            'id_user' => Auth::user()->id,
            'status' => "unpacked",
            'comment' => "unpacked",
            'date' => new DateTime(),
        ];
         HistoryStatu::insert($data4);

    return response()->json(['success' => true, 'lead_id' => $lead->id]);
}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function edit(Lead $lead, Request $request, $id)
    {
        // $neworder = Lead::where('type','seller')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->get();
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $provinces = Province::where('id_country', Auth::user()->country_id)->select('name','id')->get();
        $cities = Citie::where('id_country',Auth::user()->country_id)->get();
        $customer = $request->customer;
        $ref = $request->ref;
        $phone = $request->phone1;
        $city = $request->city;
        $product = $request->id_prod;
        $confirmation = $request->confirmation;
        if(Auth::user()->id_role != "3"){
            $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur')->where('id_country', Auth::user()->country_id)->orderBy('id', 'DESC');
        }else{
            $leads = Lead::where('type','seller')->with('product','leadpro','country','cities')->where('id_assigned',null);
        }
        
        if(!empty($customer)){
            $leads = $leads->where('name',$customer);
        }
        if(!empty($ref)){
            $leads = $leads->where('n_lead',$ref);
        }
        if(!empty($phone)){
            $leads = $leads->where('phone',$phone);
        }
        if(!empty($phone)){
            $leads = $leads->where('phone2',$phone);
        }
        if(!empty($city)){
            $leads = $leads->where('id_city',$city);
        }
        if(!empty($product)){
            $leads = $leads->where('id_product',$product);
        }
        if(!empty($confirmation)){
            $leads = $leads->where('status_confirmation',$confirmation);
        }
        if(Auth::user()->id_role != "3"){
            $leads= $leads->paginate(15);
        }else{
            $leads= $leads->orderby('id','asc')->limit(1)->get();
            //dd($leads[0]['id']);
        }
        $date = date('Y-m-d H:i');
        //dd($date);
        $minutesToAdd = 2;
        $datemod = date('Y-m-d H:i', strtotime('+60 minutes', strtotime($date)));
        //dd($datemod);
        $lead = Lead::where('type','seller')->with('product','leadpro','country','cities')->where('id', $id)->limit(1)->first();
        //dd($lead);
        $lead = Lead::where('type','seller')->with('product','leadpro','country','cities')->where('id',$lead->id)->orderby('id','asc')->limit(1)->first();
        $detailpro = Product::where('id',$lead->id_product)->first();
        $listupsel = LeadProduct::with('product')->where('id_lead',$lead->id)->where('isupsell',1)->get();
                        
        $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
        $ledproo = LeadProduct::where('isupsell',0)->where('id_lead',$lead->id)->first();
        if(!$ledproo){
            $detailpro = Product::findOrfail($lead->id_product);
        }else{
            $detailpro = Product::findOrfail($lead->id_product);
        }
        
        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
        $leadproduct = LeadProduct::with('product')->where('isupsell',1)->where('id_lead',$lead->id)->get();
        $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
        $seller = User::where('id',$lead->id_user)->first();

        $imports = Import::where('id_product',$lead->id_product)->select('warehouse_id')->get();
        $warehouses = Warehouse::where('country_id',Auth::user()->country_id)->wherein('id',$imports)->get();

        return view('backend.leads.leadagent', compact('seller','proo','products','productss','provinces','leads','lead','detailpro','detailupsell','listupsel','leadproduct','allproductlead','productseller','warehouses','cities'));
       
    }

    public function updateCustomer(Request $request)
    {
        $lead = Lead::where('id', $request->id)->where('type', 'seller')->first();
        $citie = Citie::where('id',$request->id_city)->first();
        if($citie){
            $lastmille = ShippingCompany::where('name',$citie->last_mille)->first();
        }else{
            $lastmille = null;
        }
        
        if(!$lead){
            return response()->json(['success' => false]);
        }

        $data = array();
        
        $data['name'] = $request->name;
        if(!empty($lastmille)){
            $data['id_last_mille'] = $lastmille->id;
        }else{
            $data['id_last_mille'] = Null;
        }
        $data['phone'] = $request->phone;
        $data['address'] = $request->address;
        $data['id_city'] = $request->id_city;
        $data['warehouse_id'] = $request->warehouse_id;
        $data['note'] = $request->note;
        
        Lead::where('id',$request->id)->update($data);

        return response()->json(['success' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */

    public function confirmed(Request $request)
    {
        $checklead = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->first();
        if($checklead){
            $last = Lead::where('type','seller')->where('tracking','!=',null)->where('status_confirmation','confirmed')->orderBy('updated_at', 'desc')->first();
            
            $lead = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->first();
            $feess = CountrieFee::where('id_user', $lead->id_user)->where('id_country',$lead->id_country)->first();
            $citie = Citie::where('id',$request->customercity)->first();
            $lastmille = ShippingCompany::where('name',$citie->last_mille)->first();
            $data = array();
            // $data['name'] = $request->customename;
            // $data['phone'] = $request->customerphone;
            // $data['phone2'] = $request->customerphone2;
            // $data['province'] = $request->province;
            // $data['id_city'] = $request->customercity;
            // $data['id_zone'] = $request->customerzone;
            // $data['address'] = $request->customeraddress;
            // $data['zipcod'] = $request->zipcod;
            // $data['email'] = $request->email;
            if(!empty($lastmille->id)){
                $data['id_last_mille'] = $lastmille->id;
            }
            // $data['warehouse_id'] = $request->warehouse;
            //$data['lead_value'] = $request->leadvalue;
            if(empty($last->id_product)){
                $data['id_product'] = $request->product;
            }
            if($request->leadvquantity != 0){
                $data['quantity'] = $request->leadvquantity;
            }else{
                $data['quantity'] = 1;
            }
            $data['status_confirmation'] = "confirmed";
            // $data['note'] = $request->commentdeliv;
            $data['id_assigned'] = Auth::user()->id;
            $data['last_contact'] = new DateTime();
            $data['date_confirmed'] = new DateTime();
            if(!empty($request->datedelivred)){
                $data['date_picking'] = $request->datedelivred;
            }else{
                $data['date_picking'] = new DateTime();
            }
            
            $data['last_status_change'] = new DateTime();
            $data['status'] = '1';
            if(!$feess){
                $confirmationfees = 0;
            }else{
                $confirmationfees = $feess->fees_confirmation;
            }
            $data['fees_confirmation'] = $confirmationfees;
            if($lead->ispaidapp == "1"){
                $data['method_payment'] = "PREPAID";
             }else{
                $data['method_payment'] = "COD";
             }
            Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->update($data);
            $data2 = [
                'id_lead' => $request->id,
                'id_user' => Auth::user()->id,
                'country_id' => Auth::user()->country_id,
                'status' => "confirmed",
                'comment' => $request->commentdeliv,
                'date' => new DateTime(),
            ];
            HistoryStatu::insert($data2);
            $checkdetail = LeadProduct::where('id_lead',$request->id)->count();
            if($checkdetail == 0){
                $leadpro = array();
                $leadpro['lead_value'] = $request->leadvalue;
                $leadpro['quantity'] = $request->leadvquantity;
                $leadpro['outofstock'] = '0';
                if(!empty($request->datedelivred)){
                    $leadpro['date_delivred'] = $request->datedelivred;
                }else{
                    $leadpro['date_delivred'] = new DateTime();
                }
                
                LeadProduct::insert($leadpro);
            }else{
                $leadpro = array();
                $leadpro['outofstock'] = '0';
                LeadProduct::where('id_lead',$request->id)->where('isupsell',0)->update($leadpro);
                if(!empty($request->datedelivred)){
                    $data3 = [
                        'date_delivred' => $request->datedelivred,
                        'outofstock' => '0',
                    ]; 
                }else{
                    $data3 = [
                        'date_delivred' => new DateTime(),
                        'outofstock' => '0',
                    ]; 
                }//dd($data3);
                LeadProduct::where('id_lead',$request->id)->update($data3);
            }
            $sumquantity = LeadProduct::where('id_lead',$request->id)->sum('quantity');
            Lead::where('id',$request->id)->update(['quantity' => $sumquantity]);
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>false]);
        }
        
    }

    public function canceled(Request $request)
    {
        $checklead = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->where('deleted_at',0)->first();
        if($checklead){
            $data = [
                'status_confirmation' => "canceled",
                'status_livrison' => "unpacked",
                'last_contact' => new DateTime(),
                'note' => $request->commentecanceled,
                'last_status_change' => new DateTime(),
                'status' => '1',
                'id_assigned' => Auth::user()->id,
                'date_delivred' => Null,
            ];

            Lead::where('type','seller')->where('id',$request->id)->update($data);
            $datta = array();
            $datta['date_delivred'] = Null;
            $datta['livrison'] = "unpacked";
            $datta['outstock'] = "0";
            LeadProduct::where('id_lead',$request->id)->update($datta);
            $data2 = [
                'id_lead' => $request->id,
                'id_user' => Auth::user()->id,
                'country_id' => Auth::user()->country_id,
                'status' => "canceled",
                'comment' => $request->commentecanceled,
                'date' => new DateTime(),
            ];
            HistoryStatu::insert($data2);
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>false]);
        }
        
    }

    public function wrong(Request $request)
    {
        $checklead = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->first();
        if($checklead){
            $data = [
                'name' => $request->customename,
                'phone' => $request->customerphone,
                'phone2' => $request->customerphone2,
                'status_confirmation' => "wrong",
                'status_livrison' => "unpacked",
                'last_contact' => new DateTime(),
                'note' => $request->commentewrong,
                'last_status_change' => new DateTime(),
                'status' => '0',
                'id_assigned' => Auth::user()->id,
                'date_delivred' => Null,
            ];

            Lead::where('type','seller')->where('id',$request->id)->update($data);
            $data2 = [
                'id_lead' => $request->id,
                'id_user' => Auth::user()->id,
                'country_id' => Auth::user()->country_id,
                'status' => "wrong",
                'comment' => $request->commentewrong,
                'date' => new DateTime(),
            ];
            HistoryStatu::insert($data2);
            $datta = array();
            $datta['date_delivred'] = Null;
            $datta['livrison'] = "unpacked";
            $datta['outstock'] = "0";
            LeadProduct::where('id_lead',$request->id)->update($datta);
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>false]);
        }
        
    }

    public function duplicated(Request $request,$id)
    {
        $checklead = Lead::where('type','seller')->where('id',$id)->where('status_livrison','unpacked')->first();
        if($checklead){
            $data = [
                'status_confirmation' => "duplicated",
                'status_livrison' => "unpacked",
                'last_status_change' => new DateTime(),
                'status' => '0',
                'id_assigned' => Auth::user()->id,
                'last_contact' => new DateTime(),
                'date_delivred' => Null,
            ];

            Lead::where('type','seller')->where('id',$id)->update($data);
            $data2 = [
                'id_lead' => $id,
                'id_user' => Auth::user()->id,
                'country_id' => Auth::user()->country_id,
                'status' => "duplicated",
                'comment' => "duplicated",
                'date' => new DateTime(),
            ];
            HistoryStatu::insert($data2);
            $datta = array();
            $datta['date_delivred'] = Null;
            $datta['livrison'] = "unpacked";
            $datta['outstock'] = "0";
            LeadProduct::where('id_lead',$id)->update($datta);
            return back();
        }
    }

    public function horzone($id)
    {
        $checklead = Lead::where('type','seller')->where('id',$id)->where('status_livrison','unpacked')->first();
        if($checklead){
            $data = [
                'status_confirmation' => "out of area",
                'status_livrison' => "unpacked",
                'last_status_change' => new DateTime(),
                'status' => '0',
                'id_assigned' => Auth::user()->id,
                'last_contact' => new DateTime(),
                'date_delivred' => Null,
            ];

            Lead::where('type','seller')->where('id',$id)->update($data);
            $data2 = [
                'id_lead' => $id,
                'id_user' => Auth::user()->id,
                'country_id' => Auth::user()->country_id,
                'status' => "out of area",
                'comment' => 'out of area',
                'date' => new DateTime(),
            ];
            HistoryStatu::insert($data2);
            $datta = array();
            $datta['date_delivred'] = Null;
            $datta['livrison'] = "unpacked";
            $datta['outstock'] = "0";
            LeadProduct::where('id_lead',$id)->update($datta);
            return back();
        }
    }

    public function outofstocks(Request $request)
    {
        $checklead = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->first();
        if($checklead){
            $data = [
                'status_confirmation' => "outofstock",
                'status_livrison' => "unpacked",
                'last_contact' => new DateTime(),
                'note' => "out of stock",
                'last_status_change' => new DateTime(),
                'status' => '0',
                'id_assigned' => Auth::user()->id,
                'date_delivred' => Null,
            ];

            Lead::where('type','seller')->where('id',$request->id)->update($data);
            $data2 = [
                'id_lead' => $request->id,
                'id_user' => Auth::user()->id,
                'country_id' => Auth::user()->country_id,
                'status' => "outofstock",
                'comment' => "out of stock",
                'date' => new DateTime(),
            ];
            HistoryStatu::insert($data2);
            $datta = array();
            $datta['date_delivred'] = Null;
            $datta['livrison'] = "unpacked";
            $datta['outstock'] = "0";
            LeadProduct::where('id_lead',$request->id)->update($datta);
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>false]);
        }
        
    }

    public function details($id)
    {
        $leadss = LeadProduct::with('product','leads')->where('id_lead',$id)->get();
        $products = LeadProduct::where('id_lead',$id)->get();
        //dd($leadss);
        $leads = json_decode($leadss);
        return response()->json($leads);
    }

    public function listupsell($id)
    {
        $product = LeadProduct::with('product')->where('id_lead',$id)->where('isupsell',1)->get();
        //dd($product);
        $output ="";
        foreach($product as $v_product){
            $name = $v_product->product;
            $na = $name[0]['name'];
            $output .="
            <tr>
                <td> $na </td>
                <td> $v_product->quantity </td>
                <td> $v_product->lead_value </td>
            </tr>
            ";
        }
        //dd($output);
        return response($output);
    }

    public function seacrhdetails($id)
    {
        $leadss = Lead::where('type','seller')->where('id','like','%'.$id.'%')->orwhere('n_lead','like','%'.$id.'%')->first();
        //$products = LeadProduct::where('id_lead',$leadss->id)->first();
        //dd($leadss->name);
        $leads = json_decode($leadss);
        return response()->json($leads);
    }

    public function detailspro(Request $request)
    {
        $leadss = Lead::where('type','seller')->where('id',$request->id)->where('deleted_at',0)->first();
        $empData['data'] = Stock::join('products','products.id','=','stocks.id_product')->where('products.id_user',$leadss->id_user)->select('products.id','products.name','products.price')->get();//dd($empData);
        return response()->json($empData);
    }

    public function update(Request $request, Lead $lead)
    {
        $data = [
            'name' => $request->namecustomer,
            'quantity' => $request->quantity,
            'phone' => $request->mobile,
            'phone2' => $request->mobile2,
            'id_city' => $request->cityid,
            'id_zone' => $request->zoneid,
            'address' => $request->address,
            'lead_value' => $request->total,
            'note' => $request->note,
            'status' => '0',
            'last_contact' => new DateTime(),
        ];

        Lead::where('type','seller')->where('id',$request->id)->update($data);
        return response()->json(['success'=>true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        //
    }

    public function statuscon(Request $request)
    {
        $checklead = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->where('deleted_at',0)->first();
        if($checklead){
            $data = array();
            $data['status_confirmation'] = $request->status;
            $data['last_status_change'] = new DateTime();
            $data['last_contact'] = new DateTime();
            $data['status'] = '0';
            if($request->status == 'confirmed'){
                $data['date_shipped'] = new DateTime();
                $data2 = array();
                $data2['date_delivred'] = new DateTime();
                $data2['outofstock'] = '0';
                LeadProduct::where('id_lead',$request->id)->update($data2);
            }else{
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
            }

            Lead::where('type','seller')->where('id',$request->id)->update($data);
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>false]);
        }
    }

    public function call(Request $request)
    {//dd($request->all());
        
          
        $date_time = $request->date.' '.$request->time;
        $checklead = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->first();
        if($checklead){
            $new = new DateTime();
            $data = [
                'name' => $request->customename,
                'phone' => $request->customerphone,
                'phone2' => $request->customerphone2,
                'id_city' => $request->customercity,
                'id_zone' => $request->customerzone,
                'address' => $request->customeraddress,
                'address' => $request->customeraddress,
                'status_confirmation' => "call later",
                'status_livrison' => "unpacked",
                'last_contact' => new DateTime(),
                'date_call' => $date_time,
                'note' => $request->comment,
                'last_status_change' => new DateTime(),
                'status' => '0',
                'id_assigned' => Null ,
                'date_delivred' => Null ,
            ];
            Lead::where('type','seller')->where('id',$request->id)->update($data);
            
            $data2 = [
                'id_lead' => $request->id,
                'id_user' => Auth::user()->id,
                'country_id' => Auth::user()->country_id,
                'status' => "call later",
                'comment' => $request->comment,
                'date' => $date_time,
            ];
            HistoryStatu::insert($data2);
            $datta = array();
            $datta['date_delivred'] = Null;
            $datta['livrison'] = "unpacked";
            $datta['outstock'] = "0";
            LeadProduct::where('id_lead',$request->id)->update($datta);
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>false]);
        }
    }

    public function Upselldetails(Request $request)
    {
        $leads = Lead::where('type','seller')->where('id',$request->id)->first();
        $upsell = Product::with('upselles')->where('id', $leads->id_product)->get();
        //dd($leads);
        $upsell = json_decode($upsell);
        return response()->json($upsell);
    }

    public function date(Request $request)
    {
        //dd($request->id);
        $last = Lead::where('type','seller')->where('tracking','!=',null)->where('status_confirmation','confirmed')->orderBy('updated_at', 'desc')->first();
        
        $data = [
            'date_delivred' => $request->date,
            'status' => "1",
            'id_assigned' => Auth::user()->id,
            'last_contact' => new DateTime(),
        ];

        Lead::where('type','seller')->where('id',$request->id)->update($data);
        $data2 = [
            'id_lead' => $request->id,
            'id_user' => Auth::user()->id,
            'country_id' => Auth::user()->country_id,
            'status' => "confirmed",
            'outofstock' => '0',
            'date' => $request->date,
        ];
        HistoryStatu::insert($data2);
        $data4 = [
            'id_lead' => $request->id,
            'id_user' => Auth::user()->id,
            'country_id' => Auth::user()->country_id,
            'status' => "unpacked",
            'comment' => "unpacked",
            'date' => new DateTime(),
        ];
        HistoryStatu::insert($data4);
        $data3 = array();
        $data3['status'] = "unpacked";
        $data3['date_delivred'] = $request->date;
        $data3['outstock'] = "0";
        LeadProduct::where('id_lead',$request->id)->update($data3);
        return response()->json(['success'=>true]);
    }

    public function upsellstore(Request $request)
    {
        $data = [
            'id_lead' => $request->id,
            'isupsell' => '1',
            'id_product' => $request->product,
            'quantity' => $request->quantity,
            'lead_value' => $request->price,
        ];

        LeadProduct::insert($data);
        $lead = Lead::where('type','seller')->where('id',$request->id)->first();
        $data2 = [
            'lead_value' => $lead->lead_value + $request->price ,
            'quantity' => $lead->quantity + $request->quantity ,
        ];
        Lead::where('type','seller')->where('id',$request->id)->update($data2);
        return response()->json(['success'=>true]);
    }

    public function multiupsell(Request $request)
    {
        //dd($request->quantity);
        $data = $request->quantity;
        foreach($data as $item => $v_data){
            //dd($request->id);
            $data = [
                'id_lead' => $request->id,
                'isupsell' => '0',
                'isupsell' => '1',
                'id_product' => $request->product[$item],
                'quantity' => $request->quantity[$item],
                'lead_value' => $request->price[$item],
            ];
            //dd($data);
            LeadProduct::insert($data);
        }
            $lead = Lead::where('type','seller')->where('id',$request->id)->first();
            $data2 = [
                'lead_value' => LeadProduct::where('id_lead',$request->id)->sum('lead_value') ,
                'quantity' => LeadProduct::where('id_lead',$request->id)->sum('quantity') ,
            ];
            Lead::where('type','seller')->where('id',$request->id)->update($data2);
        //
        //
        return response()->json(['success'=>true]);
    }

    public function settings()
    {
        return view('backend.leads.settings');
    }

    public function notestatus(Request $request)
    {
        $status = Lead::where('type','seller')->where('id',$request->id)->first();
        $data = [
            'id_lead' => $request->id,
            'id_user' => Auth::user()->id,
            'country_id' => Auth::user()->country_id,
            'status' => $status->status_confirmation,
            'comment' => $request->comment,
            'date' => $request->date,
            'status' => '0',
        ];

        HistoryStatu::insert($data);
        return response()->json(['success'=>true]);
    }

    public function statusc(Request $request)
    {
        $checklead = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->first();
        if($checklead){
            $sta = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->first();//dd($sta->status_confirmation);

            if($sta->status_confirmation == "no answer"){
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+90 minutes', strtotime($date)));
                $data2 = array();
                $data2['status_confirmation'] = 'no answer 2';
                $data2['status_livrison'] = 'unpacked';
                $data2['comment'] = $request->note;
                //$data2['status'] = '0';
                //$data2['id_assigned'] = Null;
                $data2['date_call'] = $datemod;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 2",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }elseif($sta->status_confirmation == "no answer 2"){
                $time = "09:00:00";
                $tomorrow = Carbon::tomorrow()->format('Y-m-d');
                $datemod = $tomorrow .' '. $time;
                $data2 = array();
                $data2['status_confirmation'] = "no answer 3";
                $data2['status_livrison'] = 'unpacked';
                $data2['comment'] = $request->note;
                //$data2['status'] = '0';
                //$data2['id_assigned'] = Null;
                $data2['date_call'] = $datemod;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 3",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }elseif($sta->status_confirmation == "no answer 3"){
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+90 minutes', strtotime($date)));
                $data2 = array();
                $data2['status_confirmation'] = "no answer 4";
                $data2['status_livrison'] = 'unpacked';
                $data2['comment'] = $request->note;
                //$data2['status'] = '0';
                //$data2['id_assigned'] = Null;
                $data2['date_call'] = $datemod;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 4",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }elseif($sta->status_confirmation == "no answer 4"){
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+90 minutes', strtotime($date)));
                $data2 = array();
                $data2['status_confirmation'] = "no answer 5";
                $data2['status_livrison'] = 'unpacked';
                $data2['comment'] = $request->note;
                //$data2['status'] = '0';
                //$data2['id_assigned'] = Null;
                $data2['date_call'] = $datemod;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 5",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);

            }elseif($sta->status_confirmation == "no answer 5"){
                $time = "09:00:00";
                $tomorrow = Carbon::tomorrow()->format('Y-m-d');
                $datemod = $tomorrow .' '. $time;
                $data2 = array();
                $data2['status_confirmation'] = "no answer 6";
                $data2['status_livrison'] = 'unpacked';
                $data2['comment'] = $request->note;
                //$data2['status'] = '0';
                //$data2['id_assigned'] = Null;
                $data2['date_call'] = $datemod;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 6",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);

            }elseif($sta->status_confirmation == "no answer 6"){
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+90 minutes', strtotime($date)));
                $data2 = array();
                $data2['status_confirmation'] = "no answer 7";
                $data2['status_livrison'] = 'unpacked';
                $data2['comment'] = $request->note;
                //$data2['status'] = '0';
                //$data2['id_assigned'] = Null;
                $data2['date_call'] = $datemod;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 7",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);

            }elseif($sta->status_confirmation == "no answer 7"){
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+90 minutes', strtotime($date)));
                $data2 = array();
                $data2['status_confirmation'] = "no answer 8";
                $data2['status_livrison'] = 'unpacked';
                $data2['comment'] = $request->note;
                //$data2['status'] = '0';
                //$data2['id_assigned'] = Null;
                $data2['date_call'] = $datemod;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 8",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);

            }elseif($sta->status_confirmation == "no answer 8"){
                $time = "09:00:00";
                $tomorrow = Carbon::tomorrow()->format('Y-m-d');
                $datemod = $tomorrow .' '. $time;
                $data2 = array();
                $data2['status_confirmation'] = "no answer 9";
                $data2['status_livrison'] = 'unpacked';
                $data2['comment'] = $request->note;
                //$data2['status'] = '0';
                //$data2['id_assigned'] = Null;
                $data2['date_call'] = $datemod;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 9",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }elseif($sta->status_confirmation == "no answer 9"){
                $time = "09:00:00";
                $tomorrow = Carbon::tomorrow()->format('Y-m-d');
                $datemod = $tomorrow .' '. $time;
                $data2 = array();
                $data2['status_confirmation'] = "canceled by system";
                $data2['status_livrison'] = 'unpacked';
                $data2['comment'] = $request->note;
                //$data2['status'] = '0';
                //$data2['id_assigned'] = Null;
                $data2['date_call'] = $datemod;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "canceled by system",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }else{
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+90 minutes', strtotime($date)));
                $data2 = [
                    "status_confirmation" => "no answer",
                    "status_livrison" => "unpacked",
                    "comment" => $request->note,
                    'last_status_change' => new DateTime(),
                    'date_call' => $datemod,
                    'last_contact' => new DateTime(),
                ];
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }
        }else{
            return response()->json(['error'=>false]);
        }
        
    }

    public function statusctest(Request $request)
    {
        $checklead = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->first();
        if($checklead){
            $sta = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->first();//dd($sta->status_confirmation);

            if($sta->status_confirmation == "no answer"){
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+180 minutes', strtotime($date)));
                $data2 = array();
                $data2['status_confirmation'] = 'no answer 2';
                $data2['date_call'] = $datemod;
                $data2['note'] = $request->note;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
                $data2['id_assigned'] = Null;
                $data2['status'] = 0;
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 2",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }elseif($sta->status_confirmation == "no answer 2"){
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+180 minutes', strtotime($date)));
                $data2 = array();
                $data2['status_confirmation'] = "no answer 3";
                $data2['date_call'] = $datemod;
                $data2['note'] = $request->note;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
                $data2['id_assigned'] = Null;
                $data2['status'] = 0;
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 3",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }elseif($sta->status_confirmation == "no answer 3"){
                $time = "09:00:00";
                $tomorrow = Carbon::tomorrow()->format('Y-m-d');
                $datemod = $tomorrow .' '. $time;
                $data2 = array();
                $data2['status_confirmation'] = "no answer 4";
                $data2['date_call'] = $datemod;
                $data2['note'] = $request->note;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
                $data2['id_assigned'] = Null;
                $data2['status'] = 0;
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 4",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }elseif($sta->status_confirmation == "no answer 4"){
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+180 minutes', strtotime($date)));
                $data2 = array();
                $data2['status_confirmation'] = "no answer 5";
                $data2['date_call'] = $datemod;
                $data2['note'] = $request->note;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
                $data2['id_assigned'] = Null;
                $data2['status'] = 0;
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 5",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);

            }elseif($sta->status_confirmation == "no answer 5"){
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+180 minutes', strtotime($date)));
                $data2 = array();
                $data2['status_confirmation'] = "no answer 6";
                $data2['date_call'] = $datemod;
                $data2['note'] = $request->note;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
                $data2['id_assigned'] = Null;
                $data2['status'] = 0;
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 6",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);

            }elseif($sta->status_confirmation == "no answer 6"){
                $time = "09:00:00";
                $tomorrow = Carbon::tomorrow()->format('Y-m-d');
                $datemod = $tomorrow .' '. $time;
                $data2 = array();
                $data2['status_confirmation'] = "no answer 7";
                $data2['date_call'] = $datemod;
                $data2['note'] = $request->note;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
                $data2['id_assigned'] = Null;
                $data2['status'] = 0;
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 7",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);

            }elseif($sta->status_confirmation == "no answer 7"){
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+180 minutes', strtotime($date)));
                $data2 = array();
                $data2['status_confirmation'] = "no answer 8";
                $data2['date_call'] = $datemod;
                $data2['note'] = $request->note;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
                $data2['id_assigned'] = Null;
                $data2['status'] = 0;
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 8",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);

            }elseif($sta->status_confirmation == "no answer 8"){
                $time = "09:00:00";
                $tomorrow = Carbon::tomorrow()->format('Y-m-d');
                $datemod = $tomorrow .' '. $time;
                $data2 = array();
                $data2['status_confirmation'] = "no answer 9";
                $data2['date_call'] = $datemod;
                $data2['note'] = $request->note;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
                $data2['id_assigned'] = Null;
                $data2['status'] = 0;
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer 9",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }elseif($sta->status_confirmation == "no answer 9"){
                $time = "09:00:00";
                $tomorrow = Carbon::tomorrow()->format('Y-m-d');
                $datemod = $tomorrow .' '. $time;
                $data2 = array();
                $data2['status_confirmation'] = "canceled by system";
                $data2['date_call'] = $datemod;
                $data2['note'] = $request->note;
                $data2['last_status_change'] = new DateTime();
                $data2['last_contact'] = new DateTime();
                $data2['id_assigned'] = Null;
                $data2['status'] = 0;
            
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $datta = array();
                $datta['date_delivred'] = Null;
                LeadProduct::where('id_lead',$request->id)->update($datta);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "canceled by system",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }else{
                $date = date('Y-m-d H:i');
                $datemod = date('Y-m-d H:i', strtotime('+180 minutes', strtotime($date)));
                $data2 = [
                    "status_confirmation" => "no answer",
                    'last_status_change' => new DateTime(),
                    'date_call' => $datemod,
                    'note' => $request->note,
                    'last_contact' => new DateTime(),
                    'id_assigned' => Null,
                    'status' => 0,
                ];
                Lead::where('type','seller')->where('id',$request->id)->update($data2);
                $status = Lead::where('type','seller')->where('id',$request->id)->first();
                $data = [
                    'id_lead' => $status->id,
                    'id_user' => Auth::user()->id,
                    'country_id' => Auth::user()->country_id,
                    'status' => "no answer",
                    'comment' => $request->note,
                    'date' => new DateTime(),
                ];
        
                HistoryStatu::insert($data);
                return response()->json(['success'=>true]);
            }
        }else{
            return response()->json(['error'=>false]);
        }
        
    }

    public function blackList(Request $request)
    {
        $checklead = Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->first();
        if($checklead){
            $leads = Lead::where('phone',$checklead->phone)
                            ->where('status_livrison','returned')             
                            ->where('id_country',Auth::user()->country_id)
                            ->count();
            $leadsD =  Lead::where('phone',$checklead->phone)
                            ->where('status_livrison','delivered')             
                            ->where('id_country',Auth::user()->country_id)
                            ->count();
            $blackList = BlackList::where('phone',$checklead->phone)
                    ->where('id_country',Auth::user()->country_id)
                    ->count();

            $name = Lead::where('phone',$checklead->phone)->where('id_country',Auth::user()->country_id)->first()->name;
            // if($leads >= 2){
            //     if($blackList == 0){
                    $data = [
                        'name' => $name,
                        'id_user' => Auth::user()->id,
                        'phone' => $checklead->phone,
                        'id_country' => Auth::user()->country_id,
                    ];
            
                    BlackList::create($data);          
                    $lead = Lead::where('type','seller')
                            ->where('id',$request->id)
                            ->where('status_livrison','unpacked')->first();           
                                   
                    $data = [
                        'status_confirmation' => "black listed",
                        'status_livrison' => "unpacked",
                        'last_contact' => new DateTime(),
                        'last_status_change' => new DateTime(),
                        'status' => '0',
                        'id_assigned' => Auth::user()->id,
                        'date_delivred' => Null,
                    ];                                
                   Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->update($data);
                    $data2 = [
                        'id_lead' => $request->id,
                        'id_user' => Auth::user()->id,
                        'country_id' => Auth::user()->country_id,
                        'status' => "black listed",
                        'comment' => "black listed",
                        'date' => new DateTime(),
                    ];
                    HistoryStatu::insert($data2);
                    return response()->json(['success'=>true]);
                // }else{
                //     $lead = Lead::where('type','seller')
                //             ->where('id',$request->id)
                //             ->where('status_livrison','unpacked')->first();           
                //     $data = [                      
                //         'status_confirmation' => "black listed",
                //        'status_livrison' => "unpacked",
                //        'last_contact' => new DateTime(),
                //         'last_status_change' => new DateTime(),
                //         'status' => '0',
                //         'id_assigned' => Auth::user()->id,
                //         'date_delivred' => Null,
                //     ];                                           
                //     Lead::where('type','seller')->where('id',$request->id)->where('status_livrison','unpacked')->update($data);
                //     $data2 = [
                //         'id_lead' => $request->id,
                //         'id_user' => Auth::user()->id,
                //         'status' => "black listed",
                //         'comment' => "black listed",
                //         'date' => new DateTime(),
                //     ];
                //     HistoryStatu::insert($data2);
                //     return response()->json(['success'=>true]);
                // }
            // }else{
            //     return response()->json(['error'=>false]);
            // }    
        }else{
            return response()->json(['error'=>false]);
        }
        
    }

      public function history(Request $request)
    {
        $history = HistoryStatu::with(['lead:id,n_lead','delivery','agent'])->where('id_lead', $request->id)
        ->orderBy('created_at', 'DESC')
        ->get(['id_lead', 'status', 'comment', 'created_at']);
        return response()->json($history);
    }

    // public function history(Request $request)
    // {
    //     $history = HistoryStatu::with('calluser')->where('id_lead',$request->id)->get();
    //    //dd($history);
    //     $output = " ";
    //     foreach($history as $v_history){
    //         if(!empty($v_history['calluser']->name)){
    //             $output.=
    //             '<tr>
    //                 <td>'. $v_history['calluser']->name .'</td>
    //                 <td>'. $v_history->status .'</td>
    //                 <td>'. $v_history->created_at .'</td>         
    //                 <td>'. $v_history->comment .'</td>
    //             </tr>';
    //         }
    //     }
    //     return response($output);
    // }

    public function infoupsell($id)
    {
        $lead = Lead::where('type','seller')->where('id',$id)->first();
        $info = Upsel::where('id_product',$lead->id_product)->get();
        //dd($info);
        $output = "";
        foreach($info as $v_info){
            $output .='
                <tr>
                    <td>'. $v_info->quantity .'</td>
                    <td>'. $v_info->discount .'</td>
                    <td>'. $v_info->note .'</td>
                </tr>
            ';
        }
        return response($output);
        
    }

    public function relance(Request $request,$id)
    {
        $proo = Product::get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();

        $lead = Lead::where('type','seller')->where('id',$id)->first();
        
        $livreurs = Lead::where('type','seller')->with('livreur')->where('id_product',$lead->id_product)->where('id_zone',$lead->id_zone)->get();
        return view('backend.leads.relance', compact('livreurs','proo','products','productss','product','cities'));
    }

    //deleted upsell

    public function deleteupsell(Request $request)
    {
       
            $upsell = LeadProduct::findOrfail($request->id);
            $lead = Lead::where('type','seller')->where('id',$upsell->id_lead)->first();
            $product = Product::where('id',$upsell->id_product)->first();
            $history = array();
            $history['id_lead'] = $lead->id;
            $history['id_user'] = Auth::user()->id;
            $history['country_id'] = Auth::user()->country_id;
            $history['status'] = "delete Upsell";
            $history['comment'] = "delete product".' '. $product->name;
            HistoryStatu::insert($history);
            $data = array();
            $data['quantity'] = $lead->quantity - $upsell->quantity;
            $data['lead_value'] = $lead->lead_value - $upsell->lead_value;
            Lead::where('type','seller')->where('id',$lead->id)->update($data);
            LeadProduct::where('id',$request->id)->delete();
            $lead = Lead::where('type','seller')->where('id',$lead->id)->select('lead_value' , 'quantity')->first();
            $leadpro = LeadProduct::where('id_lead',$lead->id)->select('lead_value' , 'quantity')->first();
            $leads = json_decode($lead);
            $pro = json_decode($leadpro);
            return back();
        
    }

    //update price

    public function updateprice(Request $request)
    {
        $product = Product::where('id',$request->product)->first();
        $pr = LeadProduct::where('id_lead',$request->id)->first();
        $history = array();
        $history['id_lead'] = $request->id;
        $history['id_user'] = Auth::user()->id;
        $history['country_id'] = Auth::user()->country_id;
        $history['status'] = "Update Price Or Quantity";
        $history['comment'] = "Update Price Or Quantity Product".' '. $product->name .' last Price '. $pr->lead_value .' New Price'. $request->leadvalue  .' last Quantity '. $pr->quantity .' New Quantity'. $request->quantity ;
        HistoryStatu::insert($history);
        $data = array();
        $data['lead_value'] = $request->leadvalue;
        $data['quantity'] = $request->quantity;
        $data['id_product'] = $request->product;
        LeadProduct::where('id',$pr->id)->update($data);
        $price = LeadProduct::where('id_lead',$request->id)->sum('lead_value');
        $quantity = LeadProduct::where('id_lead',$request->id)->sum('quantity');
        $data2 = array();
        $data2['lead_value'] = $price;
        $data2['quantity'] = $quantity;
        $data2['id_product'] = $request->product;
        Lead::where('type','seller')->where('id',$request->id)->update($data2);

        $leads = Lead::where('type','seller')->where('id',$request->id)->first();
        $leads = json_decode($leads);
        return response()->json(['success'=>true , 'update' =>  $leads]);
    }

public function another(Request $request)
{
    $proo = Product::where('id_country', Auth::user()->country_id)->get();
    $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
    $cities = Citie::where('id_country', Auth::user()->country_id)->get();
    $provinces = Province::with('cities')->where('id_country', Auth::user()->country_id)->get();
    $customer = $request->customer;
    $ref = $request->ref;
    $phone = $request->phone1;
    $city = $request->city;
    $product = $request->id_prod;
    $confirmation = $request->confirmation;
    $date = date('Y-m-d');
    $minutesToAdd = 2;
    $datemod = date('Y-m-d H:i', strtotime('+60 minutes', strtotime($date)));

    $assignedProductIds = AssignedProduct::where('id_agent', Auth::user()->id)
        ->pluck('id_product')
        ->toArray();

    $calll = Lead::where('type','seller')
        ->with('product','leadpro','country','cities')
        ->where('id_country', Auth::user()->country_id)
        ->where('deleted_at',0)
        ->where('id_assigned', Auth::user()->id)
        ->whereIn('id_product', $assignedProductIds) 
        ->whereIn('status_confirmation',['no answer','no answer 2','no answer 3','no answer 4','no answer 5','no answer 6','no answer 7','no answer 8','no answer 9'])
        ->where('status_livrison','unpacked')
        ->where('date_call', '<', date('Y-m-d H:i'))
        ->orderby('id','asc')
        ->orderby(DB::raw("DATE_FORMAT(date_call , 'Y-m-d')"),'asc')
        ->limit(1)
        ->first();

    if(!$calll){
        $calll = Lead::where('type','seller')
            ->with('product','leadpro','country','cities')
            ->where('id_country', Auth::user()->country_id)
            ->where('deleted_at',0)
            ->where('status',0)
            ->whereNull('id_assigned')
            ->whereIn('id_product', $assignedProductIds)
            ->whereIn('status_confirmation',['no answer','no answer 2','no answer 3','no answer 4','no answer 5','no answer 6','no answer 7','no answer 8','no answer 9'])
            ->where('status_livrison','unpacked')
            ->where('date_call', '<', date('Y-m-d H:i'))
            ->orderby('id','asc')
            ->orderby(DB::raw("DATE_FORMAT(date_call , 'Y-m-d')"),'asc')
            ->limit(1)
            ->first();
    }

    if(!empty($calll->id)){
        $lead = Lead::where('type','seller')
            ->with('product','leadpro','country','cities')
            ->where('id_country', Auth::user()->country_id)
            ->where('id',$calll->id)
            ->where('status_livrison','unpacked')
            ->where('deleted_at',0)
            ->orderby('id','asc')
            ->first();
            
        Lead::where('type','seller')->where('id',$lead->id)->update(['status' => '1' , 'id_assigned' => Auth::user()->id]);
        $detailpro = Product::where('id',$lead->id_product)->first();
        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
        $detailupsell = Upsel::where('id_product',0)->get();
        $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
        $allproductlead = LeadProduct::with('product')->get();
    }else{
        $lead = Lead::where('type','seller')
            ->with('product','leadpro','country','cities')
            ->where('id_country', Auth::user()->country_id)
            ->whereIn('id_product', $assignedProductIds) 
            ->whereIn('status_confirmation',['no answer','no answer 2','no answer 3','no answer 4','no answer 5','no answer 6','no answer 7','no answer 8','no answer 9'])
            ->where('status_livrison','unpacked')
            ->where('id_assigned', Null)
            ->where('date_call', '<', date('Y-m-d H:i:s'))
            ->where('deleted_at',0)
            ->orderby(DB::raw("DATE_FORMAT(date_call , 'Y-m-d')"),'asc')
            ->orderby('id','asc')
            ->first();

        if($lead){
            Lead::where('type','seller')->where('id',$lead->id)->update(['status' => '1' , 'id_assigned' => Auth::user()->id]);
            $detailpro = Product::where('id',$lead->id_product)->first();
            $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
            $detailupsell = Upsel::where('id_product',0)->get();
            $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
            $allproductlead = LeadProduct::with('product')->get();
        }else{
            $lead = Lead::where('type','seller')
                ->with('product','leadpro','country','cities')
                ->where('id_country', Auth::user()->country_id)
                ->whereIn('id_product', $assignedProductIds) 
                ->whereIn('status_confirmation',['no answer','no answer 2','no answer 3','no answer 4','no answer 5','no answer 6','no answer 7','no answer 8','no answer 9'])
                ->where('status_livrison','unpacked')
                ->where('id_assigned','=', Auth::user()->id)
                ->where('date_call', '<', date('Y-m-d H:i'))
                ->where('deleted_at',0)
                ->orderby('id','asc')
                ->first();
                
            if($lead){
                Lead::where('type','seller')->where('id',$lead->id)->update(['status' => '1' , 'id_assigned' => Auth::user()->id]);
                $detailpro = Product::where('id',$lead->id_product)->first();
                $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                $detailupsell = Upsel::where('id_product',0)->get();
                $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
                $allproductlead = LeadProduct::with('product')->get();
            }
        }
    }

    if(!empty($lead)){
        $seller = User::where('id',$lead->id_user)->first();
        $leadproduct = LeadProduct::with('product')->where('isupsell',1)->where('id_lead',$lead->id)->get();
        $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
        
        if($lead->status_confirmation == "confirmed" ){
            return back();
        }
        if($lead->status_confirmation == "canceled" ){
            return back();
        }
        if($lead->status_confirmation == "canceled by system" ){
            return back();
        }
    }else{
        $lead = 0;
        $seller = 0;
        $detailupsell = Upsel::where('id_product',0)->get();
        $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
        $leadproduct = LeadProduct::with('product')->where('id_lead',1)->get();
        $allproductlead = LeadProduct::with('product')->get();
        $productseller = Product::get();
        $detailpro = Product::get();
    }

    if(!empty($lead->id_product)){
        $imports = Import::where('id_product',$lead->id_product)->select('warehouse_id')->get();
        $warehouses = Warehouse::where('country_id',Auth::user()->country_id)->wherein('id',$imports)->get();
    }else{
        $warehouses = Warehouse::where('country_id',Auth::user()->country_id)->get();
    }


    
    return view('backend.leads.leadagent', compact('seller','proo','products','productss','productseller','cities','provinces','lead','detailpro','detailupsell','listupsel','leadproduct','allproductlead','warehouses'));
}


    public function exports(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $v_id){
            $data[] = Lead::where('type','seller')->whereIn('id',explode(",",$v_id))->where('deleted_at',0)->get();
        }
    }


    public function downloads(Request $request,$leads)
    {
        $leads = json_decode($leads);
        //dd($leads);
        $leads = new LeadExport([$leads]);
        return Excel::download($leads, 'Leads.xlsx');
    }

    public function exportall()
    {
        $leads = new AllLead();
        return Excel::download($leads, 'Leads.xlsx');

    }

    public function refresh($id)
    {
        $data = (new GoogleSheetServices ())->getClient();
        $client = $data;
        //dd($client);
        
            $info = Lead::where('type','seller')->where('id',$id)->first();
            try{
                $v_sheet = Sheet::where('id',$info->id_sheet)->first();//dd($info);
                $userss = User::where('id',$info->id_user)->first();
                $ne = substr(strtoupper($userss->name), 0 , 1);
                $n = substr(strtoupper($userss->name), -1);
                $service = new Sheets($client);
                $spreadsheetId = $v_sheet->sheetid;
                $spreadsheetName = $v_sheet->sheetname.'!A2:L';
                //dd($spreadsheetName);
                $range = $spreadsheetName;
                $doc = $service->spreadsheets_values->get($spreadsheetId, $range);
                $response = $doc;
                $lastsheet = Lead::where('type','seller')->where('id',$id)->orderby('id','desc')->first();//dd($lastsheet->id_sheet);
                //dd($lastsheet);
                if($lastsheet){
                    $index = $lastsheet->index_sheet;
                }
                $last = Lead::where('type','seller')->orderby('id','DESC')->first();
                if(empty($last->id)){
                    $kk = 1;
                }else{
                    $kk = $last->id + 1;
                }//dd($v_sheet->id);
                 //dd($response['values'][$index][4]);
                $countries = Countrie::where('id',$v_sheet->id_warehouse)->first();
                        
                $data = array();
                if(!empty($response['values'][$index][0])){
                    $data['id_order'] = $response['values'][$index][0];
                }
                $data['id_product'] = $v_sheet->id_product;
                if(!empty($response['values'][$index][2])){
                    $data['name'] = $response['values'][$index][2];
                }
                if(!empty($response['values'][$index][4])){
                    $data['phone'] = $countries->negative. $response['values'][$index][4];
                }
                if(!empty($response['values'][$index][5])){
                    $data['phone2'] = $countries->negative. $response['values'][$index][5];
                }
                if(!empty($response['values'][$index][8])){
                    $data['city'] = $response['values'][$index][8];
                }
                if(!empty($response['values'][$index][7])){
                    $data['address'] = $response['values'][$index][7];
                }
                if(!empty($response['values'][$index][9])){
                    $data['lead_value'] = $response['values'][$index][9];
                }
                if(!empty($response['values'][$index][9]) && !empty($response['values'][$index][3]) && !empty($response['values'][$index][2]) && !empty($response['values'][$index][4])){
                    Lead::where('type','seller')->where('id',$id)->update($data);
                            
                    $data2 = array();
                    $data2['id_product'] = $v_sheet->id_product;
                    if(!empty($response['values'][$index][3])){
                        $data2['quantity'] = $response['values'][$index][3];
                    }
                    if(!empty($response['values'][$index][9])){
                        $data2['lead_value'] = $response['values'][$index][9];
                    }
                    LeadProduct::where('id_lead',$id)->update($data2);
                }

            }catch(\Exception $e){
                return redirect()->route('leads.another');
            }

                    return redirect()->route('leads.index');
                
    }

    public function calllater(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $provinces = Province::with('cities')->where('id_country', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        
        $assignedProductIds = AssignedProduct::where('id_agent', Auth::user()->id)
            ->pluck('id_product')
            ->toArray();

        if(Auth::user()->id_role = "3"){
            $leads = Lead::where('type','seller')
                ->with('product','leadpro','country','cities','livreur')
                ->where('status_confirmation','call later')
                ->where('id_country', Auth::user()->country_id)
                ->where('status_livrison','unpacked')
                ->whereIn('id_product', $assignedProductIds) 
                ->where('deleted_at',0)
                ->orderBy('date_call', 'asc');
        }else{
            $leads = Lead::where('type','seller')
                ->with('product','leadpro','country','cities')
                ->where('status_confirmation','call later')
                ->where('id_country', Auth::user()->country_id)
                ->where('status_livrison','unpacked')
                ->whereIn('id_product', $assignedProductIds) 
                ->where('id_assigned',null);
        }

        if(!empty($request->customer)){
            $leads = $leads->where('name',$request->customer);
        }
        if(!empty($request->ref)){
            $leads = $leads->where('n_lead',$request->ref);
        }
        if(!empty($request->phone1)){
            $leads = $leads->where('phone','like','%'.$request->phone1.'%');
        }
        if(!empty($request->phone1)){
            $leads = $leads->where('phone2','like','%'.$request->phone1.'%');
        }
        if(!empty($request->city)){
            $leads = $leads->where('id_city',$request->city);
        }
        if(!empty($request->id_prod)){
            $leads = $leads->where('id_product',$request->id_prod);
        }
        if(!empty($request->confirmation)){
            $leads = $leads->where('status_confirmation',$request->confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        
        if(Auth::user()->id_role != "3"){
            if($request->items){
                $items = $request->items;
            }else{
                $items = 10;
            }
            $leads= $leads->where('deleted_at',0)->paginate($items);
        }else{
            $leads= $leads->where('deleted_at',0)->orderby('id','asc')->limit(1)->get();
        }
        
        $date = date('Y-m-d');
        $minutesToAdd = 2;
        $datemod = date('Y-m-d H:i', strtotime('+60 minutes', strtotime($date)));
        
        $calll = Lead::where('type','seller')
            ->with('product','leadpro','country','cities')
            ->where('id_country', Auth::user()->country_id)
            ->where('status_confirmation','call later')
            ->where('status_livrison','unpacked')
            ->whereIn('id_product', $assignedProductIds) 
            ->whereDate('date_call', '<=', $date)
            ->where('deleted_at',0)
            ->orderby('last_contact','asc')
            ->limit(1)
            ->first();
            
        if($calll){
            if($calll->id_assigned == Auth::user()->id){
                $lead = Lead::where('type','seller')
                    ->with('product','leadpro','country','cities')
                    ->where('id_country', Auth::user()->country_id)
                    ->where('id',$calll->id)
                    ->where('id_assigned',Auth::user()->id)
                    ->where('status_livrison','unpacked')
                    ->where('deleted_at',0)
                    ->first();
                    
                Lead::where('type','seller')->where('id',$lead->id)->where('id_assigned',Auth::user()->id)->where('status_livrison','unpacked')->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
                
                $asssigned = array();
                $detailpro = Product::where('id',$lead->id_product)->first();
                $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                
                if($lead->status_confirmation == "confirmed" ){
                    return back();
                }
                if($lead->status_confirmation == "canceled" ){
                    return back();
                }
                if($lead->status_confirmation == "canceled by system" ){
                    return back();
                }
            }else{
                $lead = Lead::where('type','seller')
                    ->with('product','leadpro','country','cities')
                    ->where('id_country', Auth::user()->country_id)
                    ->where('id_assigned','=', Null)
                    ->where('status_confirmation','call later')
                    ->where('status_livrison','unpacked')
                    ->whereIn('id_product', $assignedProductIds)
                    ->whereDate('date_call', '<=', $date)
                    ->where('status',0)
                    ->where('deleted_at',0)
                    ->orderby('last_contact','asc')
                    ->first();
                    
                if($lead){
                    Lead::where('type','seller')->where('id',$lead->id)->where('id_assigned',Null)->where('status_livrison','unpacked')->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
                    $asssigned = array();
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    
                    if($lead->status_confirmation == "confirmed" ){
                        return back();
                    }
                    if($lead->status_confirmation == "canceled" ){
                        return back();
                    }
                    if($lead->status_confirmation == "canceled by system" ){
                        return back();
                    }
                }
            }
            
            $detailupsell = Upsel::where('id_product',0)->get();
            $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
            $allproductlead = LeadProduct::with('product')->get();
        }
        
        if(empty($lead->id)){
            $lead = Lead::where('type','seller')->where('status_livrison','unpacked')->where('id',0)->first();
            $detailpro = Product::where('id',1)->first();
        }
        
        if(!empty($lead)){
            $status = array();
            $status['status'] = '1';
            Lead::where('type','seller')->where('id',$lead->id)->where('status_livrison','unpacked')->update($status);
        }
        
        if(Auth::user()->id_role != "3"){
            return view('backend.leads.index', compact('proo','products','productss','leads','lead','detailpro','items'));
        }else{
            if($lead && $lead->id != 0){
                $seller = User::where('id',$lead->id_user)->first();
                $leadproduct = LeadProduct::with('product')->where('isupsell',1)->where('id_lead',$lead->id)->get();
                $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
                $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                
                if($lead->status_confirmation == "canceled"){
                    return back();
                }
                if($lead->status_confirmation == "canceled by system"){
                    return back();
                }
                if($lead->status_confirmation == "confirmed"){
                    return back();
                }
            }else{
                $seller = 0;
                $leadproduct = Null;
                $allproductlead = Null;
                $productseller = Null;
                $detailpro = Null;
                $detailupsell = Null;
                $listupsel = Null;
            }
            $warehouses = Warehouse::get();
            return view('backend.leads.leadagent', compact('cities','proo','products','productss','productseller','leads','lead','detailpro','detailupsell','listupsel','leadproduct','allproductlead','seller','provinces','warehouses'));
        }
    }

    public function leadduplicated(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur')->where('status_confirmation','duplicated')->where('id_country', Auth::user()->country_id)->where('deleted_at',0)->orderBy('id', 'DESC');
        
        if(!empty($request->customer)){
            $leads = $leads->where('name',$request->customer);
        }
        if(!empty($request->ref)){
            $leads = $leads->where('n_lead',$request->ref);
        }
        if(!empty($request->phone1)){
            $leads = $leads->where('phone','like','%'.$request->phone1.'%');
        }
        if(!empty($request->phone1)){
            $leads = $leads->where('phone2','like','%'.$request->phone1.'%');
        }
        if(!empty($request->city)){
            $leads = $leads->where('id_city',$request->city);
        }
        if(!empty($request->id_prod)){
            $leads = $leads->where('id_product',$request->id_prod);
        }
        if(!empty($request->confirmation)){
            $leads = $leads->where('status_confirmation',$request->confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
        }
        $leads= $leads->where('deleted_at',0)->select('id','n_lead','id_order','name','city','id_city','lead_value','status_confirmation','status_livrison','created_at','last_status_change')->paginate($items);
        $type = "Duplicated";
        return view('backend.leads.index', compact('proo','products','productss','leads','items','type'));

    }

    public function leadhorzone(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur')->where('status_confirmation','out of area')->where('id_country', Auth::user()->country_id)->where('deleted_at',0)->orderBy('id', 'DESC');
        
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
        }
        if(!empty($request->customer)){
            $leads = $leads->where('name',$request->customer);
        }
        if(!empty($request->ref)){
            $leads = $leads->where('n_lead',$request->ref);
        }
        if(!empty($request->phone1)){
            $leads = $leads->where('phone','like','%'.$request->phone1.'%');
        }
        if(!empty($request->phone1)){
            $leads = $leads->where('phone2','like','%'.$request->phone1.'%');
        }
        if(!empty($request->city)){
            $leads = $leads->where('id_city',$request->city);
        }
        if(!empty($request->id_prod)){
            $leads = $leads->where('id_product',$request->id_prod);
        }
        if(!empty($request->confirmation)){
            $leads = $leads->where('status_confirmation',$request->confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        $leads= $leads->where('deleted_at',0)->paginate($items);
        $type = "Out of Area";
        return view('backend.leads.index', compact('proo','products','productss','leads','items','type'));

    }

    public function leadwrong(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
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
        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur')->where('status_confirmation','wrong')->where('id_country', Auth::user()->country_id)->orderBy('id', 'DESC');
        
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
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
        if(!empty($phone)){
            $leads = $leads->where('phone2','like','%'.$phone.'%');
        }
        if(!empty($city)){
            $leads = $leads->where('id_city',$city);
        }
        if(!empty($product)){
            $leads = $leads->where('id_product',$product);
        }
        if(!empty($confirmation)){
            $leads = $leads->where('status_confirmation',$confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        $leads= $leads->where('deleted_at',0)->paginate($items);
        $type = "Wrong Data";
        return view('backend.leads.index', compact('proo','products','productss','cities','leads','items','type'));

    }

    public function leadcanceled(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
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
        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur')->where('status_confirmation','canceled')->where('id_country', Auth::user()->country_id)->orderBy('id', 'DESC');
        
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
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
        if(!empty($phone)){
            $leads = $leads->where('phone2','like','%'.$phone.'%');
        }
        if(!empty($city)){
            $leads = $leads->where('id_city',$city);
        }
        if(!empty($product)){
            $leads = $leads->where('id_product',$product);
        }
        if(!empty($confirmation)){
            $leads = $leads->where('status_confirmation',$confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        $leads= $leads->where('deleted_at',0)->paginate($items);

        $type = "Rejected";
        return view('backend.leads.index', compact('proo','products','productss','cities','leads','items','type'));

    }
    //out of stock

    public function outofstock(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
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
        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur')->where('status_confirmation','outofstock')->where('id_country', Auth::user()->country_id)->orderBy('id', 'DESC');
        
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
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
        if(!empty($phone)){
            $leads = $leads->where('phone2','like','%'.$phone.'%');
        }
        if(!empty($city)){
            $leads = $leads->where('id_city',$city);
        }
        if(!empty($product)){
            $leads = $leads->where('id_product',$product);
        }
        if(!empty($confirmation)){
            $leads = $leads->where('status_confirmation',$confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        $leads= $leads->where('deleted_at',0)->paginate($items);

        $type = "Out of Stock";
        return view('backend.leads.index', compact('proo','products','productss','cities','leads','items','type'));
    }

    public function canceledbysystem(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();

        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur')->where('status_confirmation','canceled by system')->where('id_country', Auth::user()->country_id)->where('deleted_at',0)->orderBy('id', 'DESC');
        
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
        }
        if(!empty($request->customer)){
            $leads = $leads->where('name',$request->customer);
        }
        if(!empty($request->ref)){
            $leads = $leads->where('n_lead',$request->ref);
        }
        if(!empty($request->phone1)){
            $leads = $leads->where('phone','like','%'.$request->phone1.'%');
        }
        if(!empty($request->phone1)){
            $leads = $leads->where('phone2','like','%'.$request->phone1.'%');
        }
        if(!empty($request->city)){
            $leads = $leads->where('id_city',$request->city);
        }
        if(!empty($request->id_prod)){
            $leads = $leads->where('id_product',$request->id_prod);
        }
        if(!empty($request->confirmation)){
            $leads = $leads->where('status_confirmation',$request->confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        $leads= $leads->where('deleted_at',0)->paginate($items);

        $type = "Canceled By Systeme";
        return view('backend.leads.index', compact('proo','products','productss','leads','items','type'));
    }

    public function client($id, Request $request)
    {
        // $neworder = Lead::where('type','seller')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->get();
        // $proo = Product::where('id_country', Auth::user()->country_id)->get();
        // $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        // $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();    
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        // $provinces = Province::with('cities')->where('id_country', Auth::user()->country_id)->get();
        $sellers = User::where('id_role',2)->get();
        $customer = $request->customer;
        $ref = $request->ref;
        $phone = $request->phone1;
        $city = $request->city;
        $product = $request->id_prod;
        $confirmation = $request->confirmation;

        $lead = Lead::where('type','seller')->where('id',$id)->first();

        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur','seller')->where('id_country', Auth::user()->country_id)->where('phone',$lead->phone);
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
        }
        if($request->id_seller){
            $leads = $leads->where('id_user',$request->id_seller);
        }
        if(!empty($customer)){
            $leads = $leads->where('name',$customer);
        }
        if(!empty($ref)){
            $leads = $leads->where('n_lead',$ref);
        }
        if(!empty($city)){
            $leads = $leads->where('id_city',$city);
        }
        if(!empty($product)){
            $leads = $leads->where('id_product',$product);
        }
        if(!empty($confirmation)){
            $leads = $leads->where('status_confirmation',$confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        $leads= $leads->where('deleted_at',0)->paginate($items);

        return view('backend.leads.client', compact('productss','cities','leads','lead','items','sellers'));
    }

    public function inassigned()
    {
        $leads = Lead::where('type','seller')->where('status_confirmation','new order')->where('id_assigned','!=', Null)->get();
        foreach($leads as $v_lead){
            Lead::where('id',$v_lead->id)->update(['status' => '0' , 'id_assigned' => Null]);
        }

        return back();
    }

   public function discount(Request $request)
{
    $request->validate([
        'discount' => 'nullable|integer|min:0|max:100',
    ]);

    $lead = Lead::where('type', 'seller')->where('id', $request->id)->firstOrFail();

    if (empty($lead->old_lead_value)) {
        $lead->old_lead_value = $lead->lead_value;
    }

   
    $baseValue = $lead->old_lead_value;

    $discAmount = ($baseValue * $request->discount) / 100;
    $newLeadValue = $baseValue - $discAmount;

   
    $lead->update([
        'lead_value' => $newLeadValue,
        'old_lead_value' => $lead->old_lead_value, 
        'discount' => $request->discount, 
    ]);

    
    HistoryStatu::create([
        'id_lead' => $request->id,
        'id_user' => Auth::user()->id,
        'country_id' => Auth::user()->country_id,
        'status' => 'discount',
        'comment' => 'Discount ' . $request->discount . '% applied',
        'date' => now(),
    ]);

    return response()->json([
        'success' => true,
        'total' => $newLeadValue
    ]);
}


    public function restoreOldPrice(Request $request)
    {
        $lead = Lead::where('type', 'seller')->where('id', $request->id)->first();

        if (!$lead || !$lead->old_lead_value) {
            return response()->json([
                'success' => false,
                'message' => 'No old value found to restore.'
            ]);
        }

        $lead->update([
            'lead_value' => $lead->old_lead_value,
            'old_lead_value' => null
        ]);

        $data2 = [
            'id_lead' => $request->id,
            'id_user' => Auth::user()->id,
            'country_id' => Auth::user()->country_id,
            'status' => 'restore',
            'comment' => 'Restored old lead value',
            'date' => new DateTime(),
        ];
        HistoryStatu::insert($data2);

        return response()->json([
            'success' => true,
            'total' => $lead->lead_value
        ]);
    }


    public function single(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        $provinces = Province::with('cities')->where('id_country', Auth::user()->country_id)->get();
        $productsassigned = AssignedProduct::where('id_asgent',Auth::user()->id)->select('id_product')->get()->toarray();
        
        $date = date('Y-m-d');
        //dd($date);
        $minutesToAdd = 2;
        $datemod = date('Y-m-d H:i', strtotime('+60 minutes', strtotime($date)));
        //dd($datemod);
        //check order call later
        $calll = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('id_assigned','=', Null)->where('date_call', '<', date('Y-m-d H:i'))->where('status_confirmation','call later')->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->orderby(DB::raw("DATE_FORMAT(date_call , 'Y-m-d')"),'asc')->where('deleted_at',0)->first();
        $noanswer = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('id_assigned',Null)->where('date_call', '<', date('Y-m-d H:i'))->where('status_confirmation','like','%'.'no answer'.'%')->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->orderby(DB::raw("DATE_FORMAT(date_call , 'Y-m-d')"),'asc')->where('deleted_at',0)->first();
        $newAuth= Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('status','=',0)->where('id_country', Auth::user()->country_id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
        $new = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('status','=',0)->where('id_assigned',Null)->where('id_country', Auth::user()->country_id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
        //dd($noanswer);
        //is existe order call later
        if($new){
            $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('id_assigned', Auth::user()->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
            if($lead){
                //check if assigned this order to this agent
                if($lead->id_assigned == Auth::user()->id){
                    $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id',$lead->id)->where('id_country', Auth::user()->country_id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->limit(1)->first();
                    Lead::where('type','seller')->where('id',$lead->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id , 'status' => '1']);
                    //check order if nexiste product
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    $listupsel = LeadProduct::with('product')->where('id_lead',$lead->id)->where('isupsell',1)->get();
                    $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
                }
                $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                if(empty($lead->id)){
                    $detailpro = Product::first();
                }
            }
            else{
                //if not existion new order assigned
                $lea = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('id_assigned','=', Null)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                if(!empty($lea->id)){
                    // $lead = Lead::where('type','seller')->where('id',$lea->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('id_assigned','=', Null)->first();
                    $lead = Lead::where('type','seller')->where('id',$lea->id)->first();
                    if($lead->id_assigned == Null){
                        Lead::where('type','seller')->where('id',$lead->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id , 'status' => '1']);
                        $detailpro = Product::where('id',$lead->id_product)->first();
                        $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    }
                }
                if(empty($detailupsell)){
                    $detailupsell = Upsel::where('id_product',0)->get();
                }
                $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
                $allproductlead = LeadProduct::with('product')->get();
            }
        }elseif($noanswer){//dd($noanswer);
            //dd($calll);
            $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('id_assigned', Auth::user()->id)->where('status_confirmation','like','%'.'no answer'.'%')->where('status_livrison','unpacked')->where('date_call', '<', date('Y-m-d H:i'))->where('deleted_at',0)->orderby(DB::raw("DATE_FORMAT(date_call , 'Y-m-d')"),'asc')->limit(1)->first();
            if($lead){
                //check if assigned this order to this agent
                if($lead->id_assigned == Auth::user()->id){
                    $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id',$lead->id)->where('id_country', Auth::user()->country_id)->where('status_confirmation','like','%'.'no answer'.'%')->where('status_livrison','unpacked')->where('date_call', '<', date('Y-m-d H:i'))->where('deleted_at',0)->orderby(DB::raw("DATE_FORMAT(date_call , 'Y-m-d')"),'asc')->limit(1)->first();
                    Lead::where('type','seller')->where('id',$lead->id)->where('status_confirmation','like','%'.'no answer'.'%')->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id , 'status' => '1']);
                    //check order if nexiste product
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    $listupsel = LeadProduct::with('product')->where('id_lead',$lead->id)->where('isupsell',1)->get();
                    $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
                }
                $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                if(empty($lead->id)){
                    $detailpro = Product::first();
                }
            }
            else{
                //if not existion new order assigned
                $lea = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('id_assigned','=', Null)->where('status','=','0')->where('status_confirmation','like','%'.'no answer'.'%')->where('status_livrison','unpacked')->where('deleted_at',0)->where('date_call', '<', date('Y-m-d H:i'))->orderby(DB::raw("DATE_FORMAT(date_call , 'Y-m-d')"),'asc')->limit(1)->first();
                if(!empty($lea->id)){
                    $lead = Lead::where('type','seller')->where('id',$lea->id)->where('status_confirmation','like','%'.'no answer'.'%')->where('status_livrison','unpacked')->where('id_assigned','=', Null)->where('date_call', '<', date('Y-m-d H:i'))->orderby(DB::raw("DATE_FORMAT(date_call , 'Y-m-d')"),'asc')->first();
                    if($lead->id_assigned == Null){
                        Lead::where('type','seller')->where('id',$lead->id)->where('status_confirmation','like','%'.'no answer'.'%')->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id , 'status' => '1']);
                        $detailpro = Product::where('id',$lead->id_product)->first();
                        $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    }
                }
                if(empty($detailupsell)){
                    $detailupsell = Upsel::where('id_product',0)->get();
                }
                $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
                $allproductlead = LeadProduct::with('product')->get();
            }
        }elseif(!empty($calll)){
            //dd($calll);
            if(!empty($calll->id_assigned)){
                $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('status_confirmation','call later')->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->where('id',$calll->id)->where('deleted_at',0)->first();
                Lead::where('type','seller')->where('id',$lead->id)->where('status_confirmation','call later')->where('status_livrison','unpacked')->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
                $asssigned = array();
                $detailpro = Product::where('id',$lead->id_product)->first();
                $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
            }else{
                if(!empty($calll->id)){
                    $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('status_confirmation','call later')->where('status_livrison','unpacked')->where('id_country', Auth::user()->country_id)->where('id_assigned', Null)->where('id',$calll->id)->where('deleted_at',0)->first();
                    Lead::where('type','seller')->where('id',$lead->id)->where('id_assigned', Null)->where('status_livrison','unpacked')->where('status_confirmation','call later')->where('id_country', Auth::user()->country_id)->update(['id_assigned' => Auth::user()->id , 'status' => 1]);
                    $asssigned = array();
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                }
            }
            $detailupsell = Upsel::where('id_product',0)->get();
            $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
            $allproductlead = LeadProduct::with('product')->get();
        }elseif($newAuth){
            $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('id_assigned', Auth::user()->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
            if($lead){
                //check if assigned this order to this agent
                if($lead->id_assigned == Auth::user()->id){
                    $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id',$lead->id)->where('id_country', Auth::user()->country_id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->limit(1)->first();
                    Lead::where('type','seller')->where('id',$lead->id)->where('id_assigned',Auth::user()->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id , 'status' => '1']);
                    //check order if nexiste product
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    $listupsel = LeadProduct::with('product')->where('id_lead',$lead->id)->where('isupsell',1)->get();
                    $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
                }
                $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                if(empty($lead->id)){
                    $detailpro = Product::first();
                }
            }
            else{
                //if not existion new order assigned
                $lea = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('id_assigned','=', Null)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                if(!empty($lea->id)){
                    $lead = Lead::where('type','seller')->where('id',$lea->id)->where('status_confirmation','new order')->where('id_assigned','=', Null)->first();
                    Lead::where('type','seller')->where('id',$lead->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('id_assigned','=', Null)->update(['id_assigned'=> Auth::user()->id , 'status' => '1']);
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                }
                if(empty($detailupsell)){
                    $detailupsell = Upsel::where('id_product',0)->get();
                }
                $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
                $allproductlead = LeadProduct::with('product')->get();
            }
        }else{
            //if not existe order call later check new order
            $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('status','=','1')->where('id_country', Auth::user()->country_id)->where('id_assigned', Auth::user()->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
            if($lead){
                //check if assigned this order to this agent
                if($lead->id_assigned == Auth::user()->id){
                    $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('status','=','1')->where('id',$lead->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->limit(1)->first();
                    Lead::where('type','seller')->where('id',$lead->id)->where('id_assigned',Auth::user()->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('id_assigned', Auth::user()->id)->update(['id_assigned'=> Auth::user()->id , 'status' => '1']);
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    $listupsel = LeadProduct::with('product')->where('id_lead',$lead->id)->where('isupsell',1)->get();
                    $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
                }
                $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                if(empty($lead->id)){
                    $detailpro = Product::first();
                }
            }else{
                //if not existion new order assigned
                $lea = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('id_assigned','=', Null)->where('status','=','0')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                if(!empty($lea->id)){
                    //dd($lea->id);
                    $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id',$lea->id)->where('id_country', Auth::user()->country_id)->where('status','=','0')->where('id_assigned',Null)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                    Lead::where('type','seller')->where('id',$lead->id)->where('id_assigned',Null)->where('status_confirmation','new order')->where('status_livrison','unpacked')->update(['id_assigned'=> Auth::user()->id , 'status' => '1']);
                    $detailpro = Product::where('id',$lead->id_product)->first();
                    $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                    $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                }else{
                    $lead = Lead::where('type','seller')->with('product','leadpro','country','cities','stock')->where('id_country', Auth::user()->country_id)->where('id_assigned',Auth::user()->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->orderby('id','asc')->limit(1)->first();
                    if($lead){
                        Lead::where('type','seller')->where('id',$lead->id)->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('id_assigned',Auth::user()->id)->update(['id_assigned'=> Auth::user()->id , 'status' => '1']);
                        $detailpro = Product::where('id',$lead->id_product)->first();
                        $detailupsell = Upsel::where('id_product',$lead->id_product)->get();
                        $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
                    }
                }
                if(empty($detailupsell)){
                    $detailupsell = Upsel::where('id_product',0)->get();
                }
                $listupsel = LeadProduct::with('product')->where('isupsell',1)->get();
                $allproductlead = LeadProduct::with('product')->get();
            }
        }
        if(!empty($lead)){
            $seller = User::where('id',$lead->id_user)->first();
            $leadproduct = LeadProduct::with('product')->where('isupsell',1)->where('id_lead',$lead->id)->get();
            $allproductlead = LeadProduct::with('product')->where('id_lead',$lead->id)->get();
            $productseller = Product::where('id_user',$lead->id_user)->where('id_country', Auth::user()->country_id)->get();
            if($lead->status_confirmation == "canceled"){
                return back();
            }
            if($lead->status_confirmation == "canceled by system"){
                return back();
            }
            if($lead->status_confirmation == "confirmed"){
                return back();
            }
        }else{
            $lead = 0;
            $seller = 0;
            $leadproduct = LeadProduct::with('product')->where('id_lead',1)->get();
            $allproductlead = LeadProduct::with('product')->get();
            $productseller = Product::where('id_country', Auth::user()->country_id)->get();
            $detailpro = Product::where('id_country', Auth::user()->country_id)->get();
        }
        return view('backend.leads.leadagent', compact('proo','products','productss','productseller','cities','provinces','lead','detailpro','detailupsell','listupsel','allproductlead','leadproduct','seller'));
    }


    public function incident(Request $request)
    {
        $date = new DateTime();
        $date = $date->format('Y-m-d');
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        $provinces = Province::with('cities')->where('id_country', Auth::user()->country_id)->get();
        $sellers = User::where('id_role',2)->get();
        $customer = $request->customer;
        $ref = $request->ref;
        $phone = $request->phone1;
        $city = $request->city;
        $product = $request->id_prod;
        $confirmation = $request->confirmation;


        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','incident');
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
        }
        if($request->id_seller){
            $leads = $leads->where('id_user',$request->id_seller);
        }
        if(!empty($customer)){
            $leads = $leads->where('name',$customer);
        }
        if(!empty($ref)){
            $leads = $leads->where('n_lead',$ref);
        }
        if(!empty($city)){
            $leads = $leads->where('id_city',$city);
        }
        if(!empty($product)){
            $leads = $leads->where('id_product',$product);
        }
        if(!empty($confirmation)){
            $leads = $leads->where('status_confirmation',$confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        // if(4 < 9){
        //     dd('pp');
        // }
        $leads= $leads->where('deleted_at',0)->paginate($items);
        $hour24 = Lead::where('type','seller')->where('id_country',Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','incident')->whereRaw('DATEDIFF(Now(),leads.date_shipped) > 6')->orderby('id','desc')->count();
        $day1ou2 = Lead::where('type','seller')->where('id_country',Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','incident')->whereRaw('DATEDIFF(Now(),leads.date_shipped) > 4')->whereRaw('DATEDIFF(Now(),leads.date_shipped) < 6')->orderby('id','desc')->count();
        $day3ou4 = Lead::where('type','seller')->where('id_country',Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','incident')->whereRaw('DATEDIFF(Now(),leads.date_shipped) > 2')->whereRaw('DATEDIFF(Now(),leads.date_shipped) < 4')->orderby('id','desc')->count();
        //dd($hour24);

        return view('backend.leads.incident', compact('proo','products','productss','cities','leads','items','sellers','hour24','day1ou2','day3ou4'));
    }

    public function rejected(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        $provinces = Province::with('cities')->where('id_country', Auth::user()->country_id)->get();
        $sellers = User::where('id_role',2)->get();
        $customer = $request->customer;
        $ref = $request->ref;
        $phone = $request->phone1;
        $city = $request->city;
        $product = $request->id_prod;
        $confirmation = $request->confirmation;


        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','confirmed')->where('status_livrison','rejected');
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
        }
        if($request->id_seller){
            $leads = $leads->where('id_user',$request->id_seller);
        }
        if(!empty($customer)){
            $leads = $leads->where('name',$customer);
        }
        if(!empty($ref)){
            $leads = $leads->where('n_lead',$ref);
        }
        if(!empty($city)){
            $leads = $leads->where('id_city',$city);
        }
        if(!empty($product)){
            $leads = $leads->where('id_product',$product);
        }
        if(!empty($confirmation)){
            $leads = $leads->where('status_confirmation',$confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        $leads= $leads->where('deleted_at',0)->paginate($items);

        return view('backend.leads.client', compact('proo','products','productss','cities','leads','items','sellers'));
    }

    public function noanswer(Request $request)
    {
        $neworder = Lead::where('type','seller')->where('status_confirmation','new order')->where('status_livrison','unpacked')->where('deleted_at',0)->get();
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        $provinces = Province::with('cities')->where('id_country', Auth::user()->country_id)->get();
        $customer = $request->customer;
        $ref = $request->ref;
        $phone = $request->phone1;
        $city = $request->city;
        $product = $request->id_prod;
        $confirmation = $request->confirmation;
        $leads = Lead::where('type','seller')
        ->with('product','leadpro','country','cities','livreur','assigned')
        ->where('status_confirmation','LIKE','%'.'no answer'.'%')
        ->where('id_country', Auth::user()->country_id)
        ->orderBy('id', 'DESC');     
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
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
            $leads = $leads->where('status_confirmation',$confirmation);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        $leads= $leads->where('deleted_at',0)->paginate($items);
        return view('backend.leads.index', compact('proo','products','productss','cities','provinces','leads','items'));
    }

    public function prepaid(Request $request)
    {
        $neworder = Lead::where('type','seller')->where('status_confirmation','new order')->where('status_livrison','unpacked')->get();
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();

        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur')->where('status_confirmation','new order')->where('ispaidapp','1')->where('id_country', Auth::user()->country_id)->orderBy('id', 'DESC');
        
        if($request->customer){
            $leads = $leads->where('name',$request->customer);
        }
        if($request->ref){
            $leads = $leads->where('n_lead',$request->ref);
        }
        if($request->phone1){
            $leads = $leads->where('phone','like','%'.$request->phone1.'%');
        }
        if($request->city){
            $leads = $leads->where('id_city',$request->city);
        }
        if($request->id_prod){
            $leads = $leads->where('id_product',$request->id_prod);
        }
        if($request->confirmation){
            $leads = $leads->where('status_confirmation',$request->confirmation);
        }
        if($request->shipping){
            $leads = $leads->where('status_livrison',$request->shipping);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
        }
        $leads= $leads->where('deleted_at',0)->paginate($items);

        return view('backend.leads.prepaid', compact('proo','products','productss','cities','leads','items'));

    }

    public function listconfirmed(Request $request)
    {
        $proo = Product::where('id_country', Auth::user()->country_id)->get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        
        $leads = Lead::where('type','seller')->with('product','leadpro','country','cities','livreur')->where('status_confirmation','confirmed')->where('id_country', Auth::user()->country_id)->orderBy('id', 'DESC');
        if(Auth::user()->id_role == 3){
            $leads = $leads->where('id_assigned',Auth::user()->id);
        }
        if($request->customer){
            $leads = $leads->where('name',$request->customer);
        }
        if($request->ref){
            $leads = $leads->where('n_lead',$request->ref);
        }
        if($request->phone1){
            $leads = $leads->where('phone','like','%'.$request->phone1.'%');
        }
        if($request->city){
            $leads = $leads->where('id_city',$request->city);
        }
        if($request->id_prod){
            $leads = $leads->where('id_product',$request->id_prod);
        }
        if($request->confirmation){
            $leads = $leads->where('status_confirmation',$request->confirmation);
        }
        if($request->shipping){
            $leads = $leads->where('status_livrison',$request->shipping);
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_two == $date_from){
                $leads = $leads->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $leads = $leads->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
            }
        }
        if($request->items){
            $items = $request->items;
        }else{
            $items = 10;
        }
        $leads= $leads->where('deleted_at',0)->paginate($items);

        return view('backend.leads.prepaid', compact('proo','products','productss','cities','leads','items'));
    }

    public function pickingorder($id)
    {
        $date = \Carbon\carbon::now()->toDateTimeString();
        $tomorrow = \Carbon\carbon::tomorrow()->toDateTimeString();
        $yesterday = \Carbon\carbon::yesterday()->toDateTimeString();

        $productss = LeadProduct::where('lead_products.id_product', $id)->where('date_delivred','!=',null)->where('livrison','=','unpacked')
        ->where('date_delivred','!=', Null)
        ->where('outstock',0)->get();
        // foreach($productss as $v_product){
        //     $lead = Lead::where('id',$v_product->id_lead)->where('warehouse_id', Auth::user()->warehouse_id)->where('status_confirmation','confirmed')->first();
        //     if(empty($lead->id)){
        //         LeadProduct::where('id', $v_product->id)->update(['date_delivred' => Null]);
        //     }
        // }
        
        // $products =     LeadProduct::join('leads','leads.id','lead_products.id_lead')->join('products','products.id','lead_products.id_product')->with('leadproduct')->where('status_confirmation','confirmed')->where('status_livrison','unpacked')->where('leads.id_product',$id)->where('leads.date_shipped','!=',Null)->select('leads.id as id','leads.n_lead as lead','leads.quantity','leads.status_payment','leads.created_at as created_at')->orderby('leads.created_at','desc')->get();
        $products = LeadProduct::join('products','products.id','lead_products.id_product')->join('leads','leads.id','lead_products.id_lead')->where('status_confirmation', 'confirmed')->where('status_livrison', 'unpacked')->where('lead_products.livrison', 'unpacked')->where('lead_products.outstock', 0)->where('lead_products.id_product', $id)->where('lead_products.date_delivred', '!=', Null)->select('leads.id as id', 'leads.n_lead as lead', 'lead_products.quantity', 'lead_products.outstock', 'leads.status_payment', 'leads.created_at as created_at', 'leads.date_shipped', 'leads.date_confirmed', 'last_status_change', 'leads.date_picking')->orderBy('leads.date_confirmed', 'desc')->get();
        // dd($products);
        //dd($products);
        return view('backend.proccess.onepick', compact('products','id'));
    }

    //pick list order

    public function picklist(Request $request)
    {
        //dd($request->all());
        $date = \Carbon\carbon::now()->toDateTimeString();
        $tomorrow = \Carbon\carbon::tomorrow()->toDateTimeString();
        $yesterday = \Carbon\Carbon::yesterday()->toDateTimeString();
        $orders = $request->ids;
        foreach($orders as $v_id){
            $leadProo = LeadProduct::wherein('id_lead',explode(",",$v_id))->where('livrison','unpacked')->where('outstock','=',0)->where('date_delivred','!=', Null)->get();
            //dd($leadProo);
            foreach($leadProo as $v_leadpro){
                $mapping = MappingStock::join('stocks','stocks.id','mapping_stocks.id_stock')->where('id_product',$v_leadpro->id_product)->first();
                $stock = Stock::where('id_product',$v_leadpro->id_product)->first();
                $leads = LeadProduct::where('id_lead',$v_leadpro->id_lead)->where('id_product',$v_leadpro->id_product)->where('date_delivred','!=', Null)->sum('quantity');
                //dd($v_leadpro);
                if($leads <= $mapping->quantity){
                    //tracking Stock
                    $tracking = new Tracking();
                    $tracking->id_mappingstock = $mapping->id;
                    $tracking->id_product = $mapping->id_product;
                    $tracking->id_tagier = $mapping->id_tagier;
                    $tracking->quantity =  $leads;
                    $tracking->save();

                    $quantity = $mapping->quantity - $leads;
                    $data3 = ['outstock' => true,'livrison' => 'picking process'];
                    LeadProduct::where('id_lead',$v_id)->where('id_product',$v_leadpro->id_product)->update($data3);
                    $data2 = ['quantity' => $mapping->quantity - $leads];
                    MappingStock::where('id_stock',$mapping->id_stock)->where('isactive',1)->update($data2);
                    $updatemapping = MappingStock::where('id_stock',$mapping->id_stock)->sum('quantity');
                    $data = ['qunatity' => $stock->qunatity - $leads];
                    Stock::where('id',$stock->id)->update($data);
                    $history = [
                        'type' => 'exite',
                        'id_product' => $stock->id_product,
                        'quantity' => $leads,
                        'note' => 'picking product',
                    ];
                    HistoryStock::insert($history);
                    $leadssAll = LeadProduct::where('id_lead',$v_id)->where('date_delivred','!=', Null)->count();
                    $leadsstrue = LeadProduct::where('id_lead',$v_id)->where('outstock',1)->where('date_delivred','!=', Null)->count();
                    if($leadssAll == $leadsstrue){
                        $data4 = [
                            'status_livrison' => "picking process",
                            'last_status_delivery'=> new DateTime(),
                            'deleted_at' => "0",
                        ];
                        Lead::where('id',$v_id)->update($data4);
                    }
                    $data5 = [
                        'country_id' => Auth::user()->country_id,
                        'id_lead' => $v_id,
                        'id_user' => Auth::user()->id,
                        'status' => 'picking process',
                        'comment' => 'picking process',
                        'date' => new DateTime(),
                    ];
                    HistoryStatu::insert($data5);
                }else{
                    return response()->json(['error'=>false]);
                }
            }
            
        }
        return response()->json(['success'=>true]);
    }

    public function outstock(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $v_id){
            $data = array();
            $data['status_confirmation'] = "outofstock";
            Lead::whereIn('id',explode(",",$v_id))->update($data);
            $data2 = array();
            $data2['outofstock'] = 1;
            LeadProduct::where('id_lead',explode(",",$v_id))->update($data2);
        }
        return response()->json(['success'=>true]);
    }

    public function deliveryman(Request $request)
    {
        $orders = Lead::with('product','country','cities')
        ->where('id_country', Auth::user()->country_id)
        ->where('status_confirmation','confirmed')
        ->where('livreur_id',Null)
        ->where('id_last_mille',Null)
        ->whereIn('status_livrison',['item packed','picking process','shipped','proseccing','proseccing']);

        $searchTerm = $request->input('search');
        if($searchTerm){
            $orders = $orders->where(function ($query) use ($searchTerm) {
                $query->where('products.name', 'like', '%' . $searchTerm . '%')
                ->orWhere('leads.n_lead', 'like', '%' . $searchTerm . '%')
                ->orWhere('leads.name', 'like', '%' . $searchTerm . '%')
                ->orWhere('products.price', 'like', '%' . $searchTerm . '%')
                ->orWhere('leads.phone', 'like', '%' . $searchTerm . '%');
            });
        }

        if(!empty($request->customer)){
            $orders = $order->where('leads.name',$request->customer);
        }
        if(!empty($request->ref)){
            $orders = $orders->where('leads.n_lead',$request->ref);
        }
        if(!empty($request->phone)){
            $orders = $orders->where('leads.phone',$request->phone);
        }

        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);

            if(sizeof($parts) < 2){
                
                Carbon::parse($dateString);
                $orders = $orders->whereDate('updated_at', Carbon::parse($parts[0])->format('Y-m-d'));
            }else{
                $date_from =Carbon::parse($parts[0])->format('Y-m-d');
                $date_two = Carbon::parse($parts[1])->format('Y-m-d');
                $orders = $orders->whereBetween('updated_at', [$date_from , $date_two]);
            }
            
        }
        $items = $request->items;
        if($items){
            $items = $request->items;
        }else{
            $items = 10;
        }
       
        $orders= $orders->orderby('id','desc')->paginate($items);
        $products = Product::where('id_country', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();
        $delivery = User::where('id_role',7)->where('country_id',Auth::user()->country_id)->get();
        return view('backend.orders.index', compact('orders','products','cities','items','delivery'));

    }
    
}
