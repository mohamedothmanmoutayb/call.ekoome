<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadSetting;
use App\Models\WhatsappMessage;
use Auth;

class SettingController extends Controller
{
    public function index()
    {
        $messages = WhatsappMessage::get();
        return view('backend.settings.message', compact('messages'));
    }

    public function store(Request $request)
    {
        $data = [
            "country_id" => Auth::user()->country_id,
            'message' => $request->message_whatsapp,
            'status' => $request->status,
        ];

        WhatsappMessage::insert($data);

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $data = [
            'meaasge_whatsapp' => $request->whatsapp,
        ];
        LeadSetting::where('id',$request->id)->update($data);

        return response()->json(['success'=>true]);
    }
}
