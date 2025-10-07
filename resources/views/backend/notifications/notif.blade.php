@extends('backend.layouts.app')

@section('content')
<style>
    .notification-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }

    .notification-card:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .notification-card:active {
        transform: scale(0.98);
    }
    .card-body, .card {
    overflow: visible !important;
    }
    .table-responsive {
    overflow: visible !important; 
    }
@media (max-width: 576px) {
        .notification-card {
        font-size: 12px !important;
    }

    .notification-card .fw-bold {
        font-size: 13px !important;
    }

    .notification-card p,
    .notification-card small {
        font-size: 11px !important;
    }

    .notification-card button.delete-notification {
        width: 20px !important;
        height: 20px !important;
        font-size: 16px !important;
        line-height: 10px !important;
        border-radius: 5px !important;
    }

    .notification-card .position-absolute {
        font-size: 12px !important;
    }
    }

</style>
<div class="container-fluid p-0 p-sm-3">

      <!-- Desktop Version -->
<div id="desktop-version" class="d-none">
    {{-- Your original large screen filter and layout --}} 
<div class="card card-body py-3 bg-white">
             <div class="row align-items-center ">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">Notifications ({{$allnotifications->count()}})</h4>
                    <nav aria-label="breadcrumb" class="ms-auto">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item d-flex align-items-center">
                                <a class="text-muted text-decoration-none d-flex" href="{{ route('home') }}">
                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
            <ul class="nav nav-pills p-3 mb-3 rounded align-items-center card flex-row w-100">
        <li class="nav-item">
            <a href="javascript:void(0)" onclick="toggleText()"
                class="nav-link gap-6 note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 fs-11 active"
                id="all-category">
                <i class="ti ti-list fill-white"></i>
                <span class="d-none d-md-block fw-medium">Filter</span>
            </a>
        </li>
    
            <!-- Filter Section -->
        <div class="filter-section w-100" id="multi">
            <form method="GET" action="{{ route('notifications.index') }}" id="filter-form">
                <!-- Date Range Picker -->
                <div class="row mt-3">
                    <div class="col-md-3">
                        <label for="start-date">Start Date:</label>
                        <input type="date" id="start-date" name="start_date" class="form-control" value="{{ request()->input('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end-date">End Date:</label>
                        <input type="date" id="end-date" name="end_date" class="form-control" value="{{ request()->input('end_date') }}">
                    </div>

                  <div class="col-md-6" >
                    <label for="type">Type:</label>
                    <div class="row mx-auto d-flex align-items-center justify-content-center border rounded" style="height:70%;">
                        
                        <div class="col-auto text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="returned-filter" name="type[]" 
                                    value="returned" {{ in_array('returned', request()->input('type', [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="returned-filter">Returned</label>
                            </div>
                        </div>

                        <div class="col-auto text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="canceled-filter" name="type[]" 
                                    value="canceled" {{ in_array('canceled', request()->input('type', [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="canceled-filter">Canceled</label>
                            </div>
                        </div>

                        <div class="col-auto text-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="delivered-filter" name="type[]" 
                                    value="delivered" {{ in_array('delivered', request()->input('type', [])) ? 'checked' : '' }}>
                                <label class="form-check-label text-dark" for="delivered-filter">Delivered</label>
                            </div>
                        </div>

                        @if ($user->id_role == 4)
                            <div class="col-auto text-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="rejected-filter" name="type[]" 
                                        value="rejected" {{ in_array('rejected', request()->input('type', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="rejected-filter">Rejected</label>
                                </div>
                            </div>
                            <div class="col-auto text-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="wrong-filter" name="type[]" 
                                        value="wrong" {{ in_array('wrong', request()->input('type', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark" for="wrong-filter">Wrong</label>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                </div>

                <!-- Buttons -->
                <div class="row mt-3">
                    <div class="col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Search </button>
                        <button type="button" id="reset-filters" class="btn btn-outline-secondary">Reset </button>
                    </div>
                </div>
            </form>
        </div>
    </ul>


</div>

<!-- Mobile Version -->
<div id="mobile-version" class="d-none" style="">
    {{-- The mobile-optimized version from earlier --}}



    <!-- Mobile Notification Header -->
<div class="card card-body py-2 bg-white d-sm-none w-100">
    <h5 class="card-title mb-2">Notifications</h5>
    <a href="{{ route('home') }}" class="text-muted text-decoration-none">
        <iconify-icon icon="solar:home-2-line-duotone" class="fs-5"></iconify-icon>
    </a>
</div>

<!-- Mobile Filter Toggle Button -->
<ul class="nav nav-pills p-2 mb-2 rounded card w-100 d-sm-none">
    <li class="nav-item w-100 ">
        <a href="javascript:void(0)" onclick="toggleText2()"
           class="nav-link d-flex justify-content-center align-items-center px-2 fs-12 active" id="all-category">
            <i class="ti ti-list fill-white me-1"></i> Filter
        </a>
    </li>


<!-- Mobile Filter Section -->
<div class="filter-section w-100 d-sm-none mt-3" id="multi2">
    <form method="GET" action="{{ route('notifications.index') }}" id="filter-form">
        <!-- Date Range -->
        <div class="mb-2">
            <label for="start-date" class="form-label">Start Date:</label>
            <input type="date" id="start-date" name="start_date" class="form-control"
                   value="{{ request()->input('start_date') }}">
        </div>
        <div class="mb-2">
            <label for="end-date" class="form-label">End Date:</label>
            <input type="date" id="end-date" name="end_date" class="form-control"
                   value="{{ request()->input('end_date') }}">
        </div>

        <!-- Type Checkboxes -->
        <div class="mb-3">
            <label class="form-label">Type:</label>
            <div class="d-flex flex-column gap-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="returned-filter" name="type[]"
                           value="returned" {{ in_array('returned', request()->input('type', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="returned-filter">Returned</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="canceled-filter" name="type[]"
                           value="canceled" {{ in_array('canceled', request()->input('type', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="canceled-filter">Canceled</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="delivered-filter" name="type[]"
                        value="delivered" {{ in_array('delivered', request()->input('type', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="delivered-filter">Delivered</label>
                </div>
                @if ($user->id_role == 4)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rejected-filter" name="type[]"
                               value="rejected" {{ in_array('rejected', request()->input('type', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="rejected-filter">Rejected</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="wrong-filter" name="type[]"
                            value="wrong" {{ in_array('wrong', request()->input('type', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="wrong-filter">Wrong</label>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-column gap-2">
            <button type="submit" class="btn btn-primary">Search</button>
            <button type="button" id="reset-filters2" class="btn btn-outline-secondary">Reset</button>
        </div>
    </form>
</div>
</ul>
 </div>     

    <div class="d-flex justify-content-end">
    <button id="delete-all-btn" class="btn btn-danger" style="background-color: rgb(215, 89, 89); display: none;">Delete All</button>
</div>

 
       
        <div class="card  " style="margin-top: 30px;">
            <div class="card-body p-0">
                <div class="table-responsive bg-light">
                     @if ($allnotifications->isEmpty())
                            <div class="p-4 text-center text-muted">
                                <h5>No notifications available.</h5>
                                <p>You're all caught up!</p>
                            </div>
                        @else
                    @foreach ($allnotifications as $notification)
                        @php
                            $type = $notification->type;
                            $icon = match ($type) {
                                default => '🔔',
                            };
                            $payload = json_decode($notification->payload, true);
                            $orderId = $payload['id'] ?? 'N/A';
                            $source = $payload['source'] ?? 'Unknown';
                            $date = \Carbon\Carbon::parse($notification->created_at)->diffForHumans();
                         
                        @endphp
 @if ($orderId)
 <a class="text-decoration-none text-dark">
                        <div class="posdiv position-relative border rounded p-3 mb-3 shadow-sm bg-white  notification-card">

<button class="top-0 end-0 mt-3 me-3 btn btn-danger delete-notification"
        data-id="{{ $notification->id }}"
        style="position: absolute; 
               width: 25px; 
               height: 25px; 
               font-size: 24px; 
               text-align: center; 
               line-height: 10px; 
               border-radius: 7px; 
               padding: 0; 
               border: none; 
               background-color: rgb(190, 64, 64); 
               color: white;">
    &times;
</button>


                            <div class="d-flex align-items-start gap-3">
                                <div class="fs-7 mt-1">{{ $icon }}</div>

                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <div class="mb-1 text-dark fw-bold d-flex align-items-center gap-2">
                                            {{ $notification->title }}
                                         
                                        </div>
                                       
                                    </div>
                                    <p class="mb-1">{{ $notification->message }}</p>
                                 <a href="{{ route('leads.edit', ['id' => $orderId]) }}" class="text-primary text-decoration-underline d-inline">Order ID: {{ $orderId }}</a>

                                 
                                </div>
                            </div>
                            <small class="position-absolute bottom-0 end-0 mb-2 me-3 text-muted" style="font-size:22px;">
                                {{ \Carbon\Carbon::parse($notification->created_at)->format(' H:i') }}
                            </small>
                        </div>
                    </a>
                    @endif
                    @endforeach

                @endif
                
          <div class="d-flex justify-content-center paginate">
                {!! $allnotifications->withQueryString()->links('vendor.pagination.courier') !!}
            </div>

    
                </div>
            </div>


        </div>
    
        
    </div>

    {{-- <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">New Notification</strong>
                <small>Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast-message"></div>
        </div>
    </div> --}}


{{-- @section('script')
    <script>
        $(document).on('click', '#fix', function() {
            
        });
    </script>
@endsection --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    function handleResponsiveView() {
        const isMobile = window.innerWidth < 576;
        document.getElementById('mobile-version').classList.toggle('d-none', !isMobile);
        document.getElementById('desktop-version').classList.toggle('d-none', isMobile);
    }

    handleResponsiveView();

    window.addEventListener('resize', handleResponsiveView);
</script>

 <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('reset-filters').addEventListener('click', function () {
                    console.log('Reset button clicked');

                    document.getElementById('start-date').value = '';
                    document.getElementById('end-date').value = '';

                    document.querySelectorAll('.form-check-input').forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    document.getElementById('filter-form').submit();
                });
                 document.getElementById('reset-filters2').addEventListener('click', function () {
                    console.log('Reset button clicked');

                    document.getElementById('start-date').value = '';
                    document.getElementById('end-date').value = '';

                    document.querySelectorAll('.form-check-input').forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    document.getElementById('filter-form').submit();
                });
            });

            

    
        function toggleText() {
        var x = document.getElementById("multi");

        $('#timeseconds').val('');
        if (x.style.display === "none") {
            x.style.display = "block"; 
        } else {
            x.style.display = "none";  
        }
    } 
        function toggleText2() {
        var x = document.getElementById("multi2");

        $('#timeseconds').val('');
        if (x.style.display === "none") {
            x.style.display = "block"; 
        } else {
            x.style.display = "none";  
        }
    } 


        

    $(document).ready(function () {

       function toggleDeleteAllButton() {
            if ($('.posdiv').length === 0) {
                $('#delete-all-btn').hide();
            } else {
                $('#delete-all-btn').show();
            }
        }

        toggleDeleteAllButton();

   $('#delete-all-btn').on('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to delete all notifications!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/notifications/delete-all',
                        method: 'GET',
                        success: function (response) {
                            $('.posdiv').remove();
                            $('.notification-count').text('').css('display', 'none');
                            $('.notif-list').empty();
                            $('.notif-count-badge').text('0 new');
                             $('.paginate').remove();


                            showToast("All notifications deleted!");
                            setTimeout(toggleDeleteAllButton, 300);
                        },
                        error: function (xhr) {
                            alert("Failed to delete notifications.");
                        }
                    });
                }
            });
        });

       $('.delete-notification').on('click', function (e) {
    e.preventDefault();

    const id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: 'This will delete the notification permanently.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/notifications/delete/${id}`,
                method: 'GET',
                success: function (response) {
                    $(`button[data-id="${id}"]`).closest('.posdiv').remove();

                    const $badge = $('.notification-count');
                    let count = parseInt($badge.text());

                    if (!isNaN(count) && count > 0) {
                        count -= 1;

                        if (count > 99) {
                            $badge.text('+99').show();
                        } else if (count > 0) {
                            $badge.text(count).show();
                        } else {
                            $badge.hide().text('');
                        }
                    }

                    showToast("Notification deleted!");
                    setTimeout(toggleDeleteAllButton, 300);
                },
                error: function (xhr) {
                    alert("Failed to delete notification.");
                }
            });
        }
    });
});



        function showToast(message) {
            const toast = $('<div></div>')
                .text(message)
                .css({
                    position: 'fixed',
                    top: '20px', 
                    right: '20px',
                    background: '#28a745',
                    color: 'white',
                    padding: '10px 20px',
                    borderRadius: '5px',
                    zIndex: 9999,
                    boxShadow: '0 0 10px rgba(0,0,0,0.2)'
                });

            $('body').append(toast);
            setTimeout(() => toast.remove(), 3000);  
        }
    });

     
    </script>






 @endsection