@extends('backend.layouts.app')
@section('content')
<style>
    .dropdown-menu.show {
    display: block;
    left: -50px !important;
}
</style>
          
                  <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            <div class="card card-body py-3">
                <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">Staffs</h4>
                    <nav aria-label="breadcrumb" class="ms-auto">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item d-flex align-items-center">
                            <a class="text-muted text-decoration-none d-flex" href="{{ route('home')}}">
                            <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                            </a>
                        </li>
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
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                       
                                    <div class="form-group mb-4 text-right">
                                        <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#adduser">Add New Assigne</button>
                                        <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white" id="export">Export</a>
                                    </div>
                                
                                <!-- Add Contact Popup Model -->        
                                <div id="adduser" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Add New Assigne</h4> 
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal form-material">
                                                    <div class="form-group">
                                                        <div class="col-md-12 my-4">
                                                            <input type="text" class="form-control" id="assigne_name" placeholder="Assigne Name">
                                                        </div>
                                                        <div class="col-md-12 my-4">
                                                            <input type="phone" class="form-control" id="assigne_phone" placeholder="Phone">
                                                        </div>
                                                        <div class="col-md-12 my-4">
                                                            <input type="email" class="form-control" id="assigne_email" placeholder="Email">
                                                        </div>
                                                        <div class="col-md-12 my-4">
                                                            <input type="password" class="form-control" id="assigne_password" placeholder="password">
                                                        </div>
                                                    </div>
                                                </from>
                                            </div>
                                            <div class="modal-body">
                                                <button type="submit" class="btn btn-primary waves-effect" data-bs-dismiss="modal" id="add-assigne">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                            <div class="modal-footer">
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
                                                <th>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="selectall custom-control-input" id="chkCheckAll" required>
                                                        <label class="custom-control-label" for="chkCheckAll"></label>
                                                    </div>
                                                </th>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Created at</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $counter = 1 ;
                                            ?>
                                            @foreach($staffs as $v_product)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="ids" class="custom-control-input checkBoxClass"  value="{{$v_product->id}}" id="pid-{{$counter}}">
                                                        <label class="custom-control-label" for="pid-{{$counter}}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $counter}}</td>
                                                <td>{{ $v_product->name}}</td>
                                                <td>{{ $v_product->email}}</td>
                                                <td>{{ $v_product->telephone}}</td>
                                                <td>@if( $v_product->is_active == "1") <span class="badge bg-success">Active</span>@else <span class="badge bg-danger">InActive</span> @endif</td>
                                                <td>{{ $v_product->created_at}}</td>
                                                <td>
                                                    
                                                <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle rounded" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="ti ti-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu animated slideInUp" x-placement="bottom-start" style="position: absolute; will-change: transform; transform: translate3d(0px, 35px, 0px);">
                                                        @if($v_product->is_active == "1")
                                                            <a class="dropdown-item inactive" data-id="{{ $v_product->id}}" id=""><i class="ti-pencil-alt"></i> InActive</a>
                                                        @else
                                                            <a class="dropdown-item active" data-id="{{ $v_product->id}}" id=""><i class="ti-pencil-alt"></i> Active</a>
                                                        @endif
                                                            <a class="dropdown-item reset" data-id="{{ $v_product->id}}" id=""><i class="ti-pencil-alt"></i> Reset Password</a>
                                                            <a class="dropdown-item" href="{{ route('Staff.performence', $v_product->id)}}"><i class="ti-pencil-alt"></i> Performance</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            $counter ++ ;
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

                                <div id="edit_product" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Edit Product</h4> 
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                            </div>
                                                <form action="{{ route('products.update')}}" method="POST" class="form-horizontal form-material" enctype="multipart/form-data" novalidate="novalidate">
                                                @csrf
                                            <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="col-md-12 my-4">
                                                            <input type="text" class="form-control" name="product_nam" id="product_nam" placeholder="Product Name">
                                                            <input type="hidden" class="form-control" name="product_id" id="product_id" placeholder="Product Name">
                                                        </div>
                                                        <div class="col-md-12 my-4">
                                                            <input type="file" class="form-control" name="product_image" id="product_image" placeholder="Product Image"> </div>
                                                        <div class="col-md-12 my-4">
                                                            <input type="text" class="form-control" name="product_link" id="product_link" placeholder="Link"> </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect" >Save</button>
                                                <button type="button" class="btn btn-default waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </form>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


