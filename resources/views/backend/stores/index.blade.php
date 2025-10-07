@extends('backend.layouts.app')
@section('content')

        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Stores</h4>
                        <div class="d-flex align-items-center">
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Stores list</h4>
                                <h6 class="card-subtitle"></h6>
                                    <div class="form-group mb-0 text-right">
                                        <button type="button" class="btn btn-info btn-rounded m-t-10 mb-2 " data-toggle="modal" data-target="#add-contact">Add New Contact</button>
                                        <button type="button" class="btn btn-info btn-rounded m-t-10 mb-2 " data-toggle="modal" data-target="#filter">Filters</button>
                                    </div>
                                <!-- Add Contact Popup Model -->        
                                <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Add New Store</h4> 
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
                                                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Save</button>
                                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
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
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
                                                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Search</button>
                                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>
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
                                                <th>Store Name</th>
                                                <th>Link</th>
                                                <th>Created at</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>genelia@gmail.com</td>
                                                <td>23</td>
                                                <td>12-10-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>arijit@gmail.com</td>
                                                <td>26</td>
                                                <td>10-09-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>govinda@gmail.com</td>
                                                <td>28</td>
                                                <td>1-10-2013</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>hritik@gmail.com</td>
                                                <td>25</td>
                                                <td>2-10-2017</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>john@gmail.com</td>
                                                <td>23</td>
                                                <td>10-9-2015</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>pawandeep@gmail.com</td>
                                                <td>29</td>
                                                <td>10-5-2013</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>ritesh@gmail.com</td>
                                                <td>35</td>
                                                <td>05-10-2012</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>salman@gmail.com</td>
                                                <td>27</td>
                                                <td>11-10-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>govinda@gmail.com</td>
                                                <td>18</td>
                                                <td>12-5-2017</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>sonu@gmail.com</td>
                                                <td>36</td>
                                                <td>18-5-2009</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>varun@gmail.com</td>
                                                <td>43</td>
                                                <td>12-10-2010</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>genelia@gmail.com</td>
                                                <td>23</td>
                                                <td>12-10-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>arijit@gmail.com</td>
                                                <td>26</td>
                                                <td>10-09-2014</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td>govinda@gmail.com</td>
                                                <td>28</td>
                                                <td>1-10-2013</td>
                                                <td>
                                                    
                                                    <a href="javascript:void(0)" class="text-inverse pr-2" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a> <a href="javascript:void(0)" class="text-inverse" title="Delete" data-toggle="tooltip"><i class="ti-trash"></i></a>
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
            <footer class="footer text-center">
                All Rights Reserved by FULFILLEMENT admin. Designed and Developed by <a href="">Palace Agency</a>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
@endsection