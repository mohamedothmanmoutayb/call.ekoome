<?php
namespace App\Http\Traits;
use App\Models\Lead;
use App\Models\HistoryStatu;
use DateTime;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\EmailTracking;
trait CttTracking {
   use EmailTracking;
   public function tracking(){

    $token = $this->loginApi();
    if(!$token) return;
        $orders = Lead::where('tracking','!=', Null)
                        ->where('status_confirmation','confirmed')
                        ->where('shipping_company','ctt')
                        ->whereNotIn('status_livrison',['returned','picking proccess','delivered','unpacked','item packed'])
                        ->get();
        
        $processing = ['0000','3000','3900','3901','3902','3903','3904','1030_INCT'];

        $incident = ['1600','1700','2300','2700','1800','1900','2600','2900','1_INCT','1001_INCT','1002_INCT','1004_INCT','75_INAT',
                        '1005_INCT','1006_INCT','1007_INCT','101_INCT','1010_INCT','1012_INCT','102_INCT','1020_INCT',
                        '1034_INCT','1035_INCT','14_INCT','17_INCT','19_INCT','2_INCT','21_INCT','25_INCT','97_INCT',
                        '27_INCT','30_INCT','31_INCT','32_INCT','34_INCT','37_INCT','5_INCT','6_INCT','8_INCT','71_INAT'];
                        
        $intransit = ['0010','0300','0400','0500','0700','1000','1200'];

        $indelivery = ['1500','2400','13_INCT'];

        $rejected = ['P9_INCT','4_INCT','2500'];

        $delivered  = ['2100','2200'];

        $curl = curl_init();

        foreach($orders as $v_order){
                
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.cttexpress.com/integrations/trf/item-history-api/history/$v_order->tracking?view=PUBLICTRACK&showItems=false",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_POSTFIELDS => "",
                    CURLOPT_HTTPHEADER => array(
                        "Content-type: application/json",                     
                        "password: passw0rd$.",
                        "user_name: user1",
                        "Authorization: Bearer ".$token,
                    ),
                ));
                $response = json_decode(curl_exec($curl), true);
                $status = '';
                if($response){
                    $shipping_history = $response['data']['shipping_history'];
                    $event = end($shipping_history['events']);
                    
                    if(in_array($event['code'],$processing)){
                        $v_order->status_livrison = 'proseccing';
                        if($event['description'] == "Anulación servicio"){
                                $v_order->status_livrison = 'rejected';
                                $status = 'rejected';
                                if($v_order->id_country == "11"){                                   
                                        $v_order->tracking_return = $response['data']['return_shipment_code'] ?? null;
                                }else{
                                        $v_order->tracking_return = $response['data']['prime_shipping_code'] ?? null;
                                }
                        }
                    }     
                    elseif(in_array($event['code'],$indelivery)){
                            $v_order->status_livrison = 'in delivery';
                            $status = 'in delivery';
                        }
                    elseif(in_array($event['code'],$intransit)){
                            $v_order->status_livrison = 'in transit';
                            $status = 'in transit';} 
                    elseif(in_array($event['code'],$incident)) {
                            $v_order->status_livrison = 'incident';
                            $status = 'incident';}
                    elseif(in_array($event['code'],$rejected)){
                            $v_order->status_livrison = 'rejected';
                                $status = 'rejected';
                            if($v_order->id_country == "11"){                                   
                                $v_order->tracking_return = $response['data']['return_shipment_code'] ?? null;
                            }else{
                                $v_order->tracking_return = $response['data']['prime_shipping_code'] ?? null;
                            }
                            
                    }
                    elseif(in_array($event['code'],$delivered)){
                            $v_order->status_livrison = 'delivered';}                  
                    else{
                            $v_order->status_livrison = $event['description'];
                    }
                        
                }
                // $lead = Lead::where('id',$v_order->id)->first();
                $data = array();
                $data['country_id'] = $v_order->id_country;
                $data['id_lead'] = $v_order->id;
                $data['status'] = $v_order->status_livrison;              
                
                $history_status = HistoryStatu::where('id_lead',$v_order->id)->latest()->first();
                Log::info('Ctt Tracking: '.$v_order->tracking.' - '.$v_order->status_livrison);
                if($history_status->status == $data['status']){
                        continue;
                }else{
                        $v_order->last_status_delivery = new DateTime();
                        if($data['status'] == 'in transit')
                        {
                                $v_order->date_shipped = new DateTime();
                        }
                        $v_order->save();
                        HistoryStatu::insert($data);
                        if(str_contains($v_order->email, '@')){
                                // $this->emailNotify($v_order,$status);
                        }
                        
                }
                
       
        }

   }

   public function loginApi()  
   {
       $data = [
           'client_id' => '1s9gfn08a5du1jm116ib9pi3lj',
           'client_secret' => 'j8hj0tdhv33kg5hbrvbajrullgr098ce11vksgrs3as4b6s78tu',
           'scope' => 'urn:com:ctt-express:integration-clients:scopes:common/ALL',
           'grant_type' => 'client_credentials',
       ];
       $curl = curl_init();

       curl_setopt_array($curl, array(
                           CURLOPT_URL => "https://es-ctt-integration-clients-pool-ids.auth.eu-central-1.amazoncognito.com/oauth2/token",
                           CURLOPT_RETURNTRANSFER => true,
                           CURLOPT_ENCODING => "",
                           CURLOPT_MAXREDIRS => 10,
                           CURLOPT_TIMEOUT => 30,
                           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                           CURLOPT_CUSTOMREQUEST => "POST",
                           CURLOPT_POSTFIELDS => http_build_query($data),
                           CURLOPT_HTTPHEADER => array(
                               "Content-type: application/x-www-form-urlencoded"
                           ),
       ));
       $response = json_decode(curl_exec($curl), true);
       
       $err = curl_error($curl);
      
       curl_close($curl);
      
       if ($response)
       {
            return $response['access_token']; 

       }else return false;
       
   }
  

   public function removeDuplicate()
   {
        $orders = Lead::where('tracking','!=', Null)
                        ->where('status_confirmation','confirmed')
                        ->where('shipping_company','ctt')
                        ->whereNotIn('status_livrison',['picking proccess','unpacked','item packed','shipped'])
                        ->get();
        foreach ($orders as $v_order) {
                $statusesToKeep = ['proseccing', 'in transit','in delivery']; // Add other statuses if needed
                        
                foreach ($statusesToKeep as $status) {
                        $historyStatuses = HistoryStatu::where('id_lead', $v_order->id)
                                                                    ->where('status', $status)
                                                                    ->get();
                        
                        if ($historyStatuses->count() > 1) {
                                        // Keep the first one and delete the rest
                                $firstHistoryStatus = $historyStatuses->shift();
                                                
                                foreach ($historyStatuses as $duplicateStatus) {
                                        $duplicateStatus->delete();
                                }
                        }
                }
        }
   }
}