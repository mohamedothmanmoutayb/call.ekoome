<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\Notification;
use Pusher\Pusher;
use DB;
use App\Models\ShippingCompany;

class Lead extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'note','status_confirmation', 'city', 'address', 'phone', 'phone2','quantity', 
    'warehouse_id', 'lead_value',"old_lead_value", 'created_at','id_assigned',"discount",
    "n_lead",
    "id_city",
    "created_at",
    "delivered_at",
    "id_user",
    "id_country",
    "market",
    "method_payment",
    "id_product",
    "id_assigned",
    "id_zone",
];
    
    public function softdelete()
    {
        return $this->where('deleted_at', '0');
    }
    
    public function country()
    {
        return $this->hasMany('App\Models\Countrie','id','id_country');
    }

    public function shippingcompany() {
        return $this->belongsTo(ShippingCompany::class,'id_last_mille');
    }
    
    public function cities()
    {
        return $this->hasMany('App\Models\Citie','id','id_city');
    }
    
    public function product()
    {
        return $this->hasMany('App\Models\Product','id','id_product');
    }
    
    public function leadproduct()
    {
        return $this->hasMany('App\Models\LeadProduct','id_lead','id');
    }
    
    public function leadpro()
    {
        return $this->hasOne('App\Models\LeadProduct','id_lead','id')->where('isupsell','0');
    }
    
    public function leadproducts()
    {
        return $this->hasMany('App\Models\Product','id','id_product');
    }
    
    public function leadbyvendor()
    {
        return $this->hasMany('App\Models\User','id','id_user');
    }
    
    public function livreur()
    {
        return $this->hasMany('App\Models\User','id','livreur_id');
    }

    public function call()
    {
        return $this->hasMany('App\Models\User','id','id_assigned');
    }

    public function historystatu()
    {
        return $this->hasMany('App\Models\HistoryStatu','id_lead','id');
    }

    public function scopeDate($query , $dat)
    {
        return $query->whereDate('last_status_change',$dat);
    }

    public function stock()
    {
        return $this->hasone('App\Models\Stock','id_product','id_product');
    }

    public function assigned()
    {
        return $this->hasone('App\Models\User','id','id_assigned');
    }

    public function scopeListOforder($query,$id)
    {
        $lead = Lead::where('id',$id)->first();
        $count = Lead::where('phone',$lead->phone)->where('deleted_at',0)->count();

        return $count;
    }


//      protected static function booted()
//     {
//         static::updated(function ($lead) {
//             if ($lead->wasChanged('status_confirmation')) {
//                $newStatus = $lead->status_confirmation;
//              $user = Auth::user();

//             $roleId = $user->id_role;

//            if ($roleId == 3 && in_array($newStatus, ['returned', 'rejected'])) {
//             $notification = Notification::create([
//                 'user_id' => $user->id_role,
//                 'type' => $newStatus, 
//                 'title' => 'Order status updated',
//                 'message' => "Order has been {$newStatus}.",
//                 'is_read' => false,
//                 'payload' => $lead->toJson(),
//             ]);

//             $this->triggerPusherNotification($user->id_role, $notification);
//         }

//         if ($roleId == 4 && in_array($newStatus, ['returned', 'rejected', 'canceled'])) {
//             $notification = Notification::create([
//                 'user_id' => $user->id_role,
//                 'type' => $newStatus, 
//                 'title' => 'Order status updated',
//                 'message' => "Order has been {$newStatus}.",
//                 'is_read' => false,
//                 'payload' => $lead->toJson(),
//             ]);

//             $this->triggerPusherNotification($user->id_role, $notification);
//         }


//             }
//         });

        
//     }
    
//      private function triggerPusherNotification($userId, $notification)
// {
//     $options = [
//         'cluster' => env('PUSHER_APP_CLUSTER'),
//         'useTLS' => true
//     ];

//     $pusher = new Pusher(
//         env('PUSHER_APP_KEY'),
//         env('PUSHER_APP_SECRET'),
//         env('PUSHER_APP_ID'),
//         $options
//     );

//     $data = [
//         'notification_id' => $notification->id,
//         'title' => $notification->title,
//         'message' => $notification->message,
//         'type' => $notification->type,
//         'payload' => json_decode($notification->payload),
//         'is_read' => $notification->is_read,
//         'time' => $notification->created_at,
//     ];

