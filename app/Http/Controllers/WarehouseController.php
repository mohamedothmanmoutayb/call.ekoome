<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Citie;
use App\Models\Import;
use App\Models\Tagier;
use App\Models\Product;
use App\Models\Countrie;
use App\Models\Tracking;
use GuzzleHttp\Client;
use App\Models\Error;
use App\Models\Province;
use App\Models\SkuLastmile;
use App\Models\ReturnStock;
use App\Models\LeadProduct;
use App\Models\MappingStock;
use App\Models\HistoryStock;
use App\Models\{HistoryStatu};
use App\Models\LastmilleIntegration;
use Illuminate\Pagination\Paginator;
use App\Models\{LabelParameter,Parameter,IslandZipcode,ShippingCompany};
use App\Models\HistoryTransferStock;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use DateTime;
use Auth;
use PDF;
use DB;
use Carbon\Carbon;
use ZipArchive;
use App\Http\Traits\EmailTracking;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportShipLead;
use App\Services\DigylogService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    use EmailTracking;
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

        // $order = Lead::where('tracking','!=', Null)
        //         ->where('tracking_return', Null)
        //         ->where('numEnvio','!=', Null)
        //         ->where('status_confirmation','confirmed')->whereIn('status_livrison',['rejected','returned'])->get();
        // //dd($order);
        // foreach($order as $v_order){
        //         $res = Http::withHeaders([
        //             'Content-Type' => 'application/json',
        //             'Authorization' => 'Basic ZWZ1bGZpbGxtZW50X3dzOnFTc0pH',
        //             ])->post(
        //                 'https://www.cexpr.es/wspsc/apiRestSeguimientoEnviosk8s/json/seguimientoEnvio',
        //                 [
        //                     'codigoCliente'=> '322120001',
        //                     'dato'=> $v_order->n_lead,
        //                     'idioma'=> 'ES'
        //                 ]
        //         )->collect()->toArray();
        //         if(!empty($res['bultoSeguimiento'][0]['codUnico'])){
        //             Lead::where('id',$v_order->id)->update(['tracking_return' => $res['bultoSeguimiento'][0]['codUnico']]);
        //         }
        //     }
        $products = Product::where('id_country',Auth::user()->country_id)->get();
        // $stocks = Stock::with(['import','Products' => function($query){
        //     $query->with('users');
        // },'MappingStock' => function($query){
        //     $query->with(['tagier' => function($query){
        //         $query = $query->with(['palet' => function($query){
        //             $query = $query->with('row');
        //         }]);
        //     }]);
        // }])->where('id_warehouse', Auth::user()->country_id)->orderby('id','desc')->paginate('10');
        $stocks = Product::with(['users','imports','stock' => function($query){
            $query = $query->with(['MappingStock' => function($query){
                $query->with(['tagier' => function($query){
                    $query = $query->with(['palet' => function($query){
                        $query = $query->with('row');
                    }]);
                }]);
            }]);
        }])->join('users', 'products.id_user', '=', 'users.id')
        ->where('checkimport',1)->where('id_country', Auth::user()->country_id);
        if($request->sku){
            $stocks = $stocks->where('sku',$request->sku);
        }
        if($request->product_name){
            $stocks = $stocks->where('products.name','like','%'.$request->product_name.'%');
        }
        if($request->seller_name){
            $stocks = $stocks->where('id_user',$request->seller_name);
        }
        if($request->items){
            $items = $request->items;
        }else{
            $items = 20;
        }
        $stocks = $stocks->orderBy('users.name', 'asc')
                            ->select('products.*')
                            ->paginate($items);

        $sellers = User::whereIn('id_role',['2','10'])->get();
        return view('backend.products.index', compact('products','stocks','sellers','items'));
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
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function upsells(Request $request,$id)
    {
        //$upsells = Upsel::where('id_product',$id)->get();
        $upsells = Product::with('upselles')->where('sku',$id)->get();
        //dd($upsells);

        return view('backend.products.upsells', compact('upsells'));
    }

    public function pos()
    {
        return view('backend.products.pos');
    }

    public function search(Request $request)
    {
        //$products[] = Product::where('name',$request->search)->get();
        /*$products[] = Stock::with(['products' => function($query,Request $request){
            $query->where('name',$request->search);
        }])->get();*/
        $products[] = Product::join('stocks','stocks.id_product','products.id')->where('bar_cod',$request->search)->get();
        $output[] = '';
        $i = 1;
        foreach($products as $pro){
        $output[] .=
            '<tr id="row'.$pro[0]['id'].'">'.
                '<td><img width="45" src="https://client.FULFILLEMENT.com/uploads/products/'.$pro[0]['image'].'"</td>'.
                '<td><input type="hidden" name="nameproduct" id="nameproduct[]" value="'.$pro[0]['id'].'"/><input type="hidden" name="nameproducts" id="nameproducts[]" value="'.$pro[0]['name'].'"/>'.$pro[0]['name'].'</td>'.
                '<td><input id="demo3" name="quantity" type="text" name="demo3"></td>'.
                '<td><a type="button" class="btn btn-info waves-effect" id="deleted" >Deleted</a></td>'.
            '</tr>';
            $i++;
        }
        return response($output);
    }

    public function searchtagier(Request $request)
    {
        $palet[] = Tagier::with(['palet' => function($query){
        }])->where('cod_bar',$request->search)->get();
        $output[] = '';
        $i = 1;
        foreach($palet as $pro){
        $output[] .=
            '<tr id="row'.$pro[0]['id'].'">'.
                '<td>'.$i.'</td>'.
                '<td><input type="hidden" name="tagier" value="'.$pro[0]['id'].'" />'.$pro[0]['name'].'</td>'.
            '</tr>';
            $i++;
        }
        return response($output);
    }

    public function emplacement(Request $request)
    {
        foreach($request->quantity as $item => $value){
            $data = [
                'id_stock' => $request->name[$item],
                'quantity' => $request->quantity[$item],
                'id_tagier' => $request->tagier,
            ];

            MappingStock::insert($data);
        }
        return response()->json(['success'=>true]);
    }

    public function return(Request $request)
    {

        $leads = LeadProduct::with(['leads' => function($query){
            $query = $query->with('leadbyvendor')->where('status_confirmation','confirmed');
        },'stock' => function($query){
            $query->where('isactive',1)->with(['MappingStock' => function($query){
                $query = $query->where('isactive',1)->with(['tagier' => function($query){
                    $query = $query->with(['palet' => function($query){
                        $query = $query->with('row');
                    }]);
                }]);
            }]);
        }])->where('outstock',1)->where('isreturn',1)->get()->groupby('id_product');

        $leads = ReturnStock::with(['products' => function($query){
            $query = $query->with(['stock' => function($query){
                $query->with(['MappingStock' => function($query){
                    $query = $query->with(['tagier' => function($query){
                        $query = $query->with(['palet' => function($query){
                            $query = $query->with('row');
                        }]);
                    }]);
                }]);
            }]);
        }]);
        if($request->product_name){
            $products = Product::where('name',$request->product_name)->first();
            $leads = $leads->where('id_product',$products->id);
        }
        if($request->sku){
            $products = Product::where('sku',$request->sku)->first();
            $leads = $leads->where('id_product',$products->id);
        }
        $leads = $leads->where('id_warehouse', Auth::user()->country_id)->get();
        //dd($leads);
        $sellers = User::where('id_role',2)->get();
        return view('backend.products.return', compact('leads','sellers'));
    }

    public function searchlead(Request $request)
    {
        $pro = Lead::where('n_lead',$request->search)->where('status_livrison','return')->first();
        //dd($leads);
        $output[] = '';
        $output[] .=
            '<tr id="row'.$pro->id.'">'.
                '<td><input type="hidden" name="nameproduct" id="nameproduct[]" value="'.$pro->n_lead.'"/>'.$pro->n_lead.'</td>'.
                '<td><a type="button" class="btn btn-info waves-effect" id="deleted" >Deleted</a></td>'.
            '</tr>';

        return response($output);
    }

    public function process()
    {

        return view('backend.products.process');
    }

    public function processtore(Request $request)
    {
        //dd($request->all());
        foreach($request->quantity as $item => $value){
            $last = MappingStock::where('id_tagier',$request->tagier)->where('id_stock',$request->name[$item])->first();
            $data = [
                'quantity' => $last->quantity - $request->quantity[$item],
            ];

            MappingStock::where('id_tagier',$last->id_tagier)->update($data);
        }
        return response()->json(['success'=>true]);
    }

    public function barcod(Request $request,$id)
    {
        $import = Import::where('id',$id)->first();
        // $print = Stock::with('products')->where('id_import',$import->id)->first();//dd($print->qunatity);
        $print = Stock::with('products')->where('id_product', $import->id_product)->first();
        $pro = Product::where('id',$print->id_product)->first();
        $user = User::where('id',$pro->id_user)->first();
        //dd($print);
        $cod = $print->bar_cod;
        $quantity = $print->qunatity;
        $customPaper = array(0,0,250,175);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled'=>true,'isRemoteEnabled'=>true])->loadView('backend.products.print-barcod', compact('print','cod','quantity','user'))->setPaper($customPaper,'portrait');
        return $pdf->download('barcod.pdf');
        return view('backend.products.print-barcod', compact('print','cod','quantity'));
    }

    public function productfordelivred()
    {
        $date = \Carbon\carbon::now()->toDateTimeString();
        $tomorrow = \Carbon\carbon::tomorrow()->toDateTimeString();
        $yesterday = \Carbon\carbon::yesterday()->toDateTimeString();
        if(Auth::user()->id_role == 6){
            $lead = Lead::where('picker_id',null)->where('status_confirmation','confirmed')
                        ->where('deleted_at',0)->where('status_livrison','unpacked');
                        if(Auth::user()->role_id == 3){
                            $lead = $lead->where('id_assigned',Auth::user()->id);
                        }
                        $lead = $lead->where('date_delivred', \Carbon\carbon::createFromFormat('Y-m-d H:i:s', $date)
                        ->format('Y-m-d'))->limit(2)->get();
            //dd($lead);
            foreach($lead as $v_lead){
                $data = [
                    'picker_id' => Auth::user()->id,
                ];
                Lead::where('id', $v_lead['id'])->update($data);
            }
            $leads = LeadProduct::with(['leads' => function($query){
                            $query->with('leadbyvendor')->where('deleted_at',0)
                            ->where('picker_id', Auth::user()->id)
                            // ->where('warehouse_id', Auth::user()->warehouse_id)
                            ->where('status_confirmation','confirmed')
                            ->where('status_livrison','unpacked')
                            ->whereIn('status_payment',['no paid','prepaid']);
                        },'stock' => function($query){
                            $query->where('isactive',1)->with(['MappingStock' => function($query){
                                $query->where('isactive',1)->with('tagier');
                            }]);
                        }])->where('outstock',0)->where('outofstock',0)
                        ->wherebetween('date_delivred',[ \Carbon\carbon::createFromFormat('Y-m-d H:i:s', $yesterday)
                        ->format('Y-m-d') ,  \Carbon\carbon::createFromFormat('Y-m-d H:i:s', $tomorrow)->format('Y-m-d') ])->get()->groupby('id_product');

        }else{
            $products = LeadProduct::join('leads','leads.id','lead_products.id_lead')->where('lead_products.date_delivred','!=',null)->where('lead_products.livrison','=','unpacked')
                                                                        ->where('lead_products.date_delivred','!=', Null)
                                                                        ->where('outstock',0);
                                                                        if(Auth::user()->role_id == 3){
                                                                            $products = $products->where('id_assigned',Auth::user()->id);
                                                                        }
                                                                        $products = $products->select('lead_products.id_product')
                                                                        ->groupby('lead_products.id_product')->orderby('lead_products.id_product','asc')->get();
            $products = json_decode($products);

            foreach($products as $v_pro){
                if(!empty($v_pro->id_product)){
                    $lead[] = Product::with('seller')->where('id',$v_pro->id_product)->where('id_country', Auth::user()->country_id)->first();
                }else{
                    $lead[] = null;
                }
            }
            if(empty($lead)){
                $lead[] = Null;
            }

            $lead = array_filter($lead);


            $lead = collect($lead)->sortBy(function ($product) {
                return optional($product->seller)->name;
            })->values()->all();
        }
        $sellers = User::where('id_role',2)->get();
        $products = Product::where('id_country', Auth::user()->country_id)->get();
        $today = now()->toDateString();

        return view('backend.proccess.product-for-shipped', compact('lead','sellers','products'));
    }

    public function searchprocess(Request $request)
    {
        $products = Product::join('stocks','stocks.id_product','products.id')->where('stocks.id',$request->search)->first();
        //dd($products);
        return response($products);
    }

    public function check(Request $request)
    {//dd($request->search);
        $tagier = Tagier::where('cod_bar',$request->search)->first();
        if($tagier == null) return response()->json(['error'=>false]);
        $stock = Stock::where('bar_cod',$request->stock)->where('qunatity','!=',0)->where('isactive',1)->first();
        $check = MappingStock::where('id_stock',$stock->id)->where('id_tagier',$tagier->id)->where('isactive',1)->where('quantity','!=',0)->first();
        //dd($check->id);
        if($check == null){
            return response()->json(['error'=>false]);
        }else{
            return response()->json(['success'=>true]);
        }
    }

    public function outstock(Request $request)
    {
        $date = \Carbon\carbon::now()->toDateTimeString();
        $tomorrow = \Carbon\carbon::tomorrow()->toDateTimeString();
        $yesterday = \Carbon\Carbon::yesterday()->toDateTimeString();
        $tagier = Tagier::where('cod_bar',$request->tagier)->first();
        $stock = Stock::where('bar_cod',$request->stock)->first();

        $leads = LeadProduct::join('products','products.id','lead_products.id_product')->with(['leads' => function($query){
            $query->with('leadbyvendor')->where('status_confirmation','confirmed')->where('status_livrison','unpacked')->whereIn('status_payment',['no paid','prepaid']);
        },'stock' => function($query){
            $query->with(['MappingStock' => function($query){
                $query->where('isactive',1)->with(['tagier' => function($query){
                    $query = $query->with(['palet' => function($query){
                        $query = $query->with('row');
                    }]);
                }]);
            }]);
        }])->where('products.id_country',Auth::user()->country_id)->where('livrison','=','unpacked')->where('outstock',0)->where('id_product','=',$stock->id_product)->where('date_delivred','=',\Carbon\carbon::now()->format('Y-m-d'))->where('isreturn',0)->where('outofstock',0)->sum('quantity');
        $mapping = MappingStock::where('id_stock',$stock->id)->where('isactive',1)->where('id_tagier',$tagier->id)->first();
        //dd($mapping->quantity);
        if($leads >= $request->quantity){
            //dd($mapping->quantity);
            if($request->quantity <= $mapping->quantity){
                $tracking = new Tracking();
                $tracking->id_mappingstock = $mapping->id;
                $tracking->id_product = $stock->id_product;
                $tracking->id_tagier = $tagier->id;
                $tracking->quantity =  $request->quantity;
                $tracking->save();
                $date = \Carbon\carbon::now()->toDateTimeString();
                $products = LeadProduct::where('id_product',$stock->id_product)->where('outstock',0)->where('livrison','=','unpacked')->where('date_delivred','=',\Carbon\carbon::now()->format('Y-m-d'))->where('isreturn',0)->get();
                $quantity = $request->quantity;
                foreach($products as $quant){
                    $quantit = MappingStock::where('id_stock',$stock->id)->where('isactive',1)->where('id_tagier',$tagier->id)->first();
                    $stockks = Stock::where('id',$quantit->id_stock)->where('bar_cod',$request->stock)->first();
                    $quantity = $quantity;
                    if($quantity != 0){
                        if($quant->quantity <= $quantit->quantity){
                            if($quant->quantity >= $quant->quantity){
                                $quantity = $quantity - $quant->quantity;
                                $data3 = [
                                    'outstock' => "1",
                                    'livrison' => "picking process",
                                ];
                                LeadProduct::where('id',$quant->id)->update($data3);

                                $data2 = [
                                    'quantity' => $quantit->quantity - $quant->quantity,
                                ];
                                MappingStock::where('id_stock',$stock->id)->where('isactive',1)->update($data2);
                                $updatemapping = MappingStock::where('id_stock',$stock->id)->sum('quantity');

                                $data = [
                                    'qunatity' => $stockks->qunatity - $quant->quantity ,
                                ];

                                Stock::where('id',$stock->id)->update($data);
                                $history = [
                                    'type' => 'exite',
                                    'id_product' => $stock->id_product,
                                    'quantity' => $quant->quantity,
                                    'note' => 'picking product',
                                ];
                                HistoryStock::insert($history);
                                $leadssAll = LeadProduct::where('id_lead',$quant->id_lead)->count();
                                $leadsstrue = LeadProduct::where('id_lead',$quant->id_lead)->where('outstock',1)->where('livrison','=','picking process')->where('isreturn',0)->where('outofstock',0)->count();
                                //dd($leadsstrue);
                                if($leadssAll == $leadsstrue){
                                    $data4 = [
                                        'status_livrison' => "picking process",
                                        'last_status_delivery'=> new DateTime(),
                                        'deleted_at' => "0",
                                    ];
                                    Lead::where('id',$quant->id_lead)->update($data4);
                                    $data5 = [
                                        'country_id' => Auth::user()->country_id,
                                        'id_lead' => $quant->id_lead,
                                        'id_user' => Auth::user()->id,
                                        'status' => 'picking process',
                                        'comment' => 'picking process',
                                        'date' => new DateTime(),
                                    ];

                                    HistoryStatu::insert($data5);
                                }
                            }

                        }

                    }else{
                        return response()->json(['success'=>true]);
                    }

                    $checkquantity = MappingStock::where('id_stock',$stockks->id)->where('isactive',1)->get();
                    foreach($checkquantity as $v_mapping){
                        if($v_mapping->quantity == 0 ){

                            $dta = [
                                'isactive' => 0,
                            ];

                            MappingStock::where('id',$v_mapping->id)->update($dta);
                            Stock::where('id',$stockks->id)->update($dta);
                        }
                    }
                }
                return response()->json(['success'=>true]);
            }elseif($mapping->quantity > 0){//dd('iiii');
                //tracking Stock
                $tracking = new Tracking();
                $tracking->id_mappingstock = $mapping->id;
                $tracking->id_product = $stock->id_product;
                $tracking->id_tagier = $tagier->id;
                $tracking->quantity =  $request->quantity;
                $tracking->save();
                $date = \Carbon\carbon::now()->toDateTimeString();
                $products = LeadProduct::where('id_product',$stock->id_product)->where('outstock',0)->where('livrison','=','unpacked')->where('date_delivred','=',\Carbon\carbon::now()->format('Y-m-d'))->where('isreturn',0)->get();
                $quantity = $mapping->quantity;
                //dd(MappingStock::where('id_stock',$stock->id)->where('isactive',1)->get());
                foreach($products as $quant){
                    $quantit = MappingStock::where('id_stock',$stock->id)->where('isactive',1)->where('id_tagier',$tagier->id)->first();
                    //dd($quantit->id);
                    if($quantit){
                       $stockks = Stock::where('id',$quantit->id_stock)->where('bar_cod',$request->stock)->first();
                        $quantity = $quantit->quantity;
                        //dd($quantity);
                        if($quantity != 0){
                            if($quantit->quantity >= $quant->quantity){
                                $quantity = $quantity - $quant->quantity;
                                //dd($quantity);
                                $data3 = [
                                    'outstock' => "1",
                                    'livrison' => "picking process",
                                ];
                                LeadProduct::where('id',$quant->id)->update($data3);

                                $data2 = [
                                    'quantity' => $quantit->quantity - $quant->quantity,
                                ];
                                MappingStock::where('id_stock',$stock->id)->where('isactive',1)->update($data2);
                                $updatemapping = MappingStock::where('id_stock',$stock->id)->sum('quantity');

                                $data = [
                                    'qunatity' => $stockks->qunatity - $quant->quantity ,
                                ];

                                Stock::where('id',$stock->id)->update($data);
                                $history = [
                                    'type' => 'exite',
                                    'id_product' => $stock->id_product,
                                    'quantity' => $quant->quantity,
                                    'note' => 'picking product',
                                ];
                                HistoryStock::insert($history);

                                $leadssAll = LeadProduct::where('id_lead',$quant->id_lead)->where('id_product',$stock->id_product)->count();
                                $leadsstrue = LeadProduct::where('id_lead',$quant->id_lead)->where('id_product',$stock->id_product)->where('outstock',1)->where('livrison','=','picking process')->where('isreturn',0)->count();
                                if($leadssAll == $leadsstrue){
                                    $data4 = [
                                        'status_livrison' => "picking process",
                                        'last_status_delivery' => new DateTime(),
                                        'deleted_at' => "0",
                                    ];
                                    Lead::where('id',$quant->id_lead)->update($data4);
                                    $data5 = [
                                        'country_id' => Auth::user()->country_id,
                                        'id_lead' => $quant->id_lead,
                                        'id_user' => Auth::user()->id,
                                        'status' => 'picking process',
                                        'comment' => 'picking process',
                                        'date' => new DateTime(),
                                    ];
                                    HistoryStatu::insert($data5);
                                }
                                $checkquantity = MappingStock::where('id_stock',$stockks->id)->where('isactive',1)->get();
                                foreach($checkquantity as $v_mapping){
                                    if($v_mapping->quantity == 0 ){
                                        $dta = ['isactive' => 0];
                                        MappingStock::where('id',$v_mapping->id)->update($dta);
                                        Stock::where('id',$stockks->id)->update($dta);
                                    }
                                }
                            }
                        }else{
                            return response()->json(['notedispo'=>false]);
                        }
                    }else{
                        return response()->json(['notedispo'=>false]);
                    }

                }
                return response()->json(['success'=>true]);
            }else{
                return response()->json(['notedispo'=>false]);
            }
        }else{
            return response()->json(['quantityerorr'=>false]);
        }
    }

    public function pack(Request $request)
    {
        $companies = ShippingCompany::get();
        $lastMile = array();
    
        foreach($companies as $company){
            $integration = LastmilleIntegration::where('id_country',Auth::user()->country_id)
                            ->where('id_lastmile',$company->id)->first();
            if($integration){
                if($company->countries) {
                    if(in_array(auth()->user()->country_id, $company->countries)){
                        $lastMile[] = $company;
                    }
                }
            }
        }
    
        // Initial empty response - we'll load via AJAX
        $leads = collect();
        $sellers = collect();
    
        // If we have a seller ID, get their last mile companies
        $sellerLastMiles = collect();
        if ($request->seller) {
            $sellerLastMiles = Lead::where('id_user', $request->seller)
                ->whereNotNull('id_last_mille')
                ->with('lastMileCompany')
                ->get()
                ->pluck('lastMileCompany')
                ->unique('id')
                ->filter();
        }
    
        return view('backend.proccess.pack', compact('leads','lastMile','sellers','sellerLastMiles'));
    }

    public function getSellers(Request $request)
{
    $sellers = User::whereHas('sellerLeads', function($query) use ($request) {
            $query->where('id_country', $request->country_id)
                //   ->where('warehouse_id', $request->warehouse_id)
                  ->where('livreur_id', Null)
                  ->where('packed', 0)
                  ->where('status_confirmation', 'confirmed')
                  ->whereIn('status_livrison', ['picking process', 'proseccing', 'picking proccess']);
        })
        ->withCount(['sellerLeads' => function($query) use ($request) {
            $query->where('id_country', $request->country_id)
                //   ->where('warehouse_id', $request->warehouse_id)
                  ->where('livreur_id', Null)
                  ->where('packed', 0)
                  ->where('status_confirmation', 'confirmed')
                  ->whereIn('status_livrison', ['picking process', 'proseccing', 'picking proccess']);
        }])
        ->orderBy('name')
        ->get();

    // dd($sellers);

    return response()->json([
        'sellers' => $sellers
    ]);
}

    public function getSellerLastMiles(Request $request)
    {
        $lastMiles = Lead::where('id_user', $request->seller_id)
            ->whereNotNull('id_last_mille')
            ->with('shippingcompany')
            ->get()
            ->pluck('shippingcompany')
            ->unique('id')
            ->filter()
            ->values();

           $counts = Lead::select('id_last_mille', \DB::raw('COUNT(*) as cnt'))
        ->where('id_user', $request->seller_id)
        ->whereNotNull('id_last_mille')
        ->whereNull('livreur_id')
        ->where('status_confirmation', 'confirmed')
        ->whereIn('status_livrison', ['picking process', 'proseccing', 'picking proccess'])
        ->groupBy('id_last_mille')
        ->pluck('cnt', 'id_last_mille'); 

        foreach ($lastMiles as $lm) {
            $lm->orders_count = (int) ($counts[$lm->id] ?? 0);
        }
    

        return response()->json([
            'lastMiles' => $lastMiles
        ]);
    }

    public function getOrders(Request $request)
    {
        $query = Lead::with(['leadproduct.product', 'shippingcompany'])
            ->where('id_user', $request->seller_id)
            ->where('id_last_mille','!=',Null)
            ->where('livreur_id', Null)
            ->where('status_confirmation', 'confirmed')
            ->whereIn('status_livrison', ['picking process', 'proseccing', 'picking proccess']);
        if ($request->last_mile_id) {
            $query->where('id_last_mille', $request->last_mile_id);
        }
        //dd($query->get());
        $orders = $query->orderBy('updated_at', 'asc')
            ->paginate(25);

        return response()->json([
            'orders' => $orders
        ]);
    }

    public function lastMileModal(Request $request)
    {
        $orderIds = $request->order_ids;
        $orders = Lead::whereIn('id', $orderIds)->get();
        
        $lastMileCompanies = ShippingCompany::whereIn('id', 
            $orders->pluck('id_last_mille')->filter()->unique()
        )->get();
        if ($lastMileCompanies->contains('name', 'DIGYLOG')) {            
            return view('backend.proccess.partials.digylog_modal', [
                'orderIds' => $orderIds,
                'orders' => $orders,
                'lastMileCompanies' => $lastMileCompanies
            ]);
        }

        return view('backend.proccess.partials.last_mile_modal', [
            'orders' => $orders,
            'lastMileCompanies' => $lastMileCompanies
        ]);
    }

    public function digylogOrder(Request $request)
    {
        $orderIds = explode(',', $request->order_ids);
        $orders = Lead::with(['leadproduct.product', 'city'])
                    ->whereIn('id', $orderIds)
                    ->get();
        
        $lastMile = LastmilleIntegration::find($request->last_mile_id);
        $digylogService = new DigylogService($lastMile->api_key);
        
        $ordersData = [];
        foreach ($orders as $order) {
            $products = [];
            foreach ($order->leadproduct as $product) {
                $products[] = [
                    'designation' => $product->product[0]->name,
                    'quantity' => $product->quantity
                ];
            }
            
            $ordersData[] = [
                'num' => $order->n_lead,
                'type' => 1, 
                'name' => $order->name,
                'phone' => $order->phone,
                'address' => $order->address,
                'city' => $order->city->name,
                'price' => $order->lead_value,
                'refs' => $products,
                'port' => (int)$request->port,
                'openproduct' => (int)$request->openproduct,
                'note' => 'Order from your platform'
            ];
        }
        
        $orderPayload = [
            'network' => (int)$request->network,
            'store' => $request->store,
            'mode' => (int)$request->mode,
            'status' => (int)$request->status,
            'orders' => $ordersData
        ];
        
        $response = $digylogService->createOrder($orderPayload);
        
        if ($response['success'] ?? false) {
            foreach ($orders as $order) {
                $tracking = $response['data']['tracking'] ?? null;
                if ($tracking) {
                    $order->update([
                        'id_last_mille' => $lastMile->id_lastmile,
                        'tracking' => $tracking,
                        'status_livrison' => 'processing'
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Orders successfully submitted to Digylog',
                'data' => $response['data'] ?? null
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => $response['message'] ?? 'Error submitting to Digylog',
            'errors' => $response['errors'] ?? null
        ], 400);
    }

    public function transfer(Request $request)
    {
        //dd($request->id);
        $last = MappingStock::where('id_stock',$request->id)->first();
        $tagiers = Tagier::where('id_warehouse', Auth::user()->country_id)->where('cod_bar',$request->tagier)->first();
        $shelf = Tagier::where('id_warehouse', Auth::user()->country_id)->where('cod_bar',$request->shelf)->first();
        //dd($last);
        if(MappingStock::where('id_stock',$request->id)->where('id_tagier',$tagiers->id)->first()){
            //MappingStock::where('id_stock',$request->id)->where('id_tagier',$shelf->id)->delete();
                $quant = MappingStock::where('id_stock',$request->id)->where('id_tagier',$tagiers->id)->first();
                $data = [
                    'quantity' => $quant->quantity + $request->quantity,
                ];
                MappingStock::where('id_stock',$request->id)->where('id_tagier',$tagiers->id)->update($dat);

        }else{
            if($last->quantity == $request->quantity){
                $data = [
                    'quantity' => $request->quantity,
                    'id_tagier' => $tagiers->id,
                ];
                MappingStock::where('id_stock',$request->id)->where('id_tagier',$shelf->id)->update($data);
            }else{
                $data = [
                    'id_stock' => $request->id,
                    'quantity' => $request->quantity,
                    'id_tagier' => $tagiers->id,
                ];
                $lastquantity = MappingStock::where('id_stock',$request->id)->where('id_tagier',$shelf->id)->first();
                $data2 = array();
                $data2['quantity'] = $lastquantity->quantity - $request->quantity;
                MappingStock::where('id_stock',$request->id)->where('id_tagier',$shelf->id)->update($data2);
                MappingStock::insert($data);
            }
        }

        $data2 = [
            'id_user' => Auth::user()->id,
            'id_stock' => $request->id,
            'last_tagier' => $last->id_tagier,
            'new_tagier' => $tagiers->id,
            'quantity' => $request->quantity,
            'reson' => $request->reson,
        ];

        HistoryTransferStock::insert($data2);
        return response()->json(['success'=>true]);

    }

    public function returnorder(Request $request)
    {//dd($request->stock);
        $stockspro = Stock::where('bar_cod',$request->stock)->first();

        $mapping = MappingStock::where('id_stock',$stockspro->id)->where('isactive',1)->where('quantity','!=',0)->first();
        //dd($request->all());
        $return = ReturnStock::where('id_product',$stockspro->id_product)->first();
        if($return->quantity >= $request->quantity){
            if($mapping){
                $data = array();
                $data['quantity'] = $mapping->quantity + $request->quantity;
                $data['isactive'] = 1;
                MappingStock::where('id',$mapping->id)->update($data);
                $data2 = array();
                $data2['qunatity'] = $stockspro->qunatity + $request->quantity;
                $data2['isactive'] = 1;
                Stock::where('id',$stockspro->id)->update($data2);
            }else{
                $mapping2 = MappingStock::where('id_stock',$stockspro->id)->where('isactive',1)->first();
                if($mapping2){
                    $data = array();
                    $data['quantity'] = $mapping2->quantity + $request->quantity;
                    $data['isactive'] = 1;
                    MappingStock::where('id',$mapping2->id)->update($data);
                    $data2 = array();
                    $data2['qunatity'] = $stockspro->qunatity + $request->quantity;
                    $data2['isactive'] = 1;
                    Stock::where('id',$stockspro->id)->update($data2);
                }else{
                    $mapping3 = MappingStock::where('id_stock',$stockspro->id)->first();
                    $data = array();
                    $data['quantity'] = $mapping3->quantity + $request->quantity;
                    $data['isactive'] = 1;
                    MappingStock::where('id',$mapping3->id)->update($data);
                    $data2 = array();
                    $data2['qunatity'] = $stockspro->qunatity + $request->quantity;
                    $data2['isactive'] = 1;
                    Stock::where('id',$stockspro->id)->update($data2);
                }

            }
            $data3 = array();
            $data3['quantity'] = $return->quantity - $request->quantity;
            ReturnStock::where('id',$return->id)->update($data3);
            return response()->json(['success'=>true]);

        }else{
            return response()->json(['error'=>false]);
        }

    }

    public function sendfordelovred(Request $request)
    {
        //$leads = Lead::where('status_livrison','')
        $ids = $request->ids;
        foreach($ids as $v_id){
            $data = [
                'status_livrison' => 'ready to ship',
                'last_status_delivery'=> new DateTime(),
            ];
            Lead::whereIn('id',explode(",",$v_id))->where('status_confirmation','confirmed')->update($data);
            $data2 = [
                'country_id' => Auth::user()->country_id,
                'id_lead' => $v_id,
                'id_user' => Auth::user()->id,
                'status' => 'ready to ship',
                'comment' => 'ready to ship',
                'date' => new DateTime(),
            ];

            HistoryStatu::insert($data2);
        }
        return response()->json(['success'=>true]);
    }

    public function listpro(Request $request)
    {
        $lead = LeadProduct::with('products')->where('id_lead', $request->value)->first();

        $stock = Stock::where('id_product', $lead->products->id)->first();
        //dd($lead);
        $histories = HistoryStatu::with('lead')->where('id_lead',$request->value)->get();//dd($histories);
        $output = '';

            $output .=
            '<tr>
                <td>';
                        $output .='
                   '.$lead->products->name. '

                </td>
                <td> <img src="';

                    $output .='
                    '. $lead->products->image. '
                    " width=45/></td>';

                $output .='
                <td>
                    '. $stock->qunatity .'
                </td>
            </tr>
            ';

        // foreach($histories as $v_lead){
        //     $output .=
        //     '<tr>
        //         <td>';
        //                 $output .='
        //            '.$v_lead['lead']->n_lead.'

        //         </td>
        //         <td>';

        //             $output .='
        //             '. $v_lead->status.'
        //             </td>';

        //         $output .='
        //         <td>
        //             '. $v_lead->comment.'
        //         </td>
        //     </tr>
        //     ';
        // }

        return response($output);
    }

    public function ship(Request $request)
    {
        $date = \Carbon\carbon::now()->toDateTimeString();
        $leads = Lead::with(['livreur'],['leadproduct' => function($query){
            $query->with('product');
        }],['cities'])->where('id_country',Auth::user()->country_id)->where('status_confirmation','confirmed')->where('packed',1)->where('livreur_id',Null)->whereIn('status_livrison',['item packed','shipped','proseccing','in transit','in delivery','incident']);
        if($request->ref){
            $leads = $leads->where('n_lead', $request->ref);
        }
        if($request->customer){
            $leads = $leads->where('name',$request->customer);
        }
        if($request->phone1){
            $leads = $leads->where('phone',$request->phone1);
        }
        if($request->city){
            $leads = $leads->where('id_city',$request->city);
        }
        if($request->id_product){
            $leads = $leads->where('id_product', $request->id_product);
        }
        if($request->shipping){
            $leads = $leads->whereIn('status_livrison', $request->shipping);
        }
        if($request->id_prod){
            $leads = $leads->where('id_product', $request->id_prod);
        }
        if($request->tracking){
            $leads = $leads->where('tracking','like','%'.$request->tracking.'%');
        }
        $leads = $leads->orderby('updated_at');

        $items = $request->items;
        if($items){
            $items = $request->items;
        }else{
            $items = 20;
        }
        if(!empty($request->date)){
            $parts = explode(' - ' , $request->date);
            $date_from = $parts[0];
            $date_two = $parts[1];
            if($date_from == $date_two){
                $leads = $leads->whereDate('updated_at', $date_from );
            }else{
                $leads = $leads->whereBetween('updated_at', [$date_from , $date_two]);
            }

        }
        $leads = $leads->paginate($items);
        //dd($leads);
        $products = Product::where('id_country', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country',Auth::user()->country_id)->get();
        $delivery = User::where('id_role',7)->where('country_id', Auth::user()->country_id)->get();
        return view('backend.proccess.ship', compact('leads','products','cities','delivery','items'));
    }

    public function shipExport(Request $request){
            $leads = explode(",",$request->list_ids);
            $leads = new ExportShipLead($leads);
            return Excel::download($leads, 'Leads.xlsx');
    }

    public function printlable(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $v_id){
            $leads = Lead::with('leadproduct')->where('id',explode(",",$v_id))->get();
        }
        return response()->json(['leads' => $leads]);
    }

    public function printproduct(Request $request,$array)
    {
        $ids = json_decode($array);
        foreach($ids as $v_id){
            $leads[] = Lead::with(['leadproduct' => function($query){
                $query = $query->with('product');
            }],['cities'],['leadbyvendor'],['zones'])->where('status_confirmation','confirmed')->where('status_livrison','picking process')->where('id',explode(",",$v_id))->get();
        }//dd($leads);
            $customPaper = array(0,0,370,330);
            $pdf = PDF::loadView('backend.products.label', compact('leads'))->setPaper($customPaper,'portrait');
            return $pdf->stream();
            return $pdf->download('leads.pdf');
            return view('backend.leads.label', compact('leads'));

    }

    public function printproducttest(Request $request)
    {
            $leads[] = Lead::with(['leadproduct' => function($query){
                $query = $query->with('product');
            }],['cities'],['leadbyvendor'])->where('id',5099)->get();
            $customPaper = array(0,0,370,330);
            $pdf = PDF::loadView('backend.products.label', compact('leads'))->setPaper($customPaper,'portrait');
            return $pdf->stream();
            return $pdf->download('leads.pdf');
            return view('backend.leads.label', compact('leads'));

    }

    public function newmapping(Request $request)
    {
        $summapping = MappingStock::where('id_stock',$request->id)->sum('quantity_map');
        $stock = Stock::where('id',$request->id)->first();

        $quant = $stock->quantity_accept - $summapping;
        if($quant){
            $tagierid = Tagier::where('cod_bar', $request->tagier)->first();

            $searchstock = Stock::where('id_product',$stock->id_product)->get();

            $mapping = MappingStock::where('id_stock',$stock->id)->where('id_tagier',$tagierid->id)->first();
            if($mapping){//dd($quant);
                $data = [
                    'id_stock' => $request->id,
                    'quantity_map' => $mapping->quantity_map + $quant,
                    'quantity' => $mapping->quantity + $quant,
                    'isactive' => '1',
                ];
                MappingStock::where('id',$mapping->id)->update($data);
            }else{
                $data = [
                    'id_stock' => $request->id,
                    'quantity_map' => $quant,
                    'quantity' => $quant,
                    'id_tagier' => $tagierid->id,
                    'isactive' => '1',
                ];
                MappingStock::insert($data);
            }

            return response()->json(['success'=>true]);
        }else{
            return response()->json(['quantity'=>false]);
        }

    }

    //get shelf id

    public function stockid($id)
    {
        $stocks = MappingStock::with('tagier')->where('id_stock',$id)->get();
        $stocks = json_decode($stocks);
        return response()->json($stocks);
    }

    //scan order send to ship
    public function sendordertoship(Request $request)
    {
        $date = \Carbon\carbon::now()->toDateTimeString();
        $leads = Lead::where('tracking',$request->bar)->where('status_confirmation','confirmed')->first();
        if(!empty($leads->id)){
            $data = array();
            $data['status_livrison'] = 'ready to ship';
            $data['last_status_delivery'] = new DateTime();
            Lead::where('tracking',$request->search)->Update($data);

            $lead = Lead::where('tracking',$request->bar)->first();
            if(!empty($lead->id)){
                $data2 = [
                    'country_id' => Auth::user()->country_id,
                    'id_lead' => $lead->id,
                    'id_user' => Auth::user()->id,
                    'status' => 'ready to ship',
                    'comment' => 'ready to ship',
                    'date' => new DateTime(),
                ];
                HistoryStatu::insert($data2);
                return response()->json(['success'=>true]);
            }else{
                return response()->json(['error'=>false]);
            }

        }else{
            return response()->json(['error'=>false]);
        }

    }

    //manual order send to ship
    public function sendforshipped(Request $request)
    {
        // $date = \Carbon\carbon::now()->toDateTimeString();
        $leads = Lead::with(['leadproduct' => function($query){
            $query->with('product');
        }])->where('tracking',$request->search)
            ->where('status_confirmation','confirmed')
            ->whereIn('status_livrison',['picking process','proseccing'])
            ->first();
        if(!empty($leads->id)){
            $data = array();
            $data['status_livrison'] = 'item packed';
            $data['packed'] = '1';
            $data['last_status_delivery'] = new DateTime();
            Lead::where('tracking',$request->search)->Update($data);
            $lead = Lead::where('tracking',$request->search)->first();
            if(!empty($lead->id)){
                $data2 = [
                    'country_id' => Auth::user()->country_id,
                    'id_lead' => $lead->id,
                    'id_user' => Auth::user()->id,
                    'status' => 'item packed',
                    'comment' => 'item packed',
                    'date' => new DateTime(),
                ];
                HistoryStatu::insert($data2);

                return response()->json(['success'=>true]);
            }else{
                return response()->json(['error'=>false]);
            }
        }else{
            return response()->json(['error'=>false]);
        }
    }

    public function multisendforshipped(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $v_id){
            ;
            $lead = Lead::where('id',$v_id)
                        ->where('status_confirmation','confirmed')
                        ->whereIn('status_livrison',['picking process','proseccing'])
                        ->where('tracking','!=',null)
                        ->first();

            if(!empty($lead->id)){

                $data = array();
                $data['status_livrison'] = 'item packed';
                $data['packed'] = '1';
                $data['last_status_delivery'] = new DateTime();
                $lead->status_livrison = 'item packed';
                $lead->packed = '1';
                $lead->last_status_delivery = new DateTime();
                $lead->save();
                $data2 = [
                    'country_id' => Auth::user()->country_id,
                    'id_lead' => $lead->id,
                    'id_user' => Auth::user()->id,
                    'status' => 'item packed',
                    'comment' => 'item packed',
                    'date' => new DateTime(),
                ];
                HistoryStatu::insert($data2);
            }
        }
        return response()->json(['success'=>true]);
    }

    //product order
    public function getproductorder(Request $request)
    {//dd($request->value);
        $products = LeadProduct::join('products','products.id','lead_products.id_product')->where('lead_products.id_lead',$request->value)->select('products.name As name','products.id')->get();
        //dd($products);
        $output = "";
        $output .='
        <div class="col-lg-12">
            <div class="text-center">
                <div class="form-group">
                    <div class="col-md-12 col-sm-12 my-2">
                        <input type="hidden" class="form-control" id="lead_product" value="'. $request->value.'">
                        <select class="form-control" id="product_id" name="product_id" >
                            <option value="">Select Product</option>';
                            foreach($products as $v_product){
                                $output .='
                                    <option value="'. $v_product->id .'">'. $v_product->name .'</option>
                                ';
                            }$output .='
                        </select>
                    </div>
                    <div class="col-md-12 col-sm-12 my-2">
                        <input type="number" class="form-control" id="quantity_product" placeholder="Quantity to be you want Canceled">
                    </div>
                    <div class="col-md-12 col-sm-12 my-2">
                        <input type="number" class="form-control" id="price_product" placeholder="Price Total">
                    </div>
                </div>
            </div>
        </div>
        ';
        return response($output);
    }

    public function updatedlead(Request $request)
    {
        $lead = Lead::where('id',$request->lead)->where('status_confirmation','confirmed')->first();
        if($lead->id_product == $request->product){
            $leadproduct = LeadProduct::where('id_lead',$lead->id)->where('id_product','!=', $request->product)->first();
            //dd($leadproduct->id_product);
            if(!empty($leadproduct->id_product)){
                $data = [
                    'quantity' => $lead->quantity - $request->quantity,
                    'id_product' => $leadproduct->id_product,
                    'lead_value' => $request->price,
                ];
                Lead::where('id',$lead->id)->where('status_confirmation','confirmed')->update($data);
                $dataa = [
                    'isupsell' => '0',
                    'lead_value' => $request->price,
                ];
                LeadProduct::where('id_lead',$lead->id)->where('id_product','=', $leadproduct->id_product)->update($dataa);

                LeadProduct::where('id_lead',$lead->id)->where('id_product',$request->product)->delete();
            }else{
                if(LeadProduct::where('id_lead',$lead->id)->count() > 1){
                    $data = [
                        'quantity' => $lead->quantity - $request->quantity,
                        'lead_value' => $request->price,
                    ];
                    Lead::where('id',$lead->id)->where('status_confirmation','confirmed')->update($data);
                    $leaddds = LeadProduct::where('id_lead',$lead->id)->first();
                    $data2 = [
                        'isupsell' => '0' ,
                        'lead_value' => $request->price,
                    ];
                    LeadProduct::where('id_lead',$lead->id)->where('isupsell',0)->update($data2);
                    LeadProduct::where('id_lead',$lead->id)->where('isupsell',1)->delete();
                }else{
                    $data = [
                        'quantity' => $lead->quantity - $request->quantity,
                        'lead_value' => $request->price,
                    ];
                    Lead::where('id',$lead->id)->where('status_confirmation','confirmed')->update($data);
                    $leaddds = LeadProduct::where('id_lead',$lead->id)->first();
                    $data2 = [
                        'isupsell' => '0' ,
                        'quantity' => $leaddds->quantity - $request->quantity,
                        'lead_value' => $request->price,
                    ];
                    LeadProduct::where('id_lead',$lead->id)->where('isupsell',0)->update($data2);
                    LeadProduct::where('id_lead',$lead->id)->where('isupsell',1)->delete();
                }
            }


            $tracking = Tracking::where('id_product',$request->product)->orderby('id','desc')->first();
            $mapping = MappingStock::where('id',$tracking->id_mappingstock)->where('id_tagier',$tracking->id_tagier)->first();
            $MappingStock = array();
            $MappingStock['quantity'] = $mapping->quantity + $request->quantity;
            MappingStock::where('id',$mapping->id)->update($MappingStock);
            $stocks = Stock::where('id',$mapping->id_stock)->first();
            $stock = array();
            $stock['qunatity'] = $stocks->qunatity + $request->quantity;
            Stock::where('id',$stocks->id)->update($stock);
        }else{
            LeadProduct::where('id_lead',$lead->id)->where('id_product',$request->product)->delete();

            $tracking = Tracking::where('id_product',$request->product)->orderby('id','desc')->first();
            $mapping = MappingStock::where('id',$tracking->id_mappingstock)->where('id_tagier',$tracking->id_tagier)->first();
            $MappingStock = array();
            $MappingStock['quantity'] = $mapping->quantity + $request->quantity;
            MappingStock::where('id',$mapping->id)->update($MappingStock);
            $stocks = Stock::where('id',$mapping->id_stock)->first();
            $stock = array();
            $stock['qunatity'] = $stocks->qunatity + $request->quantity;
            Stock::where('id',$stocks->id)->update($stock);
        }
        return response()->json(['success'=>true]);
    }

    public function assigned(Request $request)
    {
        //dd($request->bar);
        $lead = Lead::where('tracking',$request->search)->where('status_confirmation','confirmed')->where('livreur_id',null)->orwhere('livreur_id','')->first();
        if($lead->status_livrison == 'ready to ship'){
            $dat = array();
            $dat['livreur_id'] = $request->search;
            $dat['status_livrison'] = 'shipped';
            $data['last_status_delivery'] = new DateTime();
            Lead::where('tracking',$request->search)->update($dat);
            $data = array();
            $data['country_id'] = Auth::user()->country_id;
            $data['id_lead'] = $lead->id;
            $data['id_user'] = Auth::user()->id;
            $data['id_delivery'] = $request->search;
            $data['status'] = "shipped";
            $data['comment'] = "shipped";
            $data['date'] = new DateTime();

            HistoryStatu::insert($data);
            return response()->json(['success'=>true]);
        }elseif($lead->status_livrison != 'ready to ship'){
            $dat = array();
            $dat['livreur_id'] = $request->search;
            $dat['status_livrison'] = 'shipped';
            $data['last_status_delivery'] = new DateTime();
            Lead::where('tracking',$request->search)->update($dat);

            $data = array();
            $data['country_id'] = Auth::user()->country_id;
            $data['id_lead'] = $lead->id;
            $data['id_user'] = Auth::user()->id;
            $data['id_delivery'] = $request->search;
            $data['status'] = "shipped";
            $data['comment'] = "shipped";
            $data['date'] = new DateTime();
            HistoryStatu::insert($data);

            return response()->json(['success'=>true]);
        }else{
            $lead = Lead::where('tracking',$request->search)->first();
            $user = User::where('id',$lead->id)->first();
            $user = json_decode($user);
            return response()->json(['error'=>false,$user]);
        }
    }

    public function returninpack()
    {
        $lead = Lead::where('tracking',$request->search)->where('status_confirmation','confirmed')->where('livreur_id','=', Null)->first();
        //dd($lead);
        if(!empty($lead->id)){
            Lead::where('id',$lead->id)->update(['livreur_id'=>'','status_livrison'=>'return','last_status_delivery' => new DateTime()]);
            //$lead = Lead::where('id',$lead->id)->first();
            LeadProduct::where('id_lead',$lead->id)->update(['isreturn'=>'1']);
            $products = LeadProduct::where('id_lead',$lead->id)->get();

            $data2 = array();
            $data2['country_id'] = Auth::user()->country_id;
            $data2['id_lead'] = $lead->id;
            $data2['id_user'] = Auth::user()->id;
            if($lead->livreur_id){
                $data2['id_delivery'] = $lead->livreur_id;
            }
            $data2['status'] = "return";
            $data2['comment'] = "return";
            $data2['date'] = new DateTime();

            HistoryStatu::insert($data2);
            //dd($products);
            foreach($products as $v_product){
                $lastreturn = ReturnStock::where('id_product',$v_product->id_product)->first();
                $data = array();
                $data['id_product'] = $v_product->id_product;
                if($lastreturn){
                    $data['quantity'] = $v_product->quantity + $lastreturn->quantity;
                    ReturnStock::where('id',$lastreturn->id)->update($data);
                }else{
                    $data['quantity'] = $v_product->quantity;
                    $data['id_warehouse'] = Auth::user()->country_id;
                    ReturnStock::insert($data);
                }
            }
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>false]);
        }
    }

    public function springapiorder(Request $request)
    {
        $list_id = array();
        $path = explode(",", $request->list_ids);
        $ids = array_merge($list_id, $path);
        $labels = LabelParameter::where('id_country', Auth::user()->id_country)->first();
        if(!empty($labels->id)){
            $tele = $labels->telephone;
            $zipcode = $labels->zipcod;
            $cit = $labels->city;
            $addr = $labels->address;
        }else{
            $tele = "0666666666";
            $zipcode = "18110";
            $cit = "LAS GABIAS";
            $addr = "P DEL CHARCON SN";
        }
        if($request->type == "CreateOrder"){
            //dd($request->all());z
            $headers = [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ];
            foreach($ids as $v_id){
                $rend = mt_rand(10000000 , 99999999);
                //dd($rend);
                $i = 0;
                $lastid = Lead::with(['products','cities'])->where('tracking',Null)->where('id',explode(",",$v_id))->first();//dd($lastid);
                $country = Countrie::where('id',$lastid->id_country)->first();
                if($country->flag != "es"){
                    $zipcodna = "";
                    $zipcodIn = $lastid->zipcod;
                }else{
                    $zipcodna = $lastid->zipcod;
                    $zipcodIn = "";
                }
                $seller = User::where('id',$lastid->id_user)->first();
                if(!empty($lastid['cities'][0]->name)){
                    $city = $lastid['cities'][0]->name;
                }else{
                    $city = $lastid->city;
                }//dd($lastid['products']->name);
                $store = Store::where('id',$lastid['products']->id_store)->first();
                if($lastid->ispaidapp == "1"){
                    $leadvalue = 0;
                }else{
                    $leadvalue = $lastid->lead_value;
                }
                $department_code;
                $vendor;
                if($seller->id_role == '11' ){
                    $vendor = User::where('id',$lastid['products']->id_user)->first();
                    $department_code = $vendor->departement;
                    $vendor = $vendor->name;
                }
                    $res = Http::withHeaders([
                        'Content-Type' => 'application/json',
                        ])->post(
                            'https://mtapi.net/',
                            [
                                "Apikey"=> "d0ffa35461437a42",
                                "Command"=> "OrderShipment",
                                "Shipment"=> [
                                    "LabelFormat"=> "PDF",
                                    "ShipperReference"=> $lastid->n_lead,
                                    "DisplayId"=> "123450000",
                                    "InvoiceNumber"=> $lastid->id,
                                    "Service"=> "SIGN",
                                    "Weight"=> "0.85",
                                    "WeightUnit"=> "kg",
                                    "Length"=> "20",
                                    "Width"=> "10",
                                    "Height"=> "10",
                                    "DimUnit"=> "cm",
                                    "Value"=> $leadvalue,
                                    "Currency"=> "EUR",
                                    "CustomsDuty"=> "DDU",
                                    "Description"=> "CD",
                                    "DeclarationType"=> "SaleOfGoods",
                                    "DangerousGoods"=> "N",
                                    "ConsignorAddress"=> [
                                    "Name"=> $vendor ?? $store->name,
                                    "Company"=> $vendor ?? $store->name
                                    ],
                                    "ConsigneeAddress"=> [
                                    "Name"=> $lastid->name,
                                    "Company"=> "",
                                    "AddressLine1"=> $lastid->address,
                                    "AddressLine2"=> "",
                                    "AddressLine3"=> "",
                                    "City"=> $city,
                                    "State"=> $lastid->province,
                                    "Zip"=> $lastid->zipcod,
                                    "Country"=> $country->flag,
                                    "Phone"=> $lastid->phone,
                                    "Email"=> $lastid->email,
                                    "Vat"=> ""
                                    ],
                                    "Products"=> [
                                        [
                                            "Description"=> $lastid['products']->name,
                                            "Sku"=> $lastid['products']->sku,
                                            "HsCode"=> "852349",
                                            "OriginCountry"=> $country->flag,
                                            "ImgUrl"=> $lastid['products']->image,
                                            "Quantity"=> $lastid->quantity,
                                            "Value"=> $lastid->lead_value,
                                            "Weight"=> $lastid['products']->weight,
                                            "PreferentialOriginTag"=> "N"
                                        ]
                                    ],
                                ],
                            ],
                    )->collect()->toArray();

                    if(!empty($res['Shipment'])){
                        $count = count($res['Shipment']);
                        $pdf = new \Clegginabox\PDFMerger\PDFMerger;
                        $i= 0;
                        if($res['Shipment'][$i]['LabelImage']){
                            $bin = base64_decode($res['Shipment'][$i]['LabelImage']);
                            \Storage::disk('public')->put(''.$lastid->n_lead.'A'.$i.'.pdf',base64_decode($bin));
                            $waybill1 = 'https://warehouse.ecomfulfilment.eu/storage/app/public/'.$lastid->n_lead.'A'.$i.'.pdf';
                            $waybill = 'https://warehouse.ecomfulfilment.eu/storage/app/public/'.$lastid->n_lead.'.pdf';
                            Lead::where('id',explode(",",$lastid->id))->update(['tracking' => $res['Shipment'][0]['TrackingNumber']]);
                            Lead::where('id',explode(",",$lastid->id))->update(['waybille' => $waybill1]);
                            //dd($leads[$i]);
                            $file = storage_path('app/public/'.$lastid->n_lead.'A'.$i.'.pdf');
                            $pdf->addPDF($file, 'all');
                        }
                    }else{
                        $data = array();
                        $data['id_country'] = Auth::user()->country_id;
                        $data['id_lead'] = $lastid->id;
                        $data['error'] = $res['mensajeRetorno'];
                        $data['code'] = $res['codigoRetorno'];

                        Error::insert($data);
                    }
                    //dd($res);
            }
            return response()->json(['success'=>true]);
        }
        if($request->type == "WaybillOrder"){
            //dd(storage_path());
            foreach($ids as $v_id){
                $lastid = Lead::where('id',explode(",",$v_id))->first();
                if(!empty($lastid->waybille)){
                    $pdf = $lastid->waybille;
                    response()->download(storage_path('app/public/' . $lastid->n_lead .'.pdf'));
                }
            }
        }

    }

    public function lastdownload($leads)
    {
        $leads = json_decode($leads);
        $count = count($leads);
        $pdf = new \Clegginabox\PDFMerger\PDFMerger;
        for($i = 0 ; $i < $count ; $i++){
            $lead = Lead::where('id',$leads[$i])->first();
            if(!empty($lead->waybille)){
                for($m = 0 ; $m < $lead->bulto ; $m++){
                    if($lead->shipping_company == "brt"){
                        $m = 0;
                    }
                    $file = storage_path('app/public/'.$lead->n_lead.'A'.$m.'.pdf');
                    $pdf->addPDF($file, 'all');
                }
            }
        }
        $fileName = time().'.pdf';
        $pdf->merge();
        $pdf->save(public_path($fileName));
        return  \Response::download($file);
    }

    //tigerline

    public function tigerline(Request $request)
    {
        $lastmille = LastmilleIntegration::where('id',$request->bussines)->where('id_country',Auth::user()->country_id)->first();
        
        $list_id = array();
        $path = explode(",", $request->list_ids);
        $ids = array_merge($list_id, $path);

        
        // $ids = explode(",", $request->list_ids);

        // Log::info($ids);
    
        
        foreach($ids as $v_id){
            try {
            $rend = mt_rand(10000000 , 99999999);
            //dd($rend);
            $i = 0;
            $lastid = Lead::where('id',explode(",",$v_id))->first();//dd($v_id);
            
            if($lastid->tracking == Null){
                Log::info('Processing Lead ID: ' . $lastid->id);
                $citie = Citie::where('id',$lastid->id_city)->first();
                $seller = User::where('id',$lastid->id_user)->first();
                $product = Product::where('id',$lastid->id_product)->first();
                $products = LeadProduct::where('id_lead',$lastid->id)->get();
                $sku = SkuLastmile::where('id_product',$product->id)->where('id_lastmille',$lastmille->id_lastmile)->first();

                $apiEndpoint = 'https://tigerline.ma/clients/api-parcels';

                if($lastmille->type == "STOCK"){
                    $formData = [
                        'token' => $lastmille->auth_key, // <-- Replace with your actual API key or use .env
                        'action' => 'add',
                        'ville' => $citie->id_city_lastmille,
                        'phone' => $lastid->phone,
                        'qty' => $lastid->quantity,
                        'adresse' => $lastid->address,
                        'note' => $lastid->note,
                        'price' => $lastid->lead_value,
                        'name' => $lastid->name,
                        'tracking' => '',
                        'stock' => 1,
                        'products[0][sku]' => $sku->sku,
                        'products[0][qty]' => $lastid->quantity,
                    ];
                }else{
                    $formData = [
                        'token' => $lastmille->auth_key, // <-- Replace with your actual API key or use .env
                        'action' => 'add',
                        'ville' => $citie->id_city_lastmille,
                        'phone' => $lastid->phone,
                        'qty' => $lastid->quantity,
                        'adresse' => $lastid->address,
                        'note' => $lastid->note,
                        'price' => $lastid->lead_value,
                        'name' => $lastid->name,
                        'tracking' => '',
                        'stock' => 0,
                    ];
                }

                    $response = Http::asForm()->post($apiEndpoint, $formData);
                    //dd($response->body());
                    Log::info('Tigerline API Response:', ['response' => $response->body()]);

                    if ($response->successful()) {
                        Lead::where('id',$lastid->id)->update(['tracking' => $response['tracking']]);
                        // return response()->json([
                        //     'status' => 200,
                        //     'msg' => 'ajouté avec succès',
                        //     'tracking' => $response['sku'] ?? 'code envoi inconnu'
                        // ]);
                    } else {

                        $errorMessage = $res['message'] ?? $response->body();

                         Log::error("Tigerline API Error for Lead {$lastid->id}", [
                                'status'   => $response->status(),
                                'error'    => $jsonResponse['message'] ?? $response->body(),
                                'payload'  => $formData
                            ]);

                          Error::create([
                                'id_country' => $lastid->id_country,
                                'id_lead' => $lastid->id,
                                'id_lastmile' => $lastmille->id_lastmile,
                                'error' => $errorMessage,
                                'code' => $res['code'] ?? null,
                            ]);

                        continue;

                    }
                } 
                }  catch (\Exception $e) {
                    Log::error("Tigerline API Exception for Lead {$lastid->id}", [
                        'error' => $e->getMessage(),
                        'payload' => $formData
                    ]);
                   Error::create([
                            'id_country' => $lastid->id_country ?? Auth::user()->country_id,
                            'id_lead' => $lastid->id ?? $v_id,
                            'id_lastmile' => $lastmille->id_lastmile ?? null,
                            'error' => $e->getMessage(),
                            'code' => null,
                        ]);

                   continue; 
                }
        }
    }

    //maberr

    public function maberr(Request $request)
    {
        $lastmille = LastmilleIntegration::where('id',$request->bussines)->where('id_country',Auth::user()->country_id)->first();
        
        $list_id = array();
        $path = explode(",", $request->list_ids);
        $ids = array_merge($list_id, $path);
        
        foreach($ids as $v_id){
            $rend = mt_rand(10000000 , 99999999);
            //dd($rend);
            $i = 0;
            $lastid = Lead::where('id',explode(",",$v_id))->first();//dd($v_id);

             if (!$lastid) {
                Error::create([
                    'id_country' => Auth::user()->country_id,
                    'id_lead' => $v_id,
                    'id_lastmile' => $lastmille->id_lastmile,
                    'error' => 'Lead not found',
                    'code' => null
                ]);
                continue; 
            }

            if ($lastid->tracking != null) {
                continue;
            }

            if($lastid->tracking == Null){
                $citie = Citie::where('id',$lastid->id_city)->first();
                $seller = User::where('id',$lastid->id_user)->first();
                $product = Product::where('id',$lastid->id_product)->first();
                $products = LeadProduct::where('id_lead',$lastid->id)->get();

                $apiEndpoint = 'https://maber.ma/clients/api-parcels';

                $formData = [
                    'action' => 'add',
                    'token' => $lastmille->auth_key, // <-- Replace with your actual API key or use .env
                    'name' => $lastid->name,
                    'phone' => $lastid->phone,
                    'marchandise' => $product->name,
                    'marchandise_qty' => $lastid->quantity,
                    'ville' => $citie->name,
                    'adresse' => $lastid->address,
                    'note' => $lastid->note,
                    'price' => $lastid->lead_value,
                ];

                try {
                    $response = Http::asForm()->post($apiEndpoint, $formData);

                    if ($response->successful()) {
                        return response()->json([
                            'status' => 200,
                            'msg' => 'ajouté avec succès',
                            'tracking' => $response['tracking'] ?? 'code envoi inconnu'
                        ]);
                    } else {

                        Error::create([
                            'id_country' => Auth::user()->country_id,
                            'id_lead' => $lastid->id,
                            'id_lastmile' => $lastmille->id_lastmile,
                            'error' => $response['message'] ?? 'API error',
                            'code' => $response->status()
                        ]);
                        continue;
                       
                    }
                } catch (\Exception $e) {

                    Error::create([
                        'id_country' => Auth::user()->country_id,
                        'id_lead' => $v_id,
                        'id_lastmile' => $lastmille->id_lastmile,
                        'error' => $e->getMessage(),
                        'code' => null
                    ]);
                    continue;

                
                }
            }
        }
    }

    //ozonexpress

    public function ozonexpress(Request $request)
    {
        $lastmille = LastmilleIntegration::where('id', $request->bussines)->where('id_country', Auth::user()->country_id)->first();
            
        if (!$lastmille) {
            return response()->json(['success' => false, 'message' => 'Business integration not found'], 404);
        }

        $citiesResponse = Http::get('https://api.ozonexpress.ma/cities');
        $ozoneCities = $citiesResponse->json();

            
        $path = explode(",", $request->list_ids);
        $successCount = 0;
        $errors = [];


        foreach ($path as $v_id) {
            $lead = Lead::where('id', $v_id)->first();
                
            if (!$lead) {
                $errors[] = "Lead with ID $v_id not found";
                continue;
            }

            $citie = Citie::where('id', $lead->id_city)->first();
            $product = Product::where('id', $lead->id_product)->first();


            $ozoneCity = null;
            foreach($ozoneCities["CITIES"] as $city) {
                if (strtolower($city['NAME']) == strtolower($citie->name)) {
                    $ozoneCity = $city;
                    break;
                }
            }
            
            if (!$ozoneCity) {
                $errors[] = "City '{$citie->name}' not found in Ozonexpress cities for lead $v_id";
                continue;
            }


            $formData = [
                'tracking-number' => '', 
                'parcel-receiver' => $lead->name,
                'parcel-phone' => $lead->phone,
                'parcel-city' => $ozoneCity['ID'], 
                'parcel-address' => $lead->address,
                'parcel-note' => $lead->note,
                'parcel-price' => $lead->lead_value,
                'parcel-nature' => $product->name,
                'parcel-stock' => 0, 
                'products' => json_encode([['ref' => $product->reference ?? '', 'qnty' => 1]])
            ];

            $response = Http::asForm()->post('https://api.ozonexpress.ma/customers/'.$lastmille->auth_key.'/'.$lastmille->auth_id.'/add-parcel', $formData);

            $res = $response->json();

            if (isset($res['ADD-PARCEL']['RESULT']) && $res['ADD-PARCEL']['RESULT'] === 'SUCCESS') {
                Lead::where('id', $lead->id)->update([
                        'id_last_mille' => $lastmille->id_lastmile,
                        'tracking' => $res['ADD-PARCEL']['NEW-PARCEL']['TRACKING-NUMBER'] ?? null
                    ]);
                    $successCount++;
            } else {
                    $errors[] = "Failed for lead $v_id: " . ($res['ADD-PARCEL']['MESSAGE'] ?? 'Unknown error');
            }
        }

        return response()->json([
                'success' => $successCount > 0,
                'processed' => count($path),
                'success_count' => $successCount,
                'errors' => $errors
        ]);
    }
    
    // speedex

    public function speedex(Request $request)
    {
        $lastmille = LastmilleIntegration::where('id',$request->bussines)->where('id_country',Auth::user()->country_id)->first();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$lastmille->auth_key,
        ];
        $list_id = array();
        $path = explode(",", $request->list_ids);
        $ids = array_merge($list_id, $path);
        
        foreach($ids as $v_id){
           try{
               $rend = mt_rand(10000000 , 99999999);
               //dd($rend);
               $i = 0;
               $lastid = Lead::where('id',explode(",",$v_id))->first();//dd($v_id);
               if($lastid->tracking == Null){
                $citie = Citie::where('id',$lastid->id_city)->first();
                $seller = User::where('id',$lastid->id_user)->first();
                $product = Product::where('id',$lastid->id_product)->first();
                $products = LeadProduct::where('id_lead',$lastid->id)->get();

                $productData = [];
                foreach($products as $v_product){
                    $sku = SkuLastmile::where('id_product',$v_product->id_product)->where('id_lastmille', $lastmille->id_lastmile)->first();
                    $productData [] = [
                        'product_sku' => $sku->sku,
                        'product_qty' => $v_product->quantity
                    ];
                }
                if($lastmille->type == "SIMPLE"){
                    $body = [
                        "parcels" => [
                            [
                                'parcel_code' => '',
                                'parcel_receiver' => $lastid->name,
                                'parcel_phone' => $lastid->phone,
                                'parcel_price' => $lastid->lead_value,
                                'parcel_city' => $citie->name,
                                'parcel_address' => $lastid->address,
                                'parcel_note' => $lastid->note,
                                'marchandise' => $product->name,
                            ]
                        ]
                    ];
                }else{
                    $body = [
                        "parcels" => [
                            [
                                'parcel_from_stock' => '1',
                                'parcel_code' => '',
                                'parcel_receiver' => $lastid->name.' ',
                                'parcel_phone' => $lastid->phone,
                                'parcel_price' => $lastid->lead_value,
                                'parcel_city' => $citie->name,
                                'parcel_address' => $lastid->address,
                                'parcel_note' => $lastid->note,
                                'products' => $productData,
                                "warehouse" => "AGADIR",
                                "parcel_can_open" => "1"
                            ]
                        ]
                    ];
                }
                
                $response = Http::withHeaders($headers)->post('https://clients.speedex.ma/api/add-parcels', $body);

                $res = $response->json();//dd($res);
                if(!empty($res['data']['success'])){
                    Lead::where('id',$lastid->id)->update(['id_last_mille' => $lastmille->id_lastmile ,'tracking' => $res['data']['success'][0] ]);
                } else {
                $errorMessage = $res['data']['errors'] ?? $response->body();

                Log::error("Speedex API Error for Lead {$lastid->id}", [
                    'payload' => $body,
                    'response' => $res
                ]);

                Error::create([
                    'id_country' => $lastid->id_country,
                    'id_lead' => $lastid->id,
                    'id_lastmile' => $lastmille->id_lastmile,
                    'error' => is_array($errorMessage) ? json_encode($errorMessage) : $errorMessage,
                    'code' => $res['code'] ?? null,
                ]);

                continue; // skip to next lead
            }
            }
        } catch (\Exception $e) {
            Log::error("Speedex Exception for Lead {$v_id}", [
                'error' => $e->getMessage(),
                'payload' => $body ?? []
            ]);

            Error::create([
                'id_country' => $lastid->id_country ?? Auth::user()->country_id,
                'id_lead' => $lastid->id ?? $v_id,
                'id_lastmile' => $lastmille->id_lastmile ?? null,
                'error' => $e->getMessage(),
                'code' => null,
            ]);

            continue; // continue to next lead
        }
        }
        return response()->json(['success'=>true]);
    }

    // olivrison

    public function olivrison(Request $request)
    {
        $lastmille = LastmilleIntegration::where('id',$request->bussines)->where('id_country',Auth::user()->country_id)->first();
        $headers = [
            'Content-Type' => 'application/json',
        ];

        $body = [
            'apiKey'    => $lastmille->auth_id,
            'secretKey' => $lastmille->auth_key,
        ];

        $response = Http::withHeaders($headers)->post('https://partners.olivraison.com/auth/login', $body);

        $res = $response->json();
        $token = $res['token'];

        $list_id = array();
        $path = explode(",", $request->list_ids);
        $ids = array_merge($list_id, $path);
            //dd($request->all());
        foreach($ids as $v_id){
            try {
            $rend = mt_rand(10000000 , 99999999);
            //dd($rend);
            $i = 0;
            $lastid = Lead::where('id',explode(",",$v_id))->first();//dd($lastid);
            $citie = Citie::where('id',$lastid->id_city)->first();
            $seller = User::where('id',$lastid->id_user)->first();
            $product = Product::where('id',$lastid->id_product)->first();
            $sku = SkuLastmile::where('id_product',$product->id)->where('id_lastmille',$lastmille->id_lastmile)->first();
            //dd($citie);
            $headers = [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer '.$token.'',
            ];
            if($lastmille->type == "STOCK"){
                $body = [
                    'price'        => $lastid->lead_value,
                    'comment'      => $product->name,
                    'description'  => $product->name,
                    'inventory' => true,
                    'fullfillment' => [
                        [
                            "quantity" => $lastid->quantity,
                            "reference" => $sku->sku,
                        ]
                    ],
                    'name'         => $lastid->name,
                    'destination'  => [
                        'name'          => $lastid->name,
                        'phone'         => $lastid->phone,
                        'city'          => $citie->name,
                        'streetAddress' => $lastid->address,
                    ],
                ];
            }else{
                $body = [
                    'price'        => $lastid->lead_value,
                    'comment'      => $product->name,
                    'description'  => $product->name,
                    'inventory'    => false,
                    'name'         => $lastid->name,
                    'destination'  => [
                        'name'          => $lastid->name,
                        'phone'         => $lastid->phone,
                        'city'          => $citie->name,
                        'streetAddress' => $lastid->address,
                    ],
                ];
            }

            $response = Http::withHeaders($headers)->post('https://partners.olivraison.com/package/new', $body);

            $res = $response->json();

        if (!$response->successful()) {
             $errorCode = $res['code'] ?? null;
            $errorMessage = $res['description'] ?? ($res['message'] ?? $response->body());

            Log::error("Olivraison Package Error for Lead {$lastid->id}", [
                'status' => $response->status(),
                'code'   => $errorCode,
                'error'  => $errorMessage,
                'payload' => $body
            ]);

            Error::create([
                'id_country' => Auth::user()->country_id,
                'id_lead'    => $lastid->id,
                'id_lastmile'=> $lastmille->id_lastmile,
                'error'      => $errorMessage,
                'code'       => $errorCode,
            ]);
            continue; 
        }
            // Assuming that you are sending a JSON file as the request body
            if(!empty($res['trackingID'])){
                $pickupBody  = [
                    "packages" => [
                        $res['trackingID']
                    ]
                ];
                $pickupResponse = Http::withHeaders($headers)->post('https://partners.olivraison.com/pickup', $body);
                $ress = $response->json();

                if (!$pickupResponse->successful()) {
                    Log::error("Olivraison Pickup Error for Lead {$lastid->id}", [
                        'status' => $pickupResponse->status(),
                        'error'  => $ress['message'] ?? $pickupResponse->body(),
                        'payload' => $pickupBody
                    ]);
                    continue;
                }
                if(!empty($res['trackingID'])){
                    Lead::where('id',$lastid->id)->update(['id_last_mille' => $lastmille->id_lastmile ,'tracking' => $res['trackingID'] ]);
                }
            }
        } catch (\Exception $e) {
            Log::error("Olivraison Exception for Lead {$v_id}", ['error' => $e->getMessage()]);

            Error::create([
                'id_country' => Auth::user()->country_id,
                'id_lead' => $v_id,
                'id_lastmile' => $lastmille->id_lastmile,
                'error' => $e->getMessage(),
                'code' => null
            ]);

            continue;
        }
    
        }
        return response()->json(['success'=>true]);
    }

    //onessta 

    public function onessta(Request $request)
    {
        $lastmille = LastmilleIntegration::where('id', $request->bussines)
                        ->where('id_country', Auth::user()->country_id)
                        ->first();

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $lastmille->api_key, 
            'API-Key' => $lastmille->auth_key, 
            'Client-ID' => $lastmille->auth_id, 
            'Accept' => 'application/json',
        ];

        $list_id = [];
        $path = explode(",", $request->list_ids);
        $ids = array_merge($list_id, $path);

        foreach($ids as $v_id) {
            $lastid = Lead::where('id', explode(",", $v_id))->first();
            $citie = Citie::where('id', $lastid->id_city)->first();
            $seller = User::where('id', $lastid->id_user)->first();
            $product = Product::where('id', $lastid->id_product)->first();
            $leadproduct = LeadProduct::where('id_lead',$lastid->id)->select('id_product')->get()->toarray();
            if($lastmille->type == "stock"){
                $sku = SkuLastmile::wherein('id_product',$leadproduct)->where('id_lastmille',$lastmille->id_lastmile)->pluck('sku')->toarray();
                $array = [];
                foreach($sku as $v_sku){
                    $array = $v_sku;
                }
                $sku = implode(';', $sku);
            }else{
                $sku = '';
            }
            //dd(collect($sku));
            $code = 'CD' . mt_rand(10000000, 99999999);
            $body = [
                'code' => $code,
                'sku' => $sku,
                'receiver' => $lastid->name,
                'phone' => $lastid->phone,
                'price' => $lastid->lead_value,
                'city' => [
                    'id' => $citie->city_id, 
                    'name' => $citie->name,
                ],
                'pickup_city' => [
                    'id' => "", 
                    'name' => "", 
                ],
                'address' => $lastid->address,
                'note' => $product->name,
                'product_nature' => $product->name, 
                'can_open' => 1, 
                'replace' => 0,
            ];

            $response = Http::withHeaders($headers)
                            ->post('https://api.onessta.com/api/v1/c/parcels/add', $body);

            $res = $response->json();
            if (!empty($res['success']) && $res['success'] == true && !empty($res['data']['parcel']['code'])) {
                Lead::where('id', $lastid->id)
                    ->update([
                        'id_last_mille' => $lastmille->id_lastmile,
                        'tracking' => $res['data']['parcel']['code']
                    ]);
            }
        }

        return response()->json(['success' => true]);
    }

    //digylog

    public function digylog(Request $request)
    {
        $digylog = LastmilleIntegration::where('id', $request->bussines)
                        ->where('id_country', Auth::user()->country_id)
                        ->first();
    
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $digylog->api_key,
            'Accept' => 'application/json',
            'Referer' => 'https://apiseller.digylog.com',
        ];
    
        $list_id = [];
        $path = explode(",", $request->list_ids);
        $ids = array_merge($list_id, $path);
    
        foreach($ids as $v_id) {
            $lead = Lead::where('id', explode(",", $v_id))->first();
            $city = Citie::where('id', $lead->id_city)->first();
            $seller = User::where('id', $lead->id_user)->first();
            $product = Product::where('id', $lead->id_product)->first();
            

            
            $body = [
                'network' => 1, // You should get this from /networks endpoint
                'store' => 'default_store', // You should get this from /stores endpoint
                'mode' => 1, // 1 for standard order
                'status' => 1, // 1 to add & send orders immediately
                'orders' => [
                    [
                        'num' => $orderNum,
                        'type' => 1, // 1 for normal delivery
                        'name' => $lead->name,  
                        'phone' => $lead->phone,
                        'address' => $lead->address,
                        'city' => $city->name,
                        'price' => $lead->lead_value,
                        'refs' => [
                            [
                                'designation' => $product->name,
                                'quantity' => 1
                            ]
                        ],
                        'port' => 1, // 1 for fees paid by seller
                        'openproduct' => 1, // 1 to allow opening product
                        'note' => 'Order from your platform'
                    ]
                ]
            ];
    
            $response = Http::withHeaders($headers)
                            ->post('https://api.digylog.com/api/v2/seller/orders', $body);
    
            $res = $response->json();
            
            // Assuming the response contains tracking information in a similar format
            if (!empty($res['success']) && isset($res['data']['tracking'])) {
                Lead::where('id', $lead->id)
                    ->update([
                        'id_last_mille' => $digylog->id_lastmile,
                        'tracking' => $res['data']['tracking']
                    ]);
            }
        }
    
        return response()->json(['success' => true]);
    }

    //ameex

    public function ameex(Request $request)
    {
        $list_id = array();
        $path = explode(",", $request->list_ids);
        $ids = array_merge($list_id, $path);

        foreach($ids as $v_id){
           try{ 
            $rend = mt_rand(10000000 , 99999999);
            //dd($rend);
            $i = 0;
            $lastid = Lead::where('id',explode(",",$v_id))->first();//dd($lastid);
            $citie = Citie::where('id',$lastid->id_city)->first();
            $seller = User::where('id',$lastid->id_user)->first();
            $product = Product::where('id',$lastid->id_product)->first();
            $citie = $citie->name;
            $citie = $this->ameexcitie($citie);
            $ShippingCompany = ShippingCompany::where('name',"AMEEX")->first();
            $lastmille = LastmilleIntegration::where('id',$request->bussines)->where('id_country',$lastid->id_country)->first();

            $client = new Client();

            // Set headers
            $headers = [
                'C-Api-Id' => $lastmille->auth_id,
                'C-Api-Key' => $lastmille->auth_key,
            ];

            // Set multipart data
            $multipart = [
                [
                    'name' => 'type',
                    'contents' => 'SIMPLE'
                ],
                [
                    'name' => 'business',
                    'contents' => $lastmille->auth_id
                ],
                [
                    'name' => 'order_num',
                    'contents' => $lastid->n_lead
                ],
                [
                    'name' => 'replace',
                    'contents' => 'false'
                ],
                [
                    'name' => 'exchange_code',
                    'contents' => ' '
                ],
                [
                    'name' => 'open',
                    'contents' => 'YES'
                ],
                [
                    'name' => 'try',
                    'contents' => 'YES'
                ],
                [
                    'name' => 'fragile',
                    'contents' => '0'
                ],
                [
                    'name' => 'receiver',
                    'contents' => $lastid->name
                ],
                [
                    'name' => 'phone',
                    'contents' => $lastid->phone
                ],
                [
                    'name' => 'city',
                    'contents' => $citie
                ],
                [
                    'name' => 'address',
                    'contents' => $lastid->address
                ],
                [
                    'name' => 'comment',
                    'contents' => ''
                ],
                [
                    'name' => 'product',
                    'contents' => $product->name
                ],
                [
                    'name' => 'cod',
                    'contents' => $lastid->lead_value
                ],
                [
                    'name' => 'products[0][id]',
                    'contents' => ''
                ],
                [
                    'name' => 'products[0][qty]',
                    'contents' => ''
                ]
            ];

                    // API URL
            $url = 'https://api.ameex.app/customer/Delivery/Parcels/Action/Type/Add';

            // Send the POST request
            $response = $client->post($url, [
                'headers' => $headers,
                'multipart' => $multipart,
            ]);
            $res = json_decode($response->getBody() , true);
             if(!empty($res['api']['data']['code'])){
                Lead::where('id', $lastid->id)
                    ->update([
                        'id_last_mille' => $ShippingCompany->id,
                        'tracking' => $res['api']['data']['code']
                    ]);
            } else {

                $errorMessage = $res['api']['message'] ?? 'Unknown error';
                Error::create([
                    'id_country' => $lastid->id_country,
                    'id_lead'    => $lastid->id,
                    'id_lastmile'=> $lastmille->id_lastmile ?? null,
                    'error'      => $errorMessage,
                    'code'       => $res['api']['code'] ?? null,
                ]);
                continue; 
            }

        } catch (\Exception $e) {
            Error::create([
                'id_country' => $lastid->id_country ?? Auth::user()->country_id,
                'id_lead'    => $lastid->id ?? null,
                'id_lastmile'=> $lastmille->id_lastmile ?? null,
                'error'      => $e->getMessage(),
                'code'       => null,
            ]);

            continue; 
        }
        }
    }

    public function ameexcitie($citie)
    {
        $client = new Client();
            // Define the URL of the API
        $url = 'https://api.ameex.app/customer/Cnfg/App';
            // Send the GET request asynchronously
        $response = $client->get($url);
            // Return the response body
        $data = json_decode($response->getBody() , true);
        foreach($data['data']['cnfg']['ameex_cities'] as  $index => $v_data){
            if($v_data['NAME'] == $citie){
                return $index;
            }
        }
    }

    public function ameexdeliver($lastmille)
    {
        $ShippingCompany = ShippingCompany::where('name',"AMEEX")->first();
        $client = new Client();

        // Set headers
        $headers = [
            'C-Api-Id' => $lastmille->auth_id,
            'C-Api-Key' => $lastmille->auth_key,
        ];

        $url = 'https://api.ameex.app/customer/Delivery/DeliveryNotes/Action/Type/Add';
        $multipart = [
            [
                'name' => 'business',
                'contents' => $lastmille->auth_id
            ],
        ];
        // Send the GET request
        $response = $client->post($url, [
            'headers' => $headers,
            'multipart' => $multipart,
        ]);

        // Return the response body
        $data = json_decode($response->getBody() , true);
        return $data['api']['data']['ref'];
    }

    public function saveddelivereyameex($deliverynote,$lastmille)
    {
        $ShippingCompany = ShippingCompany::where('name',"AMEEX")->first();

        $client = new Client();

        // Set headers
        $headers = [
            'C-Api-Id' => $lastmille->auth_id,
            'C-Api-Key' => $lastmille->auth_key,
        ];

        $url = 'https://api.ameex.app/customer/Delivery/DeliveryNotes/Action/Type/Save?Ref='.$deliverynote.'';
        // Send the GET request
        $response = $client->PUT($url, [
            'headers' => $headers,
        ]);
        $response = json_decode($response->getBody() , true);

        return $response['api']['data']['ref'];

    }

    public function printameex($savedselivery,$lastmille)
    {
        $ShippingCompany = ShippingCompany::where('name',"AMEEX")->first();

        $client = new Client();

        // Set headers
        $headers = [
            'C-Api-Id' => $lastmille->auth_id,
            'C-Api-Key' => $lastmille->auth_key,
        ];

        $response = Http::withHeaders($headers)->get('https://api.ameex.app/customer/Delivery/DeliveryNotes/Print/Type/Labels', [
            'Ref' => $savedselivery,
            'LabelType' => 'Label_A4_8',
        ]);
        //$response = json_decode($response->getBody() , true);

        $data = $response->json();
        $html = $data['api']['data']['html'];
        // $html = str_replace("\n", "",$html);

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
        $headers = array(
            "Content-type" => "application/pdf",
            "charset" => "utf-8"
        );
        Storage::put('pdf/'.$savedselivery.'.pdf', $pdf->output(),$headers);
        return '/pdf/'.$savedselivery.'.pdf';

    }

    public function ameexwaybille(Request $request)
    {

        $ShippingCompany = ShippingCompany::where('name',"AMEEX")->first();
        $lastmille = LastmilleIntegration::where('id',$request->bussines)->where('id_country',Auth::user()->country_id)->first();

        $list_id = array();
        $path = explode(",", $request->list_ids);
        $ids = array_merge($list_id, $path);

        $deliverynote = $this->ameexdeliver($lastmille);
        $multipart = [];
        foreach($ids as $v_id){
            $check = Lead::where('id',$v_id)->where('tracking','!=',Null)->first();
            if(!empty($check->id)){
                $multipart[] = [
                    'name' => 'parcels[]',
                    'contents' => $check->tracking,
                ];
            }
        }
        $client = new Client();

        // Set headers
        $headers = [
            'C-Api-Id' => $lastmille->auth_id,
            'C-Api-Key' => $lastmille->auth_key,
        ];

        $url = 'https://api.ameex.app/customer/Delivery/DeliveryNotes/Action/Type/AddParcels?Ref='.$deliverynote.'';
        $multipart = $multipart;
        // Send the GET request
        $response = $client->post($url, [
            'headers' => $headers,
            'multipart' => $multipart,
        ]);
        $response = json_decode($response->getBody() , true);
        //dd($response);
        if($response['api']['type'] = "success"){
            $savedselivery = $this->saveddelivereyameex($deliverynote,$lastmille);
            $print = $this->printameex($savedselivery,$lastmille);
            
            return $print;
        }

    }

    // api order
    public function apiorder(Request $request)
    {
        $list_id = array();
        $path = explode(",", $request->list_ids);
        $ids = array_merge($list_id, $path);

        // $labels = LabelParameter::where('id_country', Auth::user()->country_id)->first();

        $lastmille = ShippingCompany::where('id',$request->lastmille)->first();
        if($request->type == "CreateOrder"){
            if($lastmille->name == "AMEEX"){
                $ameex = $this->ameex($request);
            }
            if($lastmille->name == "OLIVRAISON"){
                $ameex = $this->olivrison($request);
            }
            if($lastmille->name == "SPEEDEX"){
                $ameex = $this->speedex($request);
            }
            if($lastmille->name == "DIGYLOG"){
                $ameex = $this->digylog($request);
            }
            if($lastmille->name == "OZONEXPRESS"){
                $ameex = $this->ozonexpress($request);
            }
            if($lastmille->name == "MABERR"){
                $ameex = $this->maberr($request);
            }
            if($lastmille->name == "ONESSTA"){
                $ameex = $this->onessta($request);
            }
            if($lastmille->name == "TigerLine"){
                $ameex = $this->tigerline($request);
            }

            return response()->json(['success'=>true]);
        }
        if($request->type == "WaybillOrder"){
            //dd(storage_path());
            if($lastmille->name == "AMEEX"){
                $ameex = $this->ameexwaybille($request);
            }
            response()->download(storage_path('log/' . $ameex ));
        }

    }

    public function businessLastMile(Request $request) {

        $lastMileId = $request->input('lastMileId');
        $sellerId = $request->input('sellerId');

        $user = User::find($sellerId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
        $businesses = LastmilleIntegration::where('id_lastmile', $lastMileId)
            ->where('id_country', $user->country_id)
            ->where('id_user', $sellerId) 
            ->get();
        
        return response()->json([
            'data' => $businesses
        ]);
    }

    public function stocks($id)
    {
        $product = Product::where('sku',$id)->first();

        $stocks = Stock::where('id_product',$product->id)->where('stocks.id_warehouse', Auth::user()->country_id)->get();
        $countries = Countrie::where('id','!=',Auth::user()->country_id)->get();

        return view('backend.products.stocks', compact('stocks','countries'));
    }

    //update stock

    public function updatestock(Request $request)
    {
        $stock = Stock::where('id',$request->id)->first();

        $data = array();
        $data['qunatity'] = $request->quantity;
        $data['isactive'] = 1;
        Stock::where('id',$request->id)->update($data);
        $mapping = MappingStock::where('id_stock',$request->id)->first();
        if(!empty($mapping->id)){
            $data2 = [
                'quantity' => $request->quantity,
                'isactive' => '1',
            ];
            MappingStock::where('id',$mapping->id)->update($data2);
        }
        return response()->json(['success'=>true]);
    }

    //list stock mapping

    public function stockmapping($id)
    {
        $mapping = MappingStock::with(['stock','tagie' => function($query){
            $query = $query->with(['pale' => function($query){
                $query = $query->with('ro');
            }]);
        }])->where('id_stock',$id)->get();

        //dd($mapping);
        return view('backend.products.mapping', compact('mapping'));
    }

    public function updatestockmapping(Request $request)
    {
        $mapp = MappingStock::where('id',$request->id)->first();
        $data = array();
        $data['quantity'] = $request->quantity;
        $data['isactive'] = 1;
        //dd($request->id);
        MappingStock::where('id',$request->id)->update($data);

        $sum = MappingStock::where('id_stock',$mapp->id_stock)->sum('quantity');
        //dd($sum);
        Stock::where('id',$mapp->id_stock)->update(['qunatity'=>$sum , 'isactive'=> 1]);
        return response()->json(['success'=>true]);
    }

    // get list product by seller and country
    public function listproduct(Request $request)
    {
        $stock = Stock::where('id',$request->stock)->first();
        if(!empty($stock->id)){
            $product = Product::where('id',$stock->id_product)->first();//dd($product);
            $empData['data'] = Product::where('id_user',$product->id_user)->where('id_country',$request->id)->select('id','products.name','weight')->get();
            return response()->json($empData);
        }else{
            response()->json(['error'=>false]);
        }
    }

    //transfer stock to another country

    public function transferstock(Request $request)
    {
        if(empty($request->weight)){
            return response()->json(['errors'=>false]);
        }

        $checkstock = Stock::where('id',$request->stock)->first();
        if($checkstock->qunatity < $request->quantity){
            return response()->json(['error'=>false]);
        }

        $product = Product::where('id',$checkstock->id_product)->first();
        $leadProducts = LeadProduct::where('id_product',$product->id)
                                    ->where('outstock',0)
                                    ->where('date_delivred','!=',null)
                                    ->where('livrison','unpacked')->sum('quantity');
        $rest = $checkstock->qunatity - $leadProducts;
        if($rest < $request->quantity){
            return response()->json(['error'=>false]);
        }
        if(!empty($checkstock->id)){
            $import = Import::where('id_product',$checkstock->id_product)->orderby('id','desc')->first();
            $ref = mt_rand(10000000, 99999999);
            $data3 = array();
            $data3['ref'] = $ref;
            $data3['type'] = "transfer";
            $data3['id_product'] = $checkstock->id_product;
            $data3['id_country'] = $import->id_country;
            $data3['quantity_sent'] = '-'.$request->quantity;
            $data3['quantity_received'] = '-'.$request->quantity;
            $data3['quantity_real'] = '-'.$request->quantity;
            $data3['shipping_country'] = $import->shipping_country;
            $data3['weight'] = $import->weight;
            $data3['nbr_packages'] = $import->nbr_package;
            $data3['expedition_mode'] = $import->expidtion_mode;
            $data3['expidtion_date'] = $import->date_shipping;
            $data3['name_shipper'] = $import->name_transport;
            $data3['phone_shipper'] = $import->phone_shipping;
            $data3['date_arrival'] = $import->date_arrival;
            $data3['status'] = 'confirmed';
            //dd($data2);
            Import::insert($data3);
            $newimp = Import::where('ref',$ref)->first();
            $ref2 = mt_rand(10000000, 99999999);
            $data2 = array();
            $data2['ref'] = $ref2;
            $data2['type'] = "transfer";
            $data2['id_import'] = $newimp->id;
            $data2['id_product'] = $request->product;
            $data2['id_country'] = $request->country;
            $data2['quantity_sent'] = $request->quantity;
            $data2['quantity_received'] = $request->quantity;
            $data2['quantity_real'] = $request->quantity;
            $data2['shipping_country'] = $import->shipping_country;
            $data2['weight'] = $import->weight;
            $data2['nbr_packages'] = $import->nbr_package;
            $data2['expedition_mode'] = $import->expidtion_mode;
            $data2['expidtion_date'] = $import->date_shipping;
            $data2['name_shipper'] = $import->name_transport;
            $data2['phone_shipper'] = $import->phone_shipping;
            $data2['date_arrival'] = $import->date_arrival;
            $data2['status'] = 'confirmed';
            //dd($data2);
            Import::insert($data2);
            $import2 = Import::where('ref',$ref2)->first();
            $history = [
                'type' => 'entred',
                'id_product' => $request->product,
                'quantity' => $request->quantity,
                'note' => 'confirmed import transfer',
            ];
            HistoryStock::insert($history);
            $checkstock2 = Stock::where('id_product',$request->product)->first();
            if(!empty($checkstock2->id)){
                $data2 = [
                    'id_product' => $request->product,
                    'id_warehouse' => $request->country,
                    'quantity_accept' => $request->quantity  + $checkstock2->quantity_accept,
                    'qunatity' => $request->quantity + $checkstock2->qunatity,
                    'isactive' => '1',
                ];
                Stock::where('id',$checkstock2->id)->update($data2);
                $checkmapping = MappingStock::where('id_stock',$checkstock2->id)->first();
                if(!empty($checkmapping->id)){
                    $data3 = [
                        'quantity_map' => $checkmapping->quantity_map + $request->quantity ,
                        'quantity' => $checkmapping->quantity + $request->quantity ,
                        'isactive' => '1',
                    ];
                    MappingStock::where('id',$checkmapping->id)->update($data3);
                }
                Product::where('id',$request->product)->update(['checkimport' => 1 , 'weight' => $request->weight]);
                Stock::where('id',$checkstock->id)->update(['qunatity' => $checkstock->qunatity - $request->quantity]);
                MappingStock::where('id_stock',$checkstock->id)->update(['quantity' => $checkstock->qunatity - $request->quantity]);
            }else{
                $data2 = [
                    'id_product' => $request->product,
                    'id_warehouse' => $request->country,
                    'quantity_accept' => $request->quantity,
                    'qunatity' => $request->quantity,
                    'isactive' => '1',
                ];
                Product::where('id',$request->product)->update(['checkimport' => 1 , 'weight' => $request->weight]);
                Stock::insert($data2);
                Stock::where('id',$checkstock->id)->update(['qunatity' => $checkstock->qunatity - $request->quantity]);
                MappingStock::where('id_stock',$checkstock->id)->update(['quantity' => $checkstock->qunatity - $request->quantity]);
            }
            return response()->json(['success'=>true]);
        }else{
            return response()->json(['error'=>false]);
        }
    }



    public function rollBackFromShipping($id)
    {
        $lead = Lead::where('id',$id)->where('livreur_id',null)->whereIn('status_livrison',['item packed','proseccing'])->first();
        if($lead){


            try{
                if($lead->waybille)
                {
                    $basename = basename($lead->waybille);

                    $storagePath = storage_path('app/public');

                    $file_path = $storagePath . '/' . $basename;

                    // Check if the file exists before attempting to delete
                    if (file_exists($file_path)) {
                        // Attempt to delete the file
                        unlink($file_path);
                    }
                }
            }catch(\Exception $e){
                return redirect()->back();
            }
            Lead::where('id',$id)->update(['packed' => 0,'tracking' => Null,'status_livrison' => 'picking process','waybille'=>null , 'shipping_company' => Null]);
            $data = [
                'livrison' => 'picking process',
            ];
            LeadProduct::where('id_lead',$lead->id)->update($data);

        }

        return back()->with('success',true);
    }

    public function assignedOrder(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $v_id){
            $tracking = 'Ek'.mt_rand(10000000000, 99999999999);
            Lead::where('id',$v_id)->update(['livreur_id' => $request->delivery , 'tracking' => $tracking]);
            
        }
        return response()->json(['success'=>true]);
    }


    public function rollback(Request $request , $id)
    {
        $products = LeadProduct::where('id_lead',$id)->get();
        foreach($products as $v_pro){
            $checkpro = LeadProduct::where('livrison','!=','unpacked')->where('outstock' ,1)->count();
            if($checkpro != 0){
                $stock = Stock::where('id_product',$v_pro->id_product)->first();
                $mapping = MappingStock::where('id_stock',$stock->id)->first();
                Stock::where('id',$stock->id)->update(['qunatity' => $stock->qunatity + $v_pro->quantity]);
                MappingStock::where('id',$mapping->id)->update(['quantity' => $mapping->quantity + $v_pro->quantity]);
            }
        }
         
        Lead::where('id',$id)->update(['status_livrison' => 'unpacked','tracking' => null ,'shipping_company' => null,'waybille' => null]);
        LeadProduct::where('id_lead',$id)->update(['livrison' => 'unpacked' ,'outstock' => '0','outofstock' => '0' , 'isreturn' => '0']);
        $leadproduct = LeadProduct::where('id_lead',$id)->first();
        $leadproduct->outstock =  '0';
        $leadproduct->save();
        $lead = Lead::where('id',$id)->first();
        try{
            if($lead)
            {           
                $basename = basename($lead->waybille);

                $storagePath = storage_path('app/public');

                $file_path = $storagePath . '/' . $basename;

                // Check if the file exists before attempting to delete
                if (file_exists($file_path)) {
                    // Attempt to delete the file
                    unlink($file_path);
                } 
            }
        }catch(\Exception $e){
            return redirect()->back();
        }
        return back();
    }

    public function multiRollBack(Request $request)
    {
        foreach($request->ids as $id){
            $products = LeadProduct::where('id_lead',$id)->get();
            foreach($products as $v_pro){
                $checkpro = LeadProduct::where('livrison','!=','unpacked')->where('outstock' ,1)->count();
                if($checkpro != 0){
                    $stock = Stock::where('id_product',$v_pro->id_product)->first();
                    $mapping = MappingStock::where('id_stock',$stock->id)->first();
                    Stock::where('id',$stock->id)->update(['qunatity' => $stock->qunatity + $v_pro->quantity]);
                    MappingStock::where('id',$mapping->id)->update(['quantity' => $mapping->quantity + $v_pro->quantity]);
                }
            }
            
            Lead::where('id',$id)->update(['status_livrison' => 'unpacked','tracking' => null ,'shipping_company' => null,'waybille' => null]);
            LeadProduct::where('id_lead',$id)->update(['livrison' => 'unpacked' ,'outstock' => '0','outofstock' => '0' , 'isreturn' => '0']);
            $leadproduct = LeadProduct::where('id_lead',$id)->first();
            $leadproduct->outstock =  '0';
            $leadproduct->save();
            $lead = Lead::where('id',$id)->first();
            try{
                if($lead)
                {           
                    $basename = basename($lead->waybille);

                    $storagePath = storage_path('app/public');

                    $file_path = $storagePath . '/' . $basename;

                    // Check if the file exists before attempting to delete
                    if (file_exists($file_path)) {
                        // Attempt to delete the file
                        unlink($file_path);
                    } 
                }
            }catch(\Exception $e){
                continue;
            } 
        }
       
        return response()->json(['success'=>true]);
    }
}

