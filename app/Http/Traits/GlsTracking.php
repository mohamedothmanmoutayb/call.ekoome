<?php
namespace App\Http\Traits;
use App\Models\Lead;
use App\Models\HistoryStatu;
use DateTime;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\EmailTracking;
trait GlsTracking {
   use EmailTracking;

   public function glstracking(){


        $orders = Lead::where('tracking','!=', Null)
                        ->where('status_confirmation','confirmed')
                        ->where('shipping_company','gls')
                        ->whereNotIn('status_livrison',['returned','picking proccess','delivered','unpacked','item packed'])
                        ->get();
        

        $processing = ['901','91'];

        $redeployment = ['32','16'];

        $incident = ['81','80','1800','1900','2600','76','75','73','72','71','70',
                     '69','68','66','67','59','49','39','33','30','24'
                     ,'15','13','12','10','8','7','6','5','3','1','910'];
                                        
        $intransit = ['904','94','903','93','902','92','909','907','25','26','27'];
                
        $indelivery = ['95','905','77','74','35','34','31','28','23','22','19','17','14'];
                
        $rejected = ['82','29','4'];
                
        $delivered  = ['906','96'];

        $curl = curl_init();

        foreach($orders as $v_order){
                
                $tracking = $v_order->tracking;
                $tracking = preg_replace("/[^0-9]/", "", $v_order->tracking);
                $tracking = trim($tracking);
                $url ='https://infoweb.gls-italy.com/XML/get_xml_track.php?locpartenza=TW&NumSped='.$tracking.'&Cli=234198';

                // Initialize cURL
                $ch = curl_init($url);

                // Set to receive the response as a string
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                // Execute the request and capture the response
                $response = curl_exec($ch);
                
                $xml = simplexml_load_string($response);
                $status = '';
                if (isset($xml->TESTOERRORE)) {
                        continue;
                } else{
                        $trackings = [];
                        foreach ($xml->SPEDIZIONE->TRACKING as $tracking) {
                                $trackings[] = [
                                        'Data' => (string) $tracking->Data,
                                        'Ora' => (string) $tracking->Ora,
                                        'Luogo' => (string) $tracking->Luogo,
                                        'Stato' => (string) $tracking->Stato,
                                        'Note' => (string) $tracking->Note,
                                        'Codice' => (string) $tracking->Codice, 
                                ];
                        }
                        if($trackings){                   
                            $event = current($trackings);
                            $status = $event['Codice'];
                            if(in_array($status,$processing)){
                                $v_order->status_livrison = 'proseccing';                    
                            }     
                            elseif(in_array($status,$indelivery)){
                                $status = 'in delivery';
                                    $v_order->status_livrison = 'in delivery';}
                            elseif(in_array($status,$intransit)){
                                $status = 'in transit'; 
                                    $v_order->status_livrison = 'in transit';} 
                            elseif(in_array($status,$incident)) {
                                $status = 'incident'; 
                                    $v_order->status_livrison = 'incident';}
                            elseif(in_array($status,$rejected)){  
                                    $status = 'rejected';                              
                                    $v_order->status_livrison = 'rejected'; 
                                    $tracking_r = (string)$xml->SPEDIZIONE->Note;
                                    $substring = "Sp.N.";
                                    $start = strpos($tracking_r, $substring);
                                    if($start !== false) {
                                        $start += strlen($substring);
                                        $filteredString = preg_replace('/\s+/', '', substr($tracking_r, $start));
                                        $v_order->tracking_return = substr($filteredString, 0, 11);
                                    }                
                            }
                            elseif(in_array($status,$redeployment)){
                                $v_order->status_livrison = 'redeployment';}
                            elseif(in_array($status,$delivered)){
                                    $v_order->status_livrison = 'delivered';}                  
                            else{
                                    $v_order->status_livrison = $event['Codice'].' '.$event['Stato'];
                            }
                                
                        }             
                        $data = array();
                        $data['country_id'] = $v_order->id_country;
                        $data['id_lead'] = $v_order->id;
                        $data['status'] = $v_order->status_livrison;              
                        
                        $history_status = HistoryStatu::where('id_lead',$v_order->id)->latest()->first();
                        Log::info('GLS Tracking: '.$v_order->tracking.' - '.$v_order->status_livrison);
                        if($history_status->status == $data['status']){
                                if($v_order->tracking_return){   
                                        $v_order->save();
                                }
                                continue;
                        }else{
                                $v_order->last_status_delivery = new DateTime();
                                if($data['status'] == 'in transit')
                                {
                                        $v_order->date_shipped == new DateTime();
                                }
                                $v_order->save();
                                HistoryStatu::insert($data);
                                if(str_contains($v_order->email, '@')){
                                        // $this->emailNotify($v_order,$status);
                                }
                                
                        }
                }    
        }
        curl_close($curl);
        
   }

}