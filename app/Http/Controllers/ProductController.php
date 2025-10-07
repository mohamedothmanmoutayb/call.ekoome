<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Import;
use App\Models\User;
use App\Models\AssignedProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        $products = Product::with(['imports','users','stocks']);
        if($request->search){
            $products = $products->where('name','like','%'.$request->search.'%')->orwhere('sku','like','%'.$request->search.'%');
        }
        $products = $products->get();
        return view('backend.products.index', compact('products'));
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
    public function edit(Request $request,$id)
    {
        $product = Product::where('id', $id)->first();
        return response()->json($product);
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

    public function price($id)
    {
        $product = Product::where('id',$id)->first();
        $price = $product->price;
        return response()->json($price);
    }

    public function assignedList($id)
    {
        $agents = User::where('id_role',3)->get();
        $assigneds = AssignedProduct::with(['product','agent'])->where('id_product',$id)->get();

        return view('backend.products.assigned', compact('assigneds','agents','id'));
    }

    public function assignedstore(Request $request)
    {
        $data['id_product'] = $request->product;
        $data['id_agent'] = $request->agent;

        AssignedProduct::insert($data);

        return response()->json(['success'=>true]);
    }

    public function assigneddelete(Request $request,$id)
    {
        AssignedProduct::where('id',$id)->delete();

        return back();
    }
}
