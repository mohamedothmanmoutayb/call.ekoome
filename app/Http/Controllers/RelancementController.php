<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Stock;
use App\Models\Citie;
use App\Models\Product;
use App\Models\Relancement;
use Illuminate\Http\Request;
use Auth;

class RelancementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $proo = Product::get();
        $products = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $productss = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $product = Stock::with('products')->where('id_warehouse', Auth::user()->country_id)->get();
        $cities = Citie::where('id_country', Auth::user()->country_id)->get();

        $lead = Lead::where('id',$id)->first();

        $livreurs = Lead::with('livreur')->where('id_product',$lead->id_product)->where('id_zone',$lead->id_zone)->get();
        return view('backend.leads.relance', compact('livreurs','proo','products','productss','product','cities','id'));
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
        $lead = Lead::where('id',$request->lead_change)->first();
        $relancement = new Relancement();
        $relancement->lead_relance = $request->lead_relance;
        $relancement->lead_change = $request->lead_change;
        $relancement->id_livreur = $lead->livreur_id;
        $relancement->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Relancement  $relancement
     * @return \Illuminate\Http\Response
     */
    public function show(Relancement $relancement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Relancement  $relancement
     * @return \Illuminate\Http\Response
     */
    public function edit(Relancement $relancement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Relancement  $relancement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Relancement $relancement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Relancement  $relancement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Relancement $relancement)
    {
        //
    }
}
