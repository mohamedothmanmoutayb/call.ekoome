<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Countrie;
use App\Models\Error;
use Auth;
use App\Observers\LeadObserver;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        require_once __DIR__ . '/../Helper/helper.php'; 

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         if (Auth::check()) {
        app()->instance('CurrentUser', Auth::user());
       }

        Lead::observe(LeadObserver::class);
        view()->composer('*', function($view)
        {
            if (Auth::check()) {
                $totalnewleads = Lead::where('leads.type','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','new order')->where('ispaidapp','!=','1')->where('deleted_at',0)->count();
                $totalnoanswer = Lead::where('leads.type','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','like','%'.'no answer'.'%')->where('deleted_at',0)->count();
                $totalcalll = Lead::where('leads.type','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','call later')->where('status_livrison','unpacked')->where('deleted_at',0)->count();
                // $totalwrongleads = Lead::where('leads.type','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','wrong')->where('deleted_at',0)->count();
                // $totalduplicatedleads = Lead::where('leads.type','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','duplicated')->where('deleted_at',0)->count();
                // $totalhorzoneleads = Lead::where('leads.type','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','out of area')->where('deleted_at',0)->count();
                // $totalrejectedleads = Lead::where('leads.type','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','canceled')->where('deleted_at',0)->count();
                // $totaloutofstockleads = Lead::where('leads.type','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','outofstock')->where('deleted_at',0)->count();
                // $totalrsystemleads = Lead::where('leads.type','seller')->where('id_country', Auth::user()->country_id)->where('status_confirmation','canceled by system')->where('deleted_at',0)->count();
                $totalprepaidleads = Lead::where('leads.type','seller')->where('id_country', Auth::user()->country_id)->where('ispaidapp','1')->where('status_confirmation','new order')->where('deleted_at',0)->count();
                $currency = Countrie::where('id', Auth::user()->country_id)->first();
               
                $user = Auth::user();
                
                $notifications = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->take(100)
                ->get();

                $errors = Error::where('id_country', Auth::user()->country_id)->orderBy('created_at', 'desc')->get();
                $view->with(['totalnewleads' => $totalnewleads ,'totalprepaidleads' => $totalprepaidleads  ,'totalnoanswer' => $totalnoanswer , 'totalcalll' => $totalcalll ,'currency' => $currency , 'notifications' => $notifications, 'errors' => $errors]);

            }else {
                $view->with('currentUser', null);
            }
        });
    }
}
