<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Google\Service\BeyondCorp\Resource\V;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\NotificationSetting;


class NotificationController extends Controller
{


//  public function index(Request $request)
//     {
//         $user = Auth::user();
//         $allnotifications = Notification::where('user_id', $user->id)
//             ->orderBy('created_at', 'desc')
//             ->get();
            

//         return view('backend.notifications.notif', compact('allnotifications'));
//     }

public function index(Request $request)
{
    $user = Auth::user();
    
    $query = Notification::where('user_id', $user->id);
    
    if ($request->has('start_date') && $request->start_date) {
        log::info('Start date: ' . $request->start_date);
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->has('end_date') && $request->end_date) {
        Log::info('End date: ' . $request->end_date);
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    if ($request->has('type') && is_array($request->type)) {
        $query->whereIn('type', $request->type);
    }
    $allnotifications = $query->orderBy('created_at', 'desc')->paginate(15);

    return view('backend.notifications.notif', compact('allnotifications', 'user'));
}

  public function storePreferences(Request $request)
    {

         Log::channel('notifications')->info('Save settings request data:', $request->all());
        $validated = $request->validate([
            'sound' => 'required|boolean',
            'titles' => 'nullable|array',
            'titles.*' => 'string'
        ]);

        $user = Auth::user();

        NotificationSetting::updateOrCreate(
            ['user_id' => $user->id],
            [
               'sound' => $validated['sound'],
            'titles' => $validated['titles'] ?? []
            ]
        );

        return response()->json(['message' => 'Notification settings saved.']);
    }


public function getPreferences()
{
    $user = Auth::user();
    $settings = $user->notificationSetting;
    $roleId = $user->id_role;

   
    if (!$settings) {
        $defaultTitles = ['returned', 'rejected','delivered'];
       if ($roleId == 4) {
    $defaultTitles = array_merge($defaultTitles, ['canceled', 'wrong']);
}

        return response()->json([
            'sound' => true,
            'titles' => $defaultTitles,
        ]);
    }

    return response()->json([
        'sound' => $settings->sound ,
        'titles' => $settings->titles,
    ]);
    
}
  public function markAllAsRead(Request $request)
{
    $user = Auth::user();
    $notificationIds = $request->input('notifications'); 

    if (empty($notificationIds)) {
        return response()->json(['message' => 'No notifications provided'], 400);
    }

    Notification::whereIn('id', $notificationIds)
                ->where('user_id', $user->id)
                ->update(['is_read' => true]);

    return response()->json(['message' => 'All selected notifications marked as read'], 200);
}
  public function list()
    {

        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $count = $notifications->count();

    return view('backend.notifications.notifictionbar', compact('notifications','count'));

        }


       public function fetch()
{
       $user = Auth::user();
        $notificationss = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $count = $notificationss->count();

 return response()->json([
        'count' => $count,
    ]);
}

public function destroy($id)
{
    $notification = Notification::findOrFail($id);
    $notification->delete();

    if (request()->ajax()) {
        return response()->json(['message' => 'Notification deleted']);
    }

    return redirect()->back()->with('success', 'Notification deleted');
}

    public function deleteAll()
    {
        try {
          $userId = auth()->id();

        Notification::where('user_id', $userId)->delete();

            return response()->json(['message' => 'All notifications deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete notifications.'], 500);
        }
    }

//     public function showNotifications()
// {
//     $allnotifications = Notification::paginate(10);  
//     return view('notifications.index', compact('allnotifications'));
// }


   }

 