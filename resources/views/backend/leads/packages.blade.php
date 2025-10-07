@extends('backend.layouts.app')
@section('content')
<style>
    .hiddenRow {
    padding: 0 !important;
}
</style>

        <div class="page-wrapper">
         
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-xxl flex-grow-1 container-p-y">
                 <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="page-breadcrumb">
                    <div class="row">
                        <div class="col-5 align-self-center">
                            <h4 class="page-title"><span class="text-muted fw-light">Dashboard /</span> Leads</h4>
                            <div class="d-flex align-items-center">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                             
                                    <div class="form-group mb-0 text-right">
                                        <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 " data-bs-toggle="modal" data-bs-target="#filter">Filters</button>
                                        <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 " data-bs-toggle="modal" data-bs-target="#filter">Send For Delivery</button>
                                        <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 " data-bs-toggle="modal" data-bs-target="#filter">Print Label</button>
                                        <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 " data-bs-toggle="modal" data-bs-target="#filter">Print List Products</button>
                                    </div>
                                <!-- Add Contact Popup Model -->        
                                <div id="filter" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Add New Lead</h4> 
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal form-material">
                                                    <div class="form-group">
                                                        <div class="col-md-12 my-2">
                                                            <input type="text" class="form-control" placeholder="Store Name">
                                                        </div>
                                                        <div class="col-md-12 my-2">
                                                            <input type="text" class="form-control" placeholder="Link">
                                                        </div>
                                                    </div>
                                                </from>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Save</button>
                                                <button type="button" class="btn btn-warning waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>        
                                <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">New Lead</h4> 
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal form-material">
                                                    <div class="form-group">
                                                        <div class="col-md-12 my-2">
                                                            <select class="form-control">
                                                                <option>Select Product</option>
                                                                <option>Desiging</option>
                                                                <option>Development</option>
                                                                <option>Videography</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 my-2">
                                                            <input type="text" class="form-control" placeholder="Name Customer">
                                                        </div>
                                                        <div class="col-md-12 my-2">
                                                            <input type="text" class="form-control" placeholder="Mobile">
                                                        </div>
                                                        <div class="col-md-12 my-2">
                                                            <input type="text" class="form-control" placeholder="Mobile 1">
                                                        </div>
                                                        <div class="col-md-12 my-2">
                                                            <select class="form-control">
                                                                <option>Select Paye</option>
                                                                <option>Desiging</option>
                                                                <option>Development</option>
                                                                <option>Videography</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 my-2">
                                                            <select class="form-control">
                                                                <option>Select City</option>
                                                                <option>Desiging</option>
                                                                <option>Development</option>
                                                                <option>Videography</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 my-2">
                                                            <select class="form-control">
                                                                <option>Select Zone</option>
                                                                <option>Desiging</option>
                                                                <option>Development</option>
                                                                <option>Videography</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 my-2">
                                                            <textarea type="text" class="form-control" placeholder="Address"></textarea>
                                                        </div>
                                                        <div class="col-md-12 my-2">
                                                            <input type="text" class="form-control" placeholder="Total Price">
                                                        </div>
                                                    </div>
                                                </from>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Save</button>
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
                                                <th>ID</th>
                                                <th>Vendor Name</th>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($leads as $key => $v_lead)
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                @foreach($v_lead[0]['leadbyvendor'] as $leadbyuse)
                                                    <span>{{ $leadbyuse['name'] }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach($v_lead[0]['leadproducts'] as $leadbyuse)
                                                    <span>{{ $leadbyuse->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{$v_lead->count()}}</td>
                                        </tr>
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
@endsection