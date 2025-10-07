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
                        <h4 class="page-title"><span class="text-muted fw-light">Dashboard /</span> Upsells</h4>
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
                                <h4 class="card-title">Upsells list</h4>
                                <h6 class="card-subtitle"></h6>
                                      
                                <div id="filter" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Search</h4> 
                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal form-material">
                                                    <div class="form-group">
                                                        <div class="col-md-12 m-b-20">
                                                            <input type="text" class="form-control" placeholder="Store Name"> </div>
                                                        <div class="col-md-12 m-b-20">
                                                            <input type="text" class="form-control" placeholder="Link"> </div>
                                                    </div>
                                                </from>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Search</button>
                                                <button type="button" class="btn btn-default waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <div class="table-responsive">
                                    <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list" data-paging="true" data-paging-size="7">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Products</th>
                                                <th>Quantity</th>
                                                <th>Discount</th>
                                                <th>Note</th>
                                                <th>Created at</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($upsells as $v_upsel)
                                            @foreach($v_upsel['upselles'] as $v_app)
                                            <tr>
                                                <td>1</td>
                                                <td>{{ $v_upsel->name}}</td>
                                                <td>{{ $v_app->quantity}}</td>
                                                <td>{{ $v_app->discount}}</td>
                                                <td>{{ $v_app->note}}</td>
                                                <td>{{$v_app->created_at}}</td>
                                            </tr>
                                            @endforeach
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
                                <div id="config_upsel" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Update Upsell</h4> 
                                                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                                <form class="form-horizontal form-material">
                                                
                                            <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="col-md-12 m-b-20">
                                                            <input type="text" class="form-control" name="upsel_quantity" id="upsel_quantity" placeholder="Quantity">
                                                            <input type="hidden" id="upsel_id" />
                                                        </div>
                                                        <div class="col-md-12 m-b-20">
                                                            <input type="text" class="form-control" name="upsel_discount" id="upsel_discount" placeholder="Discount"> </div>
                                                        <div class="col-md-12 m-b-20">
                                                            <input type="text" class="form-control" name="upsel_note" id="upsel_note" placeholder="Note"> </div>
                                                    </div>
                                            </div>
                                                </form>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect" id="config">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">
    
    $(function(e){
        $('#config').click(function(e){
            var id = $('#upsel_id').val();
            var quantity = $('#upsel_quantity').val();
            var discount = $('#upsel_discount').val();
            var note = $('#upsel_note').val();
            $.ajax({
                type : 'POST',
                url:'{{ route('products.updateupsells')}}',
                cache: false,
                data:{
                    id: id,
                    quantity: quantity,
                    discount: discount,
                    note: note,
                    _token : '{{ csrf_token() }}'
                },
                success:function(response){
                    if(response.success == true){
                        toastr.success('Good Job.', 'Upsell Has been Addess Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    }
                    location.reload();
            }});
        });
    });
    
    $(function(e){
        $('#deleteupsel').click(function(e){
            var id = $(this).data('id');
            $.ajax({
                type : 'POST',
                url:'{{ route('products.deleteupsells')}}',
                cache: false,
                data:{
                    id: id,
                    _token : '{{ csrf_token() }}'
                },
                success:function(response){
                    if(response.success == true){
                        toastr.success('Good Job.', 'Upsell Has been Addess Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    }
                    location.reload();
            }});
        });
    });
    $(function () {
        $('body').on('click', '.editProduct', function () {
        var product_id = $(this).data('id');
        //console.log(product_id);
            $.get("{{ route('products.index') }}" +'/' + product_id +'/upsells/edit', function (data) {
                //console.log(product_id);
                $('#config_upsel').modal('show');
                $('#upsel_id').val(data.id);
                $('#upsel_quantity').val(data.quantity);
                $('#upsel_discount').val(data.discount);
                $('#upsel_note').val(data.note);
            });
        });
    });
</script>
@endsection