<script type="text/javascript">
    
    $(function(e){
        $('#existin_product').click(function(e){
            var product = $('#product_name').val();
            var quantity = $('#quantity').val();
            var country = $('#country').val();
            var expidition = $('#expidition_name').val();
            var mode = $('#expidition_mode').val();
            var date = $('#expidition_date').val();
            var phone = $('#expidition_phone').val();
            var packagin = $('#nbr_packagin').val();
            $.ajax({
                type : 'POST',
                url:'{{ route('products.warehousestore')}}',
                cache: false,
                data:{
                    product: product,
                    quantity: quantity,
                    country: country,
                    expidition: expidition,
                    mode: mode,
                    date: date,
                    phone: phone,
                    packagin: packagin,
                    _token : '{{ csrf_token() }}'
                },
                success:function(response){
                    if(response.success == true){
                        toastr.success('Good Job Product.', 'Stock Has been Addess Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    }
                    location.reload();
            }});
        });
    });
    $(function(e){
        $('#config').click(function(e){
            var product = $('#upsel_product_id').val();
            var quantity = $('#upsel_quantity').val();
            var discount = $('#discount').val();
            var note = $('#note').val();
            $.ajax({
                type : 'POST',
                url:'{{ route('products.upsel')}}',
                cache: false,
                data:{
                    product: product,
                    quantity: quantity,
                    discount: discount,
                    note: note,
                    _token : '{{ csrf_token() }}'
                },
                success:function(response){
                    if(response.success == true){
                        toastr.success('Good Job Product.', 'Stock Has been Addess Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    }
                    location.reload();
            }});
        });
    });
    $(function(e){
        $('#add-assigne').click(function(e){
            var name = $('#assigne_name').val();
            var email = $('#assigne_email').val();
            var password = $('#assigne_password').val();
            var phone = $('#assigne_phone').val();
            $.ajax({
                type : 'POST',
                url:'{{ route('Staff.store')}}',
                cache: false,
                data:{
                    name: name,
                    email: email,
                    password: password,
                    phone: phone,
                    _token : '{{ csrf_token() }}'
                },
                success:function(response){
                    if(response.success == true){
                        toastr.success('Good Job Product.', 'Stock Has been Addess Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    }
                    location.reload();
            }});
        });
    });
    $(function(e){
        $('.active').click(function(e){
            var id = $(this).data('id');
            $.ajax({
                type : 'POST',
                url:'{{ route('Staff.active')}}',
                cache: false,
                data:{
                    id: id,
                    _token : '{{ csrf_token() }}'
                },
                success:function(response){
                    if(response.success == true){
                        toastr.success('Good Job.', 'User Status Has been Change Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    }
                    location.reload();
            }});
        });
    });
    $(function(e){
        $('.reset').click(function(e){
            var id = $(this).data('id');
            $.ajax({
                type : 'POST',
                url:'{{ route('Staff.reset')}}',
                cache: false,
                data:{
                    id: id,
                    _token : '{{ csrf_token() }}'
                },
                success:function(response){
                    if(response.success == true){
                        toastr.success('Good Job.', 'User Has been Reset Password Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    }
                    location.reload();
            }});
        });
    });
    $(function(e){
        $('.inactive').click(function(e){
            var ids = $(this).data('id');
            $.ajax({
                type : 'POST',
                url:'{{ route('Staff.inactive')}}',
                cache: false,
                data:{
                    id: ids,
                    _token : '{{ csrf_token() }}'
                },
                success:function(response){
                    if(response.success == true){
                        toastr.success('Good Job.', 'User Status Has been Change Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    }
                    location.reload();
            }});
        });
    });

    $(function () {
        $('body').on('click', '.editProduct', function () {
        var product_id = $(this).data('id');
        //console.log(product_id);
            $.get("{{ route('products.index') }}" +'/' + product_id +'/edit', function (data) {
                //console.log(product_id);
                $('#edit_product').modal('show');
                $('#product_id').val(data.id);
                $('#product_nam').val(data.name);
                $('#product_link').val(data.link);
            });
        });
    });
    
    $(function () {
        $('body').on('click', '.configupsel', function () {
        var product_id = $(this).data('id');
        //console.log(product_id);
                $('#config_upsel').modal('show');
                $('#upsel_product_id').val(product_id);
            
        });
    });
    $(function(e){
        $("#chkCheckAll").click(function(){
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });
        $('#export').click(function(e){
            e.preventDefault();
            var allids = [];
            $("input:checkbox[name=ids]:checked").each(function(){
                allids.push($(this).val());
            });
            $.ajax({
                    type : 'POST',
                    url:'{{ route('Staff.export')}}',
                    cache: false,
                    data:{
                        ids: allids,
                        _token : '{{ csrf_token() }}'
                    },
                    success:function(response,invoices){
                        $.each(allids, function(key,val,invoices){
                            var a = JSON.stringify(allids);
                            window.location = ('Staff/export-download/'+a);
                        });
                    }
                });
        });
    });
</script>
@if (session()->has('success'))
<div class="alert alert-success">
    @if(is_array(session('success')))
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
@if(session()->has('success'))
<script>
    toastr.success('Good Job Product.', 'Product Has been Addess Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
</script>
@endif
@endsection