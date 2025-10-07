<?php
namespace App\Http\Traits;
use App\Models\Lead;
use App\Models\EmailTemplate;
use App\Models\HistoryStatu;
use App\Models\LeadProduct;
use DateTime;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendMails;
use App\Mail\TrackOrder;
trait EmailTracking {
    
   public function emailNotify($order,$status)
   {
        // Log::info("message");
        $items = LeadProduct::with('product1')->where('id_lead',$order->id)->get();       
        $template = EmailTemplate::where('user_id',$order->id_user)
                                    ->where('id_country',$order->id_country)
                                    ->where('status',$status)
                                    ->where('active',1)
                                    ->first();

        if($template && $order->email){    
            
            SendMails::dispatch($order,$template,$items);
                                                
        }               
   }

}