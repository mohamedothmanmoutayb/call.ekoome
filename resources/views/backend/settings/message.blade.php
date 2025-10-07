@extends('backend.layouts.app')
@section('content')
    <!-- ============================================================== -->
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
                    <div class="col-5 align-self-center">
                        <h4 class="page-title"><span class="text-muted fw-light">Dashboard /</span> Messages</h4>
                        <div class="d-flex align-items-center">

                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-10">
                                    <h4 class="card-title">Messages list</h4>
                                </div> --}}
                                <div class="col-2">
                                    <div class="form-group mb-4 text-right">
                                        <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light"
                                            data-bs-toggle="modal" data-bs-target="#adduser">Add New Message</button>
                                    </div>
                                </div>
                            </div>


                            <!-- Add Contact Popup Model -->
                            <div id="adduser" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Add New Message</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('settings.store') }}" method="POST"
                                            class="form-horizontal form-material">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="col-md-12 my-2">
                                                        <select class="form-control" id="status" name="status">
                                                            <option>Select Status</option>
                                                            <option value="new order">New Order</option>
                                                            <option value="confirmed">Confirmed</option>
                                                            <option value="no answer">No Answer</option>
                                                            <option value="call later">Call Later</option>
                                                            <option value="canceled">canceled</option>
                                                            <option value="shipped">shipped</option>
                                                            <option value="delivered">Delivered</option>
                                                            <option value="ready to ship">Ready To Ship</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 my-1">
                                                        <textarea class="form-control" name="message_whatsapp" id="message_whatsapps" cols="50" width=100 rows="15" class="ckeditor"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <button type="submit" class="btn btn-primary waves-effect">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                        <div class="modal-footer">
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <div class="table-responsive" style="min-height: 500px">
                                <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list"
                                    data-paging="true" data-paging-size="7">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Message</th>
                                            <th>Status</th>
                                            <th>Created at</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 1;
                                        ?>
                                        @foreach ($messages as $v_message)
                                            <tr>
                                                <td>{{ $counter }}</td>
                                                <td>{!! Str::limit($v_message->message, 20) !!}</td>
                                                <td>{{ $v_message->status }}</td>
                                                <td>{{ $v_message->created_at }}</td>
                                                <td>

                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu animated slideInUp"
                                                            x-placement="bottom-start"
                                                            style="position: absolute; will-change: transform; transform: translate3d(0px, 35px, 0px);">
                                                            <a class="dropdown-item reset" data-id="{{ $v_message->id }}"
                                                                id=""><i class="ti-edit"></i> Edit</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('Staff.performence', $v_message->id) }}"><i
                                                                    class="ti ti-edit"></i> Deleted</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            $counter++;
                                            ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Right sidebar -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">
                <div
                    class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
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
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->

    <div id="edit_product" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Edit Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('products.update') }}" method="POST" class="form-horizontal form-material"
                    enctype="multipart/form-data" novalidate="novalidate">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="product_nam" id="product_nam"
                                    placeholder="Product Name">
                                <input type="hidden" class="form-control" name="product_id" id="product_id"
                                    placeholder="Product Name">
                            </div>
                            <div class="col-md-12 m-b-20">
                                <input type="file" class="form-control" name="product_image" id="product_image"
                                    placeholder="Product Image">
                            </div>
                            <div class="col-md-12 m-b-20">
                                <input type="text" class="form-control" name="product_link" id="product_link"
                                    placeholder="Link">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect">Save</button>
                        <button type="button" class="btn btn-default waves-effect"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


    <script type="text/javascript">
        $(function(e) {
            $('#savedata').click(function(e) {
                var whatsapp = $("#message_whatsapps").val();
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
    </script>
@endsection
