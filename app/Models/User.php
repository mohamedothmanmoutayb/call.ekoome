<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\NotificationSetting;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
      protected $casts = [
        'email_verified_at' => 'datetime',
        'titles' => 'array',
        'sound' => 'boolean',
    ];

    
     public function sellerLeads()
    {
        return $this->hasMany('App\Models\Lead','id_user','id');
    }
    public function roles()
    {
        return $this->hasMany('App\Models\AgentRole','id','id_agentrole');
    }

    public function rol()
    { 
        return $this->hasone('App\Models\Role','id','id_role');
    }
    
    public function role()
    {
        return $this->hasMany('App\Models\AgentRole','id','id_agentrole');
    }
    
    public function leads()
    {
        return $this->hasMany('App\Models\Lead','id_assigned','id');
    }
    
    public function lead()
    {
        return $this->hasOne('App\Models\Lead','id_assigned','id');
    }

    public function manager()
    {
        return $this->hasone('App\Models\User','id','id_manager');
    }

    public function whatsappAnalyticsPreferences()
    {
        return $this->hasOne(WhatsappAnalyticsPreference::class);
    }

    public function conversationAssignments()
    {
        return $this->hasMany(ConversationAssignment::class, 'assigned_to');
    }

    public function whatsappAccounts()
    {
        return $this->belongsToMany(WhatsAppAccount::class);
    }

    public function whatsappBusinessAccounts()
    {
        return $this->belongsToMany(WhatsAppBusinessAccount::class);
    }

      public function notificationSetting()
    {
        return $this->hasOne(NotificationSetting::class);
    }
 
    public function scopeCheckLeads($query,$id,$d1,$d2)
    {  
        return  $query->join('history_status', function($join)
        {
            $join->on('users.id', '=', 'history_status.id_user');
        }) 
        ->where('users.id',$id)->whereBetween('history_status.date',   [$d1." 00:00:00" , $d2." 23:59:59"])

        ->select('users.id','name',DB::raw('count(history_status.id_lead) as total_lead'))
        ->having('total_lead', '>', 0)
        ->groupBy('users.name','users.id');        
    } 

    public function scopeNbrLeads($query) 
    {
        return $query->join('history_status', function($join)
        {
            $join->on('users.id', '=', 'history_status.id_user');
        })->count();
    } 
    
    public function scopeVerifyLeads($query,$id,$d1,$d2){
        return  $query->join('history_status', function($join)
        { 
         $join->on('users.id', '=', 'history_status.id_user'); 
        })
        ->where('users.id',$id)->whereBetween('history_status.date',    [$d1." 00:00:00" , $d2." 23:59:59"])
        ->distinct('history_status.id_lead') ; 
       
        // dd($query->toSql(), $query->getBindings()); 
    }

    public function scopeCountLeads($query,$id,$d1,$d2){
        return $query->join('history_status', function($join)
        {
         $join->on('users.id', '=', 'history_status.id_user'); 
        })
        ->where('users.id',$id)->whereBetween('history_status.date',      [$d1." 00:00:00" , $d2." 23:59:59"])
        ->distinct('history_status.id_lead')  
        ->count();  
 
        dd($query->toSql(), $query->getBindings()); 
    }

    public function scopeCountCalls($query,$id,$d1,$d2){
        return $query->join('history_status', function($join)
        {
         $join->on('users.id', '=', 'history_status.id_user'); 
        })
        ->where('users.id',$id)->whereBetween('history_status.date',      [$d1." 00:00:00" , $d2." 23:59:59"])
        ->count();   
        dd($query->toSql(), $query->getBindings()); 
    }
 


    public function scopeCountTypeCall($query,$id,$d1,$d2,$type){
        if($type == 'call later'){
            return  $query->join('history_status', function($join)
            {
             $join->on('users.id', '=', 'history_status.id_user');  
            })
            ->where('users.id',$id)->whereBetween('history_status.created_at',[$d1." 00:00:00" , $d2." 23:59:59"])
            ->where('history_status.status', 'LIKE', '%' . $type . '%')
            ->orderby('history_status.created_at') 
            ->get('history_status.status');  
        }
        else { 
            return  $query->join('history_status', function($join)
            {
             $join->on('users.id', '=', 'history_status.id_user'); 
            })
            ->where('users.id',$id)->whereBetween('history_status.date',[$d1." 00:00:00" , $d2." 23:59:59"])
            ->where('history_status.status', 'LIKE', '%' . $type . '%')
            ->orderby('history_status.date')
            ->get('history_status.status');  
        }
       
            $Calltype = clone $query;
            $Calltype = $Calltype->distinct('history_status.date')->get(); 
          
        //   return $Calltype; 
        // $Calltype->max('history_status.date');  
        dd($Calltype, $query->get()); 
    }
    
    public function scopeRate($query,$id,$d1,$d2,$type){
       
        if($type == 'confirmed' )  
        {
            $call = 'confirmed';
        }
        elseif($type == 'canceled' )
        {
            $call = 'canceled';
        }
      
        $global = $query->join('history_status', function($join)
                    {
                    $join->on('users.id', '=', 'history_status.id_user'); 
                    })
                    ->where('users.id',$id)->whereBetween('history_status.created_at',      [$d1." 00:00:00" , $d2." 23:59:59"]);
        $Calltype = clone $global;
        $Calltype = $Calltype->where('history_status.status', 'LIKE', '%' . $call . '%')->whereBetween('history_status.created_at',      [$d1." 00:00:00" , $d2." 23:59:59"])->count(); 
        if($global->whereIn('status',['confirmed','canceled'])->count() == 0 ) 
        {   
            $pourcentage='0';  
        }
        else
        {
            $pourcentage = number_format(($Calltype / $global->whereIn('status',['confirmed','canceled'])->whereBetween('history_status.created_at',      [$d1." 00:00:00" , $d2." 23:59:59"])->count()) * 100 ,0); 
        }
        return $pourcentage.'%';  
    
   
}

    public function fees(){
        return $this->HasMany('App\Models\CountrieFee','id_user','id');
    }
    
    public function leadss()
    {
        return $this->hasMany('App\Models\Lead','id_user','id');
    }
    
    public function country()
    {
        return $this->hasMany('App\Models\Countrie','id','country_id');
    }

    public function leadlivreur()
    {
        return $this->hasMany('App\Models\Lead','livreur_id','id');
    }

    public function leadlivreurs()
    {
        return $this->hasMany('App\Models\Lead','livreur_id','id');
    }
    
    public function History()
    {
        return $this->hasMany('App\Models\HistoryStatu','id_delivery','id');
    }

    public function Session() 
    {
        return $this->hasMany('App\Models\Session','seller_id','id');
    }

    public function scopeCountDelivered($query,$id,$d1,$d2)
    {
        if($d1 == $d2){
            $confirmed = $query->join('history_status', function($join)
            {
             $join->on('users.id', '=', 'history_status.id_user'); 
            })
            ->where('users.id',$id)->where('history_status.date',$d1." 00:00:00")
            ->where('history_status.status', 'LIKE', '%' . 'confirmed' . '%')
            ->orderby('history_status.date')
            ->get('history_status.status');
        }else{
            $confirmed = $query->join('history_status', function($join)
            {
             $join->on('users.id', '=', 'history_status.id_user'); 
            })
            ->where('users.id',$id)->whereBetween('history_status.date',[$d1." 00:00:00" , $d2." 23:59:59"])
            ->where('history_status.status', 'LIKE', '%' . 'confirmed' . '%')
            ->orderby('history_status.date')
            ->get('history_status.status');
        }
        $count = 0;
        foreach($confirmed as $v_confirmed){
            $conf = Lead::where('id',$v_confirmed->id)->where('status_livrison','delivered')->first();
            if(!empty($conf->id)){
                $count = $count + 1;
            }else{
                $count = 0;
            }
        }
        return $count;
    }
    public function scopeLastinvoice($query,$id)
    {
        $id = $this->id;
        $last = Invoice::where('id_user',$id)->where('id_warehouse', Auth::user()->country_id)->orderby('id','desc')->first();

        if(!empty($last->transaction)){
            return $last->transaction;
        }else{
            return 0;
        }
        
    }

    public function ScopeSellerdelete($query,$id)
    {
        $seller = User::where('id',$id)->first();
        $store = Store::where('id_user',$seller->id)->first();
        if(!empty($store->id)){
            $product = Product::where('id_user',$seller->id)->first();
            if(!empty($product->id)){
                $listproduct = Product::where('id_user', $id)->select('id')->get()->toarray();
                $import = Import::wherein('id_product',$listproduct)->wherein('status',['pending','confirmed'])->count();
                if($import != 0){
                    $checklead = Lead::where('id_user',$seller->id)->count();
                    if($checklead != 0 ){
                        return 0;
                    }else{
                        return 0;
                    }
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }
        return 1;
        
    }

    public function ScopeLeadConfirmed($query,$id,$date_1_call , $date_2_call)
    {
        $check = Lead::where('id_assigned',$id)->where('status_confirmation','confirmed');
        if($date_1_call == $date_2_call){
            $check = $check->where('last_status_change',date('Y-m-d', strtotime($date_1_call)));
        }else{
            $check = $check->where('last_status_change','>=',date('Y-m-d', strtotime($date_1_call)))->where('last_status_change','<=',date('Y-m-d', strtotime($date_2_call)));
        }
        $check = $check->count();
        
        return $check;
    }

    public function ScopeLeadNoAnswer($query,$id,$date_1_call , $date_2_call)
    {
        $check = Lead::where('id_assigned',$id)->where('status_confirmation','no answer');
        if($date_1_call == $date_2_call){
            $check = $check->where('last_status_change',date('Y-m-d', strtotime($date_1_call)));
        }else{
            $check = $check->where('last_status_change','>=',date('Y-m-d', strtotime($date_1_call)))->where('last_status_change','<=',date('Y-m-d', strtotime($date_2_call)));
        }
        $check = $check->count();
        
        return $check;
    }

    public function ScopeLeadCancelled($query,$id,$date_1_call , $date_2_call)
    {
        $check = Lead::where('id_assigned',$id)->where('status_confirmation','canceled');
        if($date_1_call == $date_2_call){
            $check = $check->where('last_status_change',date('Y-m-d', strtotime($date_1_call)));
        }else{
            $check = $check->whereDate('last_status_change','>=',date('Y-m-d', strtotime($date_1_call)))->whereDate('last_status_change','<=',date('Y-m-d', strtotime($date_2_call)));
        }
        $check = $check->count();
        
        return $check;
    }

    public function ScopeLeadCalllater($query,$id,$date_1_call , $date_2_call)
    {
        $check = Lead::where('id_assigned',$id)->where('status_confirmation','call later');
        if($date_1_call == $date_2_call){
            $check = $check->where('last_status_change',date('Y-m-d', strtotime($date_1_call)));
        }else{
            $check = $check->where('last_status_change','>=',date('Y-m-d', strtotime($date_1_call)))->where('last_status_change','<=',date('Y-m-d', strtotime($date_2_call)));
        }
        $check = $check->count();
        
        return $check;
    }

    public function ScopeLeadwrong($query,$id,$date_1_call , $date_2_call)
    {
        $check = Lead::where('id_assigned',$id)->where('status_confirmation','wrong');
        if($date_1_call == $date_2_call){
            $check = $check->where('last_status_change',date('Y-m-d', strtotime($date_1_call)));
        }else{
            $check = $check->where('last_status_change','>=',date('Y-m-d', strtotime($date_1_call)))->where('last_status_change','<=',date('Y-m-d', strtotime($date_2_call)));
        }
        $check = $check->count();
        
        return $check;
    }

    public function ScopeQuanityConfirmed($query,$id,$date_1_call , $date_2_call)
    {
        $check = Lead::where('id_assigned',$id)->where('status_confirmation','confirmed');
        if($date_1_call == $date_2_call){
            $check = $check->where('last_status_change',date('Y-m-d', strtotime($date_1_call)));
        }else{
            $check = $check->where('last_status_change','>=',date('Y-m-d', strtotime($date_1_call)))->where('last_status_change','<=',date('Y-m-d', strtotime($date_2_call)));
        }
        $check = $check->groupby('quantity')->select(DB::raw('count(id) as count'),'quantity')->get();
        // if(!$check->isempty()){
        //     foreach($check as $v_check){
        //         $data[] = '<span class="badge bg-success">quantity :' . $v_check['quantity']. '- Count :' .$v_check['count'].'</span>';
        //     }
        // }
        // if(empty($data)){
        //     $data = "10";
        // }
        return $check;
    }

    public function ScopeQuanityDelivered($query,$id,$date_1_call , $date_2_call)
    {
        $check = Lead::where('id_assigned',$id)->where('status_confirmation','confirmed')->where('status_livrison','delivered');
        if($date_1_call == $date_2_call){
            $check = $check->where('last_status_change',date('Y-m-d', strtotime($date_1_call)));
        }else{
            $check = $check->where('last_status_change','>=',date('Y-m-d', strtotime($date_1_call)))->where('last_status_change','<=',date('Y-m-d', strtotime($date_2_call)));
        }
        $check = $check->groupby('quantity')->select(DB::raw('count(id) as count'),'quantity')->get();

        return $check;
    }

    public function ScopeRevenue($query,$id,$date_1_call , $date_2_call)
    {
        $check = Lead::where('id_assigned',$id)->where('status_confirmation','confirmed')->where('status_livrison','delivered');
        if($date_1_call == $date_2_call){
            $check = $check->where('last_status_change',date('Y-m-d', strtotime($date_1_call)));
        }else{
            $check = $check->where('last_status_change','>=',date('Y-m-d', strtotime($date_1_call)))->where('last_status_change','<=',date('Y-m-d', strtotime($date_2_call)));
        }
        $check = $check->sum('lead_value');

        return $check;
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function latestHistory()
    {
        return $this->hasMany(HistoryStatu::class, 'id_user')->latest();
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // public function hasUnreadMessages(Conversation $conversation = null)
    // {
    //     if ($conversation) {
    //         return $this->conversations()
    //             ->where('conversation_id', $conversation->id)
    //             ->where(function($q) {
    //                 $q->whereNull('last_read_at')
    //                 ->orWhere('last_read_at', '<', $this->conversations()
    //                     ->where('conversation_id', $conversation->id)
    //                     ->first()
    //                     ->updated_at
    //                 );
    //             })
    //             ->exists();
    //     }

    //     return $this->conversations()
    //         ->where(function($q) {
    //             $q->whereNull('last_read_at')
    //             ->orWhere('last_read_at', '<', 'conversations.updated_at');
    //         })
    //         ->exists();
    // }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where('is_active', 1)
            ->where('end_date', '>', now())
            ->first();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
