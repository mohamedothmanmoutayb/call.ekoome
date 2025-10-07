@extends('backend.layouts.app')
@section('content')

<style>
    .btn-pro{
        margin-left: auto;
        margin-right:auto;
        display: table;
    }

    
        .label-process{
            background-color: #ff6334;
        }
        #down{
            display : none;
        }

        .label-process {
            background-color: #ff6334;
        }

        #down {
            display: none;
        }

        .input-group-text {
            height: 40px !important;
        }
    </style>

    <!-- Content wrapper -->
    
        <!-- Content -->
        <div class="card card-body py-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                        <h4 class="mb-4 mb-sm-0 card-title">Account Setting</h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item d-flex align-items-center">
                                    <a class="text-muted text-decoration-none d-flex" href="../horizontal/index.html">
                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                    </a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">
                                    <span class="badge fw-medium fs-2 bg-primary-subtle text-primary">
                                    Account Setting
                                    </span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <ul class="nav nav-pills user-profile-tab" id="pills-tab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-3" id="pills-account-tab" data-bs-toggle="pill" data-bs-target="#pills-account" type="button" role="tab" aria-controls="pills-account" aria-selected="true">
                  <i class="ti ti-user-circle me-2 fs-6"></i>
                  <span class="d-none d-md-block">Account</span>
                </button>
              </li>
                   <li class="nav-item" role="presentation">
                <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-3" id="pills-notification-tab" data-bs-toggle="pill" data-bs-target="#pills-notification" type="button" role="tab" aria-controls="pills-notification" aria-selected="false" tabindex="-1">
                    <i class="fas fa-cog" style="margin-right:6px;"></i>
                  <span class="d-none d-md-block">Notifications</span>
                </button>
              </li>
            </ul>
            <div class="card-body">
              <div class="tab-content" id="pills-tabContent">

                
                <div class="tab-pane fade show active" id="pills-account" role="tabpanel" aria-labelledby="pills-account-tab" tabindex="0">
                  <div class="row">
                    <div class="col-lg-6 d-flex align-items-stretch">
                      <div class="card w-100 border position-relative overflow-hidden">
                        <div class="card-body p-4">
                          <h4 class="card-title">Change Profile</h4>
                          <p class="card-subtitle mb-4">Change your profile picture from here</p>
                          <div class="text-center">
                            <img src="{{ asset('public/assets/images/profile/user-1.jpg')}}" alt="matdash-img" class="img-fluid rounded-circle" width="120" height="120">
                            <div class="d-flex align-items-center justify-content-center my-4 gap-6">
                              <button class="btn btn-primary">Upload</button>
                              <button class="btn bg-danger-subtle text-danger">Reset</button>
                            </div>
                            <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-stretch">
                      <div class="card w-100 border position-relative overflow-hidden">
                        <div class="card-body p-4">
                          <h4 class="card-title">Change Password</h4>
                          <p class="card-subtitle mb-4">To change your password please confirm here</p>
                          <form>
                            <div class="mb-3">
                              <label for="exampleInputPassword1" class="form-label">Current Password</label>
                              <input type="password" class="form-control" id="exampleInputPassword1" value="">
                            </div>
                            <div class="mb-3">
                              <label for="exampleInputPassword2" class="form-label">New Password</label>
                              <input type="password" class="form-control" id="exampleInputPassword2" value="">
                            </div>
                            <div>
                              <label for="exampleInputPassword3" class="form-label">Confirm Password</label>
                              <input type="password" class="form-control" id="exampleInputPassword3" value="">
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="card w-100 border position-relative overflow-hidden mb-0">
                        <div class="card-body p-4">
                          <h4 class="card-title">Personal Details</h4>
                          <p class="card-subtitle mb-4">To change your personal detail , edit and save from here</p>
                          <form>
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="exampleInputtext" class="form-label">Email</label>
                                  <input type="email" class="form-control form-control-line" value="{{ $user->email }}" disabled>
                                </div>
                                <div class="mb-3">
                                  <label for="exampleInputtext1" class="form-label">UserName</label>
                                  <input type="text" value="{{ $user->name }}" class="form-control form-control-line" disabled>
                                </div>
                                <div class="mb-3">
                                  <label class="form-label">Full Name</label>
                                  <input type="text" value="{{ $user->name }}" id="full_name" class="form-control form-control-line">
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <div class="mb-3">
                                  <label for="exampleInputtext3" class="form-label">Company</label>
                                  <input type="text" class="form-control form-control-line" value="{{ $user->company }}" id="company">
                                </div>
                                <div class="mb-3">
                                  <label class="form-label">Telephone</label>
                                  <input type="mobile" value="{{ $user->telephone }}" class="form-control form-control-line" id="phone">
                                </div>
                              </div>
                              <div class="col-12">
                                <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                  <button class="btn btn-primary" type="submit" id="update">Save</button>
                                  <button class="btn bg-danger-subtle text-danger">Cancel</button>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
         






            <div class="tab-pane fade" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab" tabindex="0">
              <div class="row">
                <div class="col-12">
                  <div class="card w-100 border position-relative overflow-hidden mb-0">
                    <div class="card-body p-4">
                      <h4 class="card-title">Notifications</h4>
                      <p class="card-subtitle mb-4">Manage how and when you receive notifications</p>

                      <form id="notification-settings-form">
                        <div class="form-check form-switch mb-4">
                          <input class="form-check-input" type="checkbox" id="toggle-sound">
                          <label class="form-check-label" for="toggle-sound">Enable Sound</label>
                        </div>

                        <div class="form-check form-switch mb-4">
                          <input class="form-check-input" type="checkbox" id="toggle-notifications">
                          <label class="form-check-label fw-bold" for="toggle-notifications">Enable Notifications</label>
                        </div>

                        <label class="form-label fw-bold mb-2">Show Notifications For:</label>

                            <div class="form-check mb-2">
                            <input class="form-check-input notif-checkbox" type="checkbox" value="returned" id="returned" >
                            <label class="form-check-label" for="returned">🔁 Returned notifications</label>
                            </div>

                            <div class="form-check mb-2">
                            <input class="form-check-input notif-checkbox" type="checkbox" value="rejected" id="rejected" >
                            <label class="form-check-label" for="rejected">❌ Rejected orders</label>
                            </div>

                            
                            <div class="form-check mb-2">
                            <input class="form-check-input notif-checkbox" type="checkbox" value="delivered" id="delivered" >
                            <label class="form-check-label" for="delivered">✅ Delivered orders</label>
                            </div>

                            @auth
                            @if (auth()->user()->id_role == 4)
                                <div class="form-check mb-2">
                                <input class="form-check-input notif-checkbox" type="checkbox" value="canceled" id="canceled" checked>
                                <label class="form-check-label" for="canceled">🚫 Canceled orders</label>
                                </div>
                                  <div class="form-check mb-2">
                                <input class="form-check-input notif-checkbox" type="checkbox" value="wrong" id="wrong" checked>
                                <label class="form-check-label" for="wrong">❌ Wrong</label>
                                </div>
                            @endif
                            @endauth

                        <div class="d-flex justify-content-end gap-3">
                          <button class="btn btn-primary" type="submit" id="save-preferences">Save</button>
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
              </div>
            </div>






              </div>
            </div>
        </div>

        <div class="content-backdrop fade"></div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {
  const soundToggle = document.getElementById('toggle-sound');
  const notifToggle = document.getElementById('toggle-notifications');
  const checkboxes = document.querySelectorAll('.notif-checkbox');

 function setCheckboxState(enabled) {
  checkboxes.forEach(cb => {
    cb.disabled = !enabled;
    if (!enabled) {
      cb.checked = false;   
    }
  });
}

  notifToggle.addEventListener('change', () => {
    setCheckboxState(notifToggle.checked);
  });

  fetch("{{ route('notifications.get') }}")
    .then(res => res.json())
    .then(data => {
      console.log("Loaded notification preferences:", data);
      soundToggle.checked = !!data.sound;
      notifToggle.checked = data.titles && data.titles.length > 0;

    console.log("Notification toggle state:", notifToggle.checked);
      setCheckboxState(notifToggle.checked);

  
      if (notifToggle.checked && data.titles && data.titles.length > 0) {
        data.titles.forEach(val => {
          const cb = document.querySelector(`.notif-checkbox[value="${val}"]`);
          if (cb) cb.checked = true;
        });
      }
    })
    .catch(err => {
      alert('Error loading preferences');
      console.error(err);
    });


  document.getElementById('notification-settings-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const sound = soundToggle.checked ? 1 : 0;
    const titles = [];

    if (notifToggle.checked) {
      document.querySelectorAll('.notif-checkbox:checked').forEach(cb => {
        titles.push(cb.value);
      });
    }

    console.log("Saving preferences:", { sound, titles });
  

    $.ajax({
      url: '{{ route("notifications.settings") }}',
      method: 'POST',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content'),
        sound: sound,
        titles: titles
      },
      success: function (response) {
      Swal.fire({
      icon: 'success',
      title: 'Preferences Saved',
      text: 'Your notification settings have been updated.',
      timer: 2000,
      showConfirmButton: false
    });

      },
      error: function (xhr, status, error) {
      Swal.fire({
      icon: 'error',
      title: 'Failed to Save',
      text: 'Error: ' + error,
      footer: '<code>' + xhr.responseText + '</code>'
    });
      }
    });
  });
});

</script>



<script type='text/javascript'>


    $(function(e){
        $('#update').click(function(e){
            e.preventDefault();
            var fullname = $('#full_name').val();
            var phone = $('#phone').val();
            var company = $('#company').val();
            var newpass = $('#new_password').val();
            var conpass = $('#con_pass').val();
            $.ajax({
                    type : 'POST',
                    url:'{{ route('updateprofile')}}',
                    cache: false,
                    data:{
                        fullname: fullname,
                        phone: phone,
                        company: company,
                        newpass: newpass,
                        conpass : conpass,
                        _token : '{{ csrf_token() }}'
                    },
                    success:function(response,leads){
                        if(response.success == true){
                            toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                        }
                        if(response.success == 'remplier'){
                            toastr.error('Opps.', 'Pleas Complete information!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                        }
                        if(response.pass == 'pass'){
                            toastr.error('Opps.', 'Password Not Confirmed', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                        }
                    }
                });
        });
    });
</script>
@endsection