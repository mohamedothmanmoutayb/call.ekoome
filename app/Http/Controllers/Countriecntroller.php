<?php

namespace App\Http\Controllers;

use App\Models\Countrie;
use Illuminate\Http\Request;

class Countriecntroller extends Controller
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
    public function index()
    {
        //
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
     * @param  \App\Models\Countrie  $countrie
     * @return \Illuminate\Http\Response
     */
    public function show(Countrie $countrie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Countrie  $countrie
     * @return \Illuminate\Http\Response
     */
    public function edit(Countrie $countrie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Countrie  $countrie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Countrie $countrie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Countrie  $countrie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Countrie $countrie)
    {
        //
    }
}
