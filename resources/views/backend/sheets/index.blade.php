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
                        <h4 class="page-title"><span class="text-muted fw-light">Dashboard /</span>Sheets</h4>
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

                                    <div class="form-group mb-4 text-right">
                                        <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-target="#add-contact">Add New Sheet</button>
                                        <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-target="#filter">Filters</button>
                                    </div>
                                
                                <!-- Add Contact Popup Model -->        
                                <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Add New Sheet</h4> 
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal form-material">
                                                    <div class="form-group">
                                                        <div class="col-md-12 m-b-20">
                                                            <select class="form-control">
                                                                <option>Select Product</option>
                                                                <option>Desiging</option>
                                                                <option>Development</option>
                                                                <option>Videography</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 m-b-20">
                                                            <select class="form-control">
                                                                <option>Select Wearhouse</option>
                                                                <option>Desiging</option>
                                                                <option>Development</option>
                                                                <option>Videography</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 m-b-20">
                                                            <input type="text" class="form-control" placeholder="Sheet Name"> </div>
                                                        <div class="col-md-12 m-b-20">
                                                            <input type="text" class="form-control" placeholder="Sheet ID"> </div>
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
                                <div id="filter" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Search</h4> 
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
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
                                                <th>Sheets Name</th>
                                                <th>Sheets ID</th>
                                                <th>Created at</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Product1</td>
                                                <td>Sheet Product</td>
                                                <td>1Kpbmzx25uyHbD9g3PAkcS8ZYXMZmB4xKoe6u4-jXTls</td>
                                                <td>12-10-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-bs-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-bs-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Product1</td>
                                                <td>Sheet Product</td>
                                                <td>1Kpbmzx25uyHbD9g3PAkcS8ZYXMZmB4xKoe6u4-jXTls</td>
                                                <td>12-10-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-bs-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-bs-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Product1</td>
                                                <td>Sheet Product</td>
                                                <td>1Kpbmzx25uyHbD9g3PAkcS8ZYXMZmB4xKoe6u4-jXTls</td>
                                                <td>12-10-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-bs-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-bs-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Product1</td>
                                                <td>Sheet Product</td>
                                                <td>1Kpbmzx25uyHbD9g3PAkcS8ZYXMZmB4xKoe6u4-jXTls</td>
                                                <td>12-10-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-bs-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-bs-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Product1</td>
                                                <td>Sheet Product</td>
                                                <td>1Kpbmzx25uyHbD9g3PAkcS8ZYXMZmB4xKoe6u4-jXTls</td>
                                                <td>12-10-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-bs-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-bs-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Product1</td>
                                                <td>Sheet Product</td>
                                                <td>1Kpbmzx25uyHbD9g3PAkcS8ZYXMZmB4xKoe6u4-jXTls</td>
                                                <td>12-10-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-bs-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-bs-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Product1</td>
                                                <td>Sheet Product</td>
                                                <td>1Kpbmzx25uyHbD9g3PAkcS8ZYXMZmB4xKoe6u4-jXTls</td>
                                                <td>12-10-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-bs-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-bs-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
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
@endsection