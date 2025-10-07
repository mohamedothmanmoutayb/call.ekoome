<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\Lead;
use Illuminate\Http\Request;
use Auth;

class ReclamationController extends Controller
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
        $reclamations = Reclamation::with('lead')->where('id_country', Auth::user()->country_id)->where('id_role_sent', Auth::user()->id_role);
        if(Auth::user()->id_role != 4){
            $reclamations = $reclamations->where('id_user', Auth::user()->id);
        }
        $reclamations = $reclamations->orderby('id','desc')->get();

        $reclamations2 = Reclamation::with('lead')->where('id_role', Auth::user()->id_role)->orderby('id','desc')->get();
        
        return view('backend.reclamations.index', compact('reclamations','reclamations2'));
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
        //dd($request->id);
        //$lead = Lead::where('n_lead',$request->id)->first();
        $data = [
            'id_user' => Auth::user()->id,
            'id_lead' => $request->id,
            'id_role' => $request->service,
            'id_role_sent' => Auth::user()->id,
            'note' => $request->note,
        ];
        Reclamation::insert($data);
        return response()->json(['success'=>true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reclamation  $reclamation
     * @return \Illuminate\Http\Response
     */
    public function show(Reclamation $reclamation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reclamation  $reclamation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reclamation $reclamation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reclamation  $reclamation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reclamation $reclamation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reclamation  $reclamation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reclamation $reclamation)
    {
        //
    }

    public function statuscon(Request $request)
    {
        Reclamation::where('id',$request->id)->update(['status'=>$request->status]);
        return response()->json(['success'=>true]);
    }
}
