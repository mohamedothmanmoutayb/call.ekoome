@extends('backend.layouts.app')

@section('content')
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">

        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-10 align-self-center">
                        <h4 class="page-title"><span class="text-muted fw-light">Dashboard /</span> Setting</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Earnings -->
            <!-- ============================================================== -->

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Config Message Whatsapp</h4>
                    @if (empty($data->message_whatsapp))
                        <div class="form-group">
                            <textarea name="message_whatsapp" id="message_whatsapps" cols="50" width=100 rows="15" class="ckeditor"></textarea>
                        </div>
                        <button type="button" class="btn btn-info waves-effect" id="savedata">Save</button>
                    @else
                        <input type="hidden" id="id" value="{{ $data->id }}" />
                        <div class="form-group">
                            <textarea name="message_whatsapp" id="message_whatsapp" cols="50" width=100 rows="15" class="ckeditor">{!! $data->message_whatsapp !!}</textarea>
                        </div>
                        <button type="button" class="btn btn-info waves-effect" id="updatedata">Update</button>
                    @endif
                </div>
            </div>
        </div>
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">
                <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                    <div>
                        ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        , made with ❤️ by <a href="https://Palace Agency.eu" target="_blank" class="fw-semibold">Palace Agency</a>
                    </div>
                    <div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


    <script type="text/javascript">
        $(function(e) {
            $('#savedata').click(function(e) {
                var whatsapp = document.getElementById("message_whatsapps");
                alert(whatsapp);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('settings.store') }}',
                    cache: false,
                    data: {
                        whatsapp: whatsapp,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job .', 'Message Has been Addess Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                        }
                        location.reload();
                    }
                });
            });
        });

        $(function(e) {
            $('#updatedata').click(function(e) {
                var whatsapp = $('textarea#message_whatsapp').val();
                var id = $('#id').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('settings.update') }}',
                    cache: false,
                    data: {
                        id: id,
                        whatsapp: whatsapp,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job .', 'Message Has been Addess Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                        }
                        location.reload();
                    }
                });
            });
        });
    </script>
@endsection
