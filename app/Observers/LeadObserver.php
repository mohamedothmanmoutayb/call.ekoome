<?php

namespace App\Observers;

use App\Models\Lead;
use App\Models\Notification;
use Pusher\Pusher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\User;


class LeadObserver
{
    /**
     * Handle the Lead "created" event.
     *
     * @param  \App\Models\Lead  $lead
     * @return void
     */
    public function created(Lead $lead)
    {
        //                                                                                                                      
    }

    /**
     * Handle the Lead "updated" event.
     *
     * @param  \App\Models\Lead  $lead
     * @return void
     */
   public function updated(Lead $lead)
    {
       if ($lead->wasChanged('status_confirmation')) {
            
            $message = 'Status confirmation changed for lead ID: ' . $lead->id .' and '. $lead->id_assigned . "\n";
            File::append(storage_path('logs/custom-lead.log'), $message);
                $newStatus = $lead->status_confirmation;
                $user = User::find($lead->id_assigned); 
                if (!$user) {
                        File::append(storage_path('logs/custom-lead.log'), "User not found for lead ID: {$lead->id}\n");
                        return;
                    }

                $roleId = $user->id_role;

                $settings = $user->notificationSetting;

               $allowedStatuses = is_string($settings?->titles)
                ? json_decode($settings->titles, true)
                : ($settings->titles ?? []);
                       
              


        //    if ($roleId == 3 && in_array($newStatus, $allowedStatuses)) {
        //                         try {
        //                 $notification = Notification::create([
        //                     'user_id' => $user->id,
        //                     'type' => $newStatus, 
        //                     'title' => 'Order status updated',
        //                     'message' => "Order has been {$newStatus}.",
        //                     'is_read' => false,
        //                     'payload' => $lead->toJson(),
        //                 ]);
        //             } catch (\Exception $e) {
        //                 File::append(storage_path('logs/custom-lead.log'), "Failed to create notification: " . $e->getMessage() . "\n");
        //                 return;
        //             }
        //          $options = [
        //                 'cluster' => env('PUSHER_APP_CLUSTER'),
        //                 'useTLS' => true
        //             ];

        //             $pusher = new Pusher(
        //                 env('PUSHER_APP_KEY'),
        //                 env('PUSHER_APP_SECRET'),
        //                 env('PUSHER_APP_ID'),
        //                 $options
        //             );

        //             $data = [
        //                 'notification_id' => $notification->id,
        //                 'title' => $notification->title,
        //                 'message' => $notification->message,
        //                 'type' => $notification->type,
        //                 'payload' => json_decode($notification->payload),
        //                 'is_read' => $notification->is_read,
        //                 'time' => $notification->created_at,
        //             ];

        //             $pusher->trigger('user.' . $user->id, 'Notification', $data);
        //     }

           if ($roleId == 4 && in_array($newStatus, $allowedStatuses)){
                $notification = Notification::create([
                    'user_id' => $user->id,
                    'type' => $newStatus, 
                    'title' => 'Order status updated',
                    'message' => "Order has been {$newStatus}.",
                    'is_read' => false,
                    'payload' => $lead->toJson(),
                ]);

               $options = [
                        'cluster' => env('PUSHER_APP_CLUSTER'),
                        'useTLS' => true
                    ];

                    $pusher = new Pusher(
                        env('PUSHER_APP_KEY'),
                        env('PUSHER_APP_SECRET'),
                        env('PUSHER_APP_ID'),
                        $options
                    );

                    $data = [
                        'notification_id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'payload' => json_decode($notification->payload),
                        'is_read' => $notification->is_read,
                        'time' => $notification->created_at,
                    ];

                    $pusher->trigger('user.' . $user->id, 'Notification', $data);
            }


            }


///////////////////////////////////////////////////////////////////////////////////////////////////////////


             if ($lead->wasChanged('status_livrison')) {
            
            $message = 'Status livraison changed for lead ID: ' . $lead->id .' and '. $lead->id_assigned . "\n";
            File::append(storage_path('logs/custom-lead.log'), $message);
                $newStatus = $lead->status_livrison;
                $user = User::find($lead->id_assigned); 
                if (!$user) {
                        File::append(storage_path('logs/custom-lead.log'), "User not found for lead ID: {$lead->id}\n");
                        return;
                    }

                $roleId = $user->id_role;

                $settings = $user->notificationSetting;

               $allowedStatuses = is_string($settings?->titles)
                ? json_decode($settings->titles, true)
                : ($settings->titles ?? []);
                       
              


           if ($roleId == 3 && in_array($newStatus, $allowedStatuses)) {
                                try {
                        $notification = Notification::create([
                            'user_id' => $user->id,
                            'type' => $newStatus, 
                            'title' => 'Order status updated',
                            'message' => "Order has been {$newStatus}.",
                            'is_read' => false,
                            'payload' => $lead->toJson(),
                        ]);
                    } catch (\Exception $e) {
                        File::append(storage_path('logs/custom-lead.log'), "Failed to create notification: " . $e->getMessage() . "\n");
                        return;
                    }
                 $options = [
                        'cluster' => env('PUSHER_APP_CLUSTER'),
                        'useTLS' => true
                    ];

                    $pusher = new Pusher(
                        env('PUSHER_APP_KEY'),
                        env('PUSHER_APP_SECRET'),
                        env('PUSHER_APP_ID'),
                        $options
                    );

                    $data = [
                        'notification_id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'payload' => json_decode($notification->payload),
                        'is_read' => $notification->is_read,
                        'time' => $notification->created_at,
                    ];

                    $pusher->trigger('user.' . $user->id, 'Notification', $data);
            }

           if ($roleId == 4 && in_array($newStatus, $allowedStatuses)){
                $notification = Notification::create([
                    'user_id' => $user->id,
                    'type' => $newStatus, 
                    'title' => 'Order status updated',
                    'message' => "Order has been {$newStatus}.",
                    'is_read' => false,
                    'payload' => $lead->toJson(),
                ]);

               $options = [
                        'cluster' => env('PUSHER_APP_CLUSTER'),
                        'useTLS' => true
                    ];

                    $pusher = new Pusher(
                        env('PUSHER_APP_KEY'),
                        env('PUSHER_APP_SECRET'),
                        env('PUSHER_APP_ID'),
                        $options
                    );

                    $data = [
                        'notification_id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'payload' => json_decode($notification->payload),
                        'is_read' => $notification->is_read,
                        'time' => $notification->created_at,
                    ];

                    $pusher->trigger('user.' . $user->id, 'Notification', $data);
            }


            }
        }

        


   
    /**
     * Handle the Lead "deleted" event.
     *
     * @param  \App\Models\Lead  $lead
     * @return void
     */
    public function deleted(Lead $lead)
    {
        //
    }

    /**
     * Handle the Lead "restored" event.
     *
     * @param  \App\Models\Lead  $lead
     * @return void
     */
    public function restored(Lead $lead)
    {
        //
    }

    /**
     * Handle the Lead "force deleted" event.
     *
     * @param  \App\Models\Lead  $lead
     * @return void
     */
    public function forceDeleted(Lead $lead)
    {
        //
    }
}
