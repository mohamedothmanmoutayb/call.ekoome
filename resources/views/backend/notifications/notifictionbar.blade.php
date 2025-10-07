 <style> 
 @media (max-width: 768px) {
    #notif-list .notification-item {
        font-size: 13px;
    }
    .notification-item {
        padding-left: 15px;
        padding-right: 15px;
    }

    #notif-list h6 {
        font-size: 14px;
    }

    #notif-list span {
        font-size: 12px;
    }
}
 @media (min-width: 768px) {
  
    .notification-item {
        padding-left: 20px;
        padding-right: 0px;
    }

   
}
 </style>
 

  
  <div id="notif-list"
       class="notif-list 
       d-flex flex-column message-body w-100 overflow-auto
                                                data-simplebar" >
       @foreach ($notifications as $index => $notification)
           @php

               $type = $notification->type ?? null;

               $typeIcon = match ($type) {
                   'returned' => asset('public/cancel.png'),
                   'canceled' => asset('public/cancel.png'),
                   'rejected' => asset('public/cancel.png'),
                   default => null,
               };

               $iconPath = $iconPath ?? $typeIcon;

               $payload = json_decode($notification->payload, true);
               $leadId = $payload['id'] ?? null;
           @endphp

           <a href="{{ $leadId ? route('leads.edit', $leadId) : '#' }}"
               class="py-6 d-flex align-items-center dropdown-item gap-3  notification-item"
               data-index="{{ $index }}" data-notification-id="{{ $notification->id }}"
               data-is-read="{{ $notification->is_read }}">
               <span
                   class="flex-shrink-0 bg-light rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                   @if ($iconPath)
                       <img src="{{ $iconPath }}" style="width: 24px; height: 24px;">
                   @else
                       <iconify-icon icon="solar:widget-3-line-duotone" class="fs-6"></iconify-icon>
                   @endif
               </span>
               <div class="w-75">
                   <div class="d-flex align-items-center justify-content-between">
                       <h6 class="mb-1 fw-semibold ">
                           {{ $notification->title }}</h6>
                        <span class="d-none d-sm-block fs-2 ">
                            @if ($notification->created_at->diffInHours(now()) < 24)
                                {{ $notification->created_at->format('H:i') }}
                            @else
                                {{ $notification->created_at->diffForHumans() }}
                            @endif
                        </span>

                        <span class="d-block d-sm-none fs-2 " style="margin-left:35px;">
                            @php
                                $createdAt = $notification->created_at;
                                $now = now();
                                if ($now->diffInHours($createdAt) < 24) {
                                    echo $createdAt->format('H:i');
                                } elseif ($now->diffInDays($createdAt) < 7) {
                                    echo $now->diffInDays($createdAt) . 'd';
                                } elseif ($now->diffInWeeks($createdAt) < 4) {
                                    echo $now->diffInWeeks($createdAt) . 'w';
                                } elseif ($now->diffInMonths($createdAt) < 12) {
                                    echo $now->diffInMonths($createdAt) . 'm';
                                } else {
                                    echo $now->diffInYears($createdAt) . 'y';
                                }
                            @endphp
                        </span>

                   </div>
                   <span class="d-block text-truncate ">{{ $notification->message }} </span>
               </div>
           </a>
       @endforeach