//     $pusher->trigger('user.' . $userId, 'Notification', $data);
// }


    public function ScopeCountDelivered($query, $id , $date_from , $date_two)
    {
        if($date_from == $date_two){
            return Lead::where('id_assigned',$id)->where('status_confirmation','confirmed')->where('status_livrison','delivered')->where('id_country', Auth::user()->country_id)->whereDate('created_at','=',$date_from)->count();
        }else{
            return Lead::where('id_assigned',$id)->where('status_confirmation','confirmed')->where('status_livrison','delivered')->where('id_country', Auth::user()->country_id)->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)))->count();
        }
        
    }

    public function ScopeProductName($query,$id)
    {
        $id = $this->id;
        $lead = Lead::where('id',$id)->first();
        $product = Product::where('id',$lead->id_product)->first();
        if(!empty($product)){
            return $product->name;
        }else{
            return "-";
        }
    }

    public function ScopeCitieName($query,$id)
    {
        $id = $this->id;
        $lead = Lead::where('id',$id)->first();
        $citie = Citie::where('id',$lead->id_city)->first();
        if(!empty($citie)){
            return (string)$citie->name;
        }else{
            return (string)$lead->city;
        }
    }

    public function ScopeTotalQuantity($query,$id)
    {
        $id = $this->id;
        $lead = Lead::where('id',$id)->first();
        $leadprod = LeadProduct::where('id_lead',$lead->id)->sum('quantity');
        if(!empty($leadprod)){
            return $leadprod;
        }else{
            return 0;
        }
    }

     public function ScopeQuanityDelivered($query , $date_1_call , $date_2_call , $agent , $quantity, $call_product)
    {
        $check = Lead::where('id_assigned',$agent)->where('quantity',$quantity)->where('status_confirmation','confirmed')->where('status_livrison','delivered');
        if($date_1_call == $date_2_call){
            $check = $check->whereDate('created_at',date('Y-m-d', strtotime($date_1_call)));
        }else{
            $check = $check->whereDate('created_at','>=',date('Y-m-d', strtotime($date_1_call)))->whereDate('created_at','<=',date('Y-m-d', strtotime($date_2_call)));
        }
        if($call_product){
            $check = $check->where('id_product',$call_product);
        }
        $check = $check->groupby('lead_value')->select(DB::raw('count(id) as count'),'lead_value')->get();

        return $check;
    }

    public function ScopePriceConfirmed($query , $date_1_call , $date_2_call , $agent , $quantity, $call_product)
    {
        $check = Lead::where('id_assigned',$agent)->where('quantity',$quantity)->where('status_confirmation','confirmed');
        if($date_1_call == $date_2_call){
            $check = $check->whereDate('created_at',date('Y-m-d', strtotime($date_1_call)));
        }else{
            $check = $check->whereDate('created_at','>=',date('Y-m-d', strtotime($date_1_call)))->whereDate('created_at','<=',date('Y-m-d', strtotime($date_2_call)));
        }
        if($call_product){
            $check = $check->where('id_product',$call_product);
        }
        $check = $check->groupby('lead_value')->select(DB::raw('count(id) as count'),'lead_value')->get();

        return $check;
    }

    public function ScopeLeadCount($query , $type , $city , $date , $market , $product)
    {
        $lead  = Lead::where('id_user',Auth::user()->id);
        if($type){
            $lead = $lead->where('status_confirmation','LIKE','%'.$type.'%');
        }
        if($product){
            $lead = $lead->where('id_product',$product);
        }
        if($date){
            $parts = explode(' - ' , $date);
            $date_from = $parts[0];
            if(empty($parts[1])){
                $lead = $lead->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $date_two = $parts[1];
                if($date_two == $date_from){
                    $lead = $lead->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
                }else{
                    $lead = $lead->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
                }
            }
        }
        if($market){
            $lead = $lead->where('market',$market);
        }
        $lead = $lead->where('deleted_at',0)->count();
        return $lead;
    }

    public function ScopeOrderCount($query , $type , $city , $date , $market , $product)
    {
        $lead  = Lead::where('id_user',Auth::user()->id)->where('status_confirmation','confirmed');
        if($type){
            $lead = $lead->where('status_livrison','LIKE','%'.$type.'%');
        }
        if($product){
            $lead = $lead->where('id_product',$product);
        }
        if($date){
            $parts = explode(' - ' , $date);
            $date_from = $parts[0];
            if(empty($parts[1])){
                $lead = $lead->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
            }else{
                $date_two = $parts[1];
                if($date_two == $date_from){
                    $lead = $lead->whereDate('created_at','=',date('Y-m-d' , strtotime($date_from)));
                }else{
                    $lead = $lead->whereDate('leads.created_at','>=',date('Y-m-d' , strtotime($date_from)))->whereDate('leads.created_at','<=',date('Y-m-d' , strtotime($date_two)));
                }
            }
        }
        if($market){
            $lead = $lead->where('market',$market);
        }
        $lead = $lead->where('deleted_at',0)->count();
        return $lead;
    }



    public function seller()
    {
        return $this->hasone('App\Models\User','id','id_user');
    }
}
