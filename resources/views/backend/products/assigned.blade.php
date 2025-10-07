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

            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            <div class="card card-body py-3">
                <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                        <h4 class="mb-4 mb-sm-0 card-title">Products</h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item d-flex align-items-center">
                                    <a type="button" class="btn btn-primary btn-rounded my-1" data-bs-toggle="modal" data-bs-target="#assigned-product">Assigned Product</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-12">
                    <!-- Column -->
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <form>
                                    <div class="row">
                                        <div class="col-md-11 col-sm-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="search" id="search"
                                                    placeholder="SKU , Product Name" aria-label=""
                                                    aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-12">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary text-white">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group mb-0 text-right">
                            </div>

                            <!-- Add Contact Popup Model -->
                            <div id="assigned-product" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Add New Assigned</h4>
                                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <from class="form-horizontal form-material">
                                                <div class="form-group">
                                                    <div class="col-md-12 my-2">
                                                        <input type="hidden" id="id_product" value="{{$id}}"/>
                                                        <select class="form-control" id="agent_name">
                                                            <option>Select Agent</option>
                                                            @foreach ($agents as $v_agent)
                                                                <option value="{{ $v_agent->id }}">{{ $v_agent->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </from>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary waves-effect"
                                                id="assigned_product">Save</button>
                                            <button type="button" class="btn btn-primary waves-effect"
                                                data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <div class="table-responsive border rounded-1" style="margin-top:-20px">
                                <table class="table text-nowrap customize-table mb-0 align-middle">
                                  <thead class="text-dark fs-4">
                                        <tr>
                                            <th>No</th>
                                            <th>SKU</th>
                                            <th>Image</th>
                                            <th>Agent Name</th>
                                            <th>Created at</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 1;
                                        ?>
                                        @foreach ($assigneds as $v_assigned)
                                            <tr role="row" class="odd">
                                                <td>{{ $counter }}</td>
                                                <td><span class="badge bg-secondary">{{ $v_assigned['product']->sku }}</span></td>
                                                <td><img src="{{ $v_assigned['product']->image }}" alt="user" class="circle" width="45" /></td>
                                                <td>{{ $v_assigned['agent']->name }}</td>
                                                <td>{{ $v_assigned->created_at }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle show" type="button"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="true"><i class="icon-settings"></i></button>
                                                        <div class="dropdown-menu" bis_skin_checked="1"
                                                            style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(184px, -325.203px, 0px);"
                                                            data-popper-placement="top-start">
                                                            <a class="dropdown-item " href="{{ route('products.assigneddelete', $v_assigned->id) }}">Deleted</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $counter++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->

    <div id="edit_product" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Details Product</h4>
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
                                <input type="text" class="form-control" name="product_link" id="product_link"
                                    placeholder="Link">
                            </div>
                            <div class="col-md-12 my-2">
                                <input type="text" class="form-control" name="product_video" id="product_video"
                                    placeholder="Link Video">
                            </div>
                            <div class="col-md-12 my-2">
                                <textarea type="text" class="form-control" name="description" id="description" placeholder="description"></textarea>
                            </div>
                            <div class="col-md-12 my-2">
                                <div id="product_image">

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div id="config_upsel" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Config Upsell</h4>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="form-horizontal form-material" enctype="multipart/form-data" novalidate="novalidate">

                    <div class="modal-body">
                        <div class="form-group">
                            <div class="col-md-12 my-2">
                                <input type="text" class="form-control" name="upsel_quantity" id="upsel_quantity"
                                    placeholder="Quantity">
                                <input type="hidden" class="form-control" name="upsel_product_id" id="upsel_product_id"
                                    placeholder="Product Name">
                            </div>
                            <div class="col-md-12 my-2">
                                <input type="text" class="form-control" name="discount" id="discount"
                                    placeholder="Discount">
                            </div>
                            <div class="col-md-12 my-2">
                                <input type="text" class="form-control" name="note" id="note"
                                    placeholder="Note">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect" id="config">Save</button>
                        <button type="button" class="btn btn-primary waves-effect"
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
            $('#assigned_product').click(function(e) {
                var product = $('#id_product').val();
                var agent = $('#agent_name').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('products.assigned.store') }}',
                    cache: false,
                    data: {
                        product: product,
                        agent: agent,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.',
                                'Assigned Has been Addess Success!', {
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
            $('#config').click(function(e) {
                var product = $('#upsel_product_id').val();
                var quantity = $('#upsel_quantity').val();
                var discount = $('#discount').val();
                var note = $('#note').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('products.upsel') }}',
                    cache: false,
                    data: {
                        product: product,
                        quantity: quantity,
                        discount: discount,
                        note: note,
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
                    $('#product_video').val(data.link_video);
                    $('#description').val(data.description);
                    $('#product_image').html("<img src='" + data.image + "' width='80px' />");
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
