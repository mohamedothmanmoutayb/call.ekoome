@extends('backend.layouts.app')
@section('content')
    <style>
        .dropdown-menu.show {
            display: block;
            left: -50px !important;
        }
    </style>
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">

        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title"><span class="text-muted fw-light">Dashboard /</span>Permissions</h4>
                        <div class="d-flex align-items-center">

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-subtitle"></h6>
                            <div class="form-group mb-0 text-right">
                                <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light"
                                    data-bs-toggle="modal" data-bs-target="#adduser">Add New Permission</button>
                            </div>

                            <!-- Add Contact Popup Model -->
                            <div id="adduser" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Add Permission</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('Staff.permissionstore') }}" method="POST"
                                            class="form-horizontal form-material" enctype="multipart/form-data"
                                            novalidate="novalidate">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <div class="col-md-12 my-2">
                                                        <select class="form-control" name="id_role" id="role_name">
                                                            <option>Select Roles</option>
                                                            @foreach ($roles as $v_role)
                                                                <option value="{{ $v_role->id }}">{{ $v_role->name }}
                                                                </option>*
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-sm-12">
                                                    <label for="pass4" class="col-sm-3 control-label">Permission*</label>
                                                    <div class="row offset-sm-1">
                                                        <div class="custom-control custom-checkbox mr-sm-2">
                                                            <input type="checkbox" class="custom-control-input"
                                                                name="permission[]" id="checkbox1" value="1">
                                                            <label class="custom-control-label" for="checkbox1">Confirmation
                                                            </label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox mr-sm-2">
                                                            <input type="checkbox" class="custom-control-input"
                                                                name="permission[]" id="checkbox2" value="2">
                                                            <label class="custom-control-label" for="checkbox2">Suivi
                                                                Confirmation </label>
                                                        </div>
                                                        <div class="custom-control custom-checkbox mr-sm-2">
                                                            <input type="checkbox" class="custom-control-input"
                                                                name="permission[]" id="checkbox3" value="3">
                                                            <label class="custom-control-label" for="checkbox3">Suivi
                                                                Delivery </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <button type="submit" class="btn btn-primary waves-effect">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                    </div>
                                    </form>
                                    <div class="modal-footer">
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <div class="table-responsive">
                            <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list"
                                data-paging="true" data-paging-size="7">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Permission</th>
                                        <th>Description</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    ?>
                                    @foreach ($users as $v_role)
                                        <tr>
                                            <td>{{ $counter }}</td>
                                            <td>{{ $v_role->name }}</td>
                                            <td>
                                                @foreach ($v_role['permission'] as $v_per)
                                                    @foreach ($v_per['permission'] as $v_perm)
                                                        <span>{{ $v_perm->name }} ,</span>
                                                    @endforeach
                                                @endforeach
                                            </td>
                                            <td>{{ $v_role->description }}</td>
                                            <td>{{ $v_role->created_at }}</td>
                                            <td>

                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="ti-settings"></i>
                                                    </button>
                                                    <div class="dropdown-menu animated slideInUp" x-placement="bottom-start"
                                                        style="position: absolute; will-change: transform; transform: translate3d(0px, 35px, 0px);">
                                                        <a class="dropdown-item"
                                                            href="{{ route('Staff.permissions', $v_role->id) }}"
                                                            data-id="{{ $v_role->id }}"><i class="ti-edit"></i> Add
                                                            Permission</a>
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
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
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
                            <div class="col-md-12 my-2">
                                <input type="text" class="form-control" name="product_nam" id="product_nam"
                                    placeholder="Product Name">
                                <input type="hidden" class="form-control" name="product_id" id="product_id"
                                    placeholder="Product Name">
                            </div>
                            <div class="col-md-12 my-2">
                                <input type="file" class="form-control" name="product_image" id="product_image"
                                    placeholder="Product Image">
                            </div>
                            <div class="col-md-12 my-2">
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
            $('#add-role').click(function(e) {
                var name = $('#name').val();
                var description = $('#description').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('Staff.rolestore') }}',
                    cache: false,
                    data: {
                        name: name,
                        description: description,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job Product.',
                                'Stock Has been Addess Success!', {
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
            $('.active').click(function(e) {
                var id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('Staff.active') }}',
                    cache: false,
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.',
                            'User Status Has been Change Success!', {
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
            $('.inactive').click(function(e) {
                var ids = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('Staff.inactive') }}',
                    cache: false,
                    data: {
                        id: ids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.',
                            'User Status Has been Change Success!', {
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

        $(function() {
            $('body').on('click', '.editProduct', function() {
                var product_id = $(this).data('id');
                //console.log(product_id);
                $.get("{{ route('products.index') }}" + '/' + product_id + '/edit', function(data) {
                    //console.log(product_id);
                    $('#edit_product').modal('show');
                    $('#product_id').val(data.id);
                    $('#product_nam').val(data.name);
                    $('#product_link').val(data.link);
                });
            });
        });

        $(function() {
            $('body').on('click', '.configupsel', function() {
                var product_id = $(this).data('id');
                //console.log(product_id);
                $('#config_upsel').modal('show');
                $('#upsel_product_id').val(product_id);

            });
        });
    </script>
    @if (session()->has('success'))
        <div class="alert alert-success">
            @if (is_array(session('success')))
                <ul>
                    @foreach (session('success') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @else
                {{ session('success') }}
            @endif
        </div>
    @endif
    @if (session()->has('success'))
        <script>
            toastr.success('Good Job Product.', 'Product Has been Addess Success!', {
                "showMethod": "slideDown",
                "hideMethod": "slideUp",
                timeOut: 2000
            });
        </script>
    @endif
@endsection
