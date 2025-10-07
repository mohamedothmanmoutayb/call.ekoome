@extends('backend.layouts.app')
@section('css')

<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/css/vendors/datatable-extension.css')}}">
    @endsection
@section('content')
    <style>
        .hiddenRow {
            padding: 0 !important;
        }
    </style>
    @if (Auth::user()->id_role != '3')
        <style>
            .multi {
                display: none;
            }
        </style>
    @endif
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-xxl flex-grow-1 container-p-y">
            @if (Auth::user()->id_role != '3')
                <div class="page-breadcrumb">
                    <div class="row">
                        <div class="col-9 align-self-center">
                            <h4 class="page-title"><span class="text-muted fw-light">Dashboard /</span> Leads</h4>
                            <!-- <div class="form-group mt-2 text-left">
                                <select id="pagination" class="form-control" style="width: 80px">
                                    <option value="10" @if ($items == 10) selected @endif>10</option>
                                    <option value="50" @if ($items == 50) selected @endif>50</option>
                                    <option value="100" @if ($items == 100) selected @endif>100</option>
                                    <option value="250" @if ($items == 250) selected @endif>250</option>
                                    <option value="500" @if ($items == 500) selected @endif>500</option>
                                    <option value="1000" @if ($items == 1000) selected @endif>1000</option>
                                </select>
                            </div> -->
                        </div>
                        <div class="col-3 align-self-center mb-30" style="margin-bottom:30px">
                            <div class="row">
                                <button id="exportss" class="btn btn-primary btn-rounded w-50">Export</button>
                                <a type="button" href="{{ route('leads.inassigned') }}"
                                    class="btn btn-primary btn-rounded waves-effect waves-light w-50">InAssigned</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- ============================================================== -->
            <!-- <div class="row">
                <div class="col-12">
                    <h4>
                        @if (!empty($type))
                            {{ $type }} Leads
                        @endif
                    </h4>
                </div>
            </div> -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <!-- Column -->
                    <div class="card">
                        <div class="card-body">

                            <div class="form-group mb-0">
                                <form>
                                    <div class="row">
                                        <div class="col-md-11 col-sm-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="search" id="search_2"
                                                    placeholder="Ref , Name Customer , Phone , Price" aria-label=""
                                                    aria-describedby="basic-addon1">
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-12">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button"
                                                    id="searchdetai">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <!-- Column -->
                    <div class="card">
                        <div class="card-body">

                            <!-- Add Contact Popup Model -->
                            <div id="StatusLeads" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">List History</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <from class="form-horizontal form-material">
                                                <div class="table-responsive">
                                                    <table id="demo-foo-addrow"
                                                        class="table m-t-30 table-hover contact-list" data-paging="true"
                                                        data-paging-size="7">
                                                        <thead>
                                                            <tr>
                                                                <th>Agent</th>                                                              
                                                                <th>Status</th>
                                                                <th>Date Action</th>
                                                                <th>Comment</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="" id="history">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </from>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <div class="dt-ext table-responsive theme-scrollbar">
                                <table class="display" id="keytable">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="selectall custom-control-input"
                                                        id="chkCheckAll" required>
                                                    <label class="custom-control-label" for="chkCheckAll"></label>
                                                </div>
                                            </th>
                                            <th>Réf</th>
                                            <th>Products</th>
                                            <th>Name</th>
                                            <th>City</th>
                                            <th>Phone</th>
                                            <th>Lead Value</th>
                                            <th>Confirmation</th>
                                            <th>Agent</th>
                                            <th>Last Update Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="alldata">
                                        <?php
                                        $counter = 1;
                                        ?>
                                        @foreach ($leads as $key => $v_lead)
                                            <tr class="accordion-toggle data-item" data-id="{{ $v_lead['id'] }}">
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="ids"
                                                            class="custom-control-input checkBoxClass"
                                                            value="{{ $v_lead['id'] }}" id="pid-{{ $counter }}">
                                                        <label class="custom-control-label"
                                                            for="pid-{{ $counter }}"></label>
                                                    </div>
                                                </td>
                                                <td data-id="{{ $v_lead['id'] }}" data-bs-toggle="tooltip">
                                                    {{ $v_lead['n_lead'] }}  @if($v_lead['isrollback'])  <span class="badge bg-danger">rolled back</span>   @endif
                                                    <br>
                                                    @if(!empty($v_lead['id_order']))
                                                    {{ $v_lead['id_order'] }}
                                                    <br>@endif
                                                    @if (!empty($v_lead['leadproduct']))
                                                        <span
                                                            class="badge bg-info">+Upssel{{ $v_lead['leadproduct']->where('isupsell', '1')->count() }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$v_lead->ProductName($v_lead->id)}}
                                                </td>
                                                <td>{{ $v_lead['name'] }}</td>
                                                <td>
                                                    {{ strval($v_lead->CitieName($v_lead->id))}}
                                                </td>
                                                <td><a href="tel:{{ $v_lead['phone'] }}">{{ $v_lead['phone'] }}</a></td>
                                                <td>{{ $v_lead['lead_value'] }}</td>
                                                <td>
                                                    @if ($v_lead['status_confirmation'] == 'new order')
                                                        <span
                                                            class="badge bg-warning">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'confirmed')
                                                        <span
                                                            class="badge bg-success">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'canceled')
                                                        <span
                                                            class="badge bg-danger">{{ $v_lead['status_confirmation'] }}</span>
                                                    @else
                                                        <span
                                                            class="badge bg-primary">{{ $v_lead['status_confirmation'] }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!empty($v_lead['assigned']->name))
                                                        {{ $v_lead['assigned']->name }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($v_lead['last_status_change'])
                                                        {{ $v_lead['last_status_change'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($v_lead['created_at'])
                                                        {{ $v_lead['created_at'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button"
                                                            class="btn btn-primary dropdown-toggle rounded"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu animated slideInUp"
                                                            x-placement="bottom-start">
                                                            <a class="dropdown-item seehystory" id="seehystory"
                                                                data-id="{{ $v_lead['id'] }}"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-history-toggle"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 20.777a8.942 8.942 0 0 1 -2.48 -.969"></path><path d="M14 3.223a9.003 9.003 0 0 1 0 17.554"></path><path d="M4.579 17.093a8.961 8.961 0 0 1 -1.227 -2.592"></path><path d="M3.124 10.5c.16 -.95 .468 -1.85 .9 -2.675l.169 -.305"></path><path d="M6.907 4.579a8.954 8.954 0 0 1 3.093 -1.356"></path><path d="M12 8v4l3 3"></path></svg>
                                                                History</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('leads.edit', $v_lead['id']) }}"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-list-details"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M13 5h8"></path><path d="M13 9h5"></path><path d="M13 15h8"></path><path d="M13 19h5"></path><path d="M3 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"></path><path d="M3 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"></path></svg> Details</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $counter = $counter + 1; ?>
                                        @endforeach
                                    </tbody>
                                    <tbody id="contentdata" class="datasearch"></tbody>
                                </table>
                                {{ $leads->withQueryString()->links('vendor.pagination.courier') }}
                            </div>
                            <div id="statusconfirmed" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Choose Date Delivred</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <from class="form-horizontal form-material">
                                            <div class="modal-body">
                                                <div class="col-lg-12">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <h3></h3>
                                                                <div class="row">
                                                                    <div class="col-md-12 col-sm-12 my-4">
                                                                        <input type="hidden" class="form-control"
                                                                            id="leads_ids">
                                                                        <input type="date" class="form-control"
                                                                            id="date_delivreds" placeholder="">
                                                                    </div>
                                                                    <div class="col-md-12 col-sm-12 my-4">
                                                                        <textarea class="form-control" id="comment_stass"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect editlead"
                                                    id="statusconfirmeds">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </from>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
        </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->

    <div id="listlead" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:1200px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">List Leads</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <table id=""
                                        class="table table-bordered table-striped table-hover contact-list"
                                        data-paging="true" data-paging-size="7">
                                        <thead>
                                            <tr>
                                                {{-- <th>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="selectall custom-control-input"
                                                            id="chkCheckAll" required>
                                                        <label class="custom-control-label" for="chkCheckAll"></label>
                                                    </div>
                                                </th> --}}
                                                <th>Réf</th>
                                                <th>Products</th>
                                                <th>Name</th>
                                                <th>City</th>
                                                <th>Phone</th>
                                                <th>Lead Value</th>
                                                <th>Confirmation</th>
                                                <th>Assigned</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listleadss" class="datasearch"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- Add Contact Popup Model -->
    <!-- Add Contact Popup Model -->
    <div id="editsheet" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:1500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Details Lead : <span id="statusleadss"></sapn>
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <from class="form-horizontal form-material">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card" style="box-shadow: 0px 0px 8px 3px;">
                                            <div class="card-body">
                                                <p>
                                                <h4 class="card-title"style="font-size: 25px;">
                                                    <i class="mdi mdi-menu font-50" style="margin-right: 30px;"></i>Detail
                                                    Product - <span id="n_order">N Command : </span>
                                                </h4>
                                                <form class="form pt-3"style="margin-left: 39px;">
                                                    <div class="row col-12">
                                                        <div class="form-group col-6">
                                                            <label>Product Name :</label>
                                                            <input type="text" class="form-control" id="product"
                                                                name="product" value="" />
                                                        </div>
                                                        <div class="form-group m-r-2 col-6">
                                                            <label>Total Price :</label>
                                                            <input type="text" class="form-control" id="lead_value"
                                                                name="price" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-6">
                                                            <label>Link Product :</label>
                                                            <input type="text" class="form-control" id="link_products"
                                                                value="" />
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label>Link Video :</label>
                                                            <input type="text" class="form-control" id="link_video" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-6">
                                                            <label>Description :</label>
                                                            <textarea class="form-control" id="description_product" style="height: 136px; max-height: 103px;"></textarea>
                                                        </div>
                                                        <div class="form-group col-6" id="product_image">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12 align-self-center">
                                                            <div class="form-group mb-0 text-center">
                                                                <input type="hidden" id="lead_id" />
                                                                <button type="button"
                                                                    class="btn btn-primary btn-rounded m-t-10 mb-2 upsell">Add
                                                                    Upsell</button>
                                                                <button type="button"
                                                                    class="btn btn-primary btn-rounded m-t-10 mb-2 infoupsell">Information
                                                                    Upsell</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card" style="box-shadow: 0px 0px 8px 3px;">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <h4 class="card-title"style="font-size: 25px;">
                                                            <i class="mdi mdi-account-circle"
                                                                style="margin-right: 30px;"></i>Customer Information
                                                        </h4>
                                                    </div>
                                                    <div class="col-4" id="call">
                                                        <a class="btn btn-success waves-effect" id="whtsapp"
                                                            href="https://wa.me/" target="_blank"><i
                                                                class="mdi mdi-whatsapp"></i>Whtsapp</a>
                                                        <a class="btn btn-success waves-effect" id="call1"
                                                            href="tel:"><i class="mdi mdi-call-made"></i>Call</a>
                                                    </div>

                                                </div>

                                                <form class="form pt-3"style="margin-left: 39px;">
                                                    <div class="row">
                                                        <div class="form-group col-6">
                                                            <label>Customer Name :</label>
                                                            <input type="text" class="form-control" id="customer_name"
                                                                name="product" value="" />
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label>Phone 1 :</label>
                                                            <input type="text" class="form-control"
                                                                id="mobile_customer" value="" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-6">
                                                            <label>Phone 2 :</label>
                                                            <input type="text" class="form-control"
                                                                id="mobile2_customer" value="" />
                                                            <div id="call2">
                                                                <a class="btn btn-success waves-effect" id="wht2"
                                                                    href="https://wa.me/" target="_blank"><i
                                                                        class="mdi mdi-whatsapp"></i>Whtsapp</a>
                                                                <a class="btn btn-success waves-effect" id="call3"
                                                                    href="tel:"><i
                                                                        class="mdi mdi-call-made"></i>Call</a>
                                                            </div>

                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label>Address :</label>
                                                            <textarea class="form-control" id="customer_address"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 my-4">
                                                            <label>City :</label>
                                                            <select class="form-control" id="id_cityy"
                                                                style="width:100%">
                                                                <option value=" ">Select City</option>

                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Zone :</label>
                                                            <select class="form-control" id="id_zonee"
                                                                style="width:100%">
                                                                <option>Select Zone</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-12">
                                                            <label>Note :</label>
                                                            <textarea class="form-control" id="customers_note"></textarea>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 align-self-center">
                                        <div class="form-group mb-0 text-center">
                                            <input type="hidden" id="lead_id" />
                                            <button class="btn btn-success btn-rounded m-t-10 mb-2 confiremd"
                                                id="confirmed">Confirmed</button>
                                            <button type="button" class="btn btn-orange btn-rounded m-t-10 mb-2 "
                                                id="unrech">no answer</button>
                                            <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 "
                                                id="callater">CALL LATER</button>
                                            <button type="button" class="btn btn-danger btn-rounded m-t-10 mb-2 "
                                                id="cancel">CANCEL</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </from>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div id="datedeli" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Choose Date Delivred</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <from class="form-horizontal form-material">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <h3></h3>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-4">
                                                <input type="hidden" class="form-control" id="lead_id">
                                                <input type="date" class="form-control" id="date_delivred"
                                                    placeholder="">
                                            </div>
                                            <div class="col-md-12 col-sm-12 my-4">
                                                <textarea class="form-control" id="comment_sta"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect editlead"
                            id="datedelivred">Save</button>
                        <button type="button" class="btn btn-primary waves-effect"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </from>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- Add Contact Popup Model -->
    <div id="autherstatus" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Note Status</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <from class="form-horizontal form-material">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <h3></h3>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-4">
                                                <input type="hidden" class="form-control" id="leads_id">
                                                <input type="date" class="form-control" id="date_status"
                                                    placeholder="">
                                            </div>
                                            <div class="col-md-12 col-sm-12 my-4">
                                                <textarea class="form-control" id="coment_sta"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect editlead"
                            id="changestatus">Save</button>
                        <button type="button" class="btn btn-primary waves-effect"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </from>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- Add Contact Popup Model -->
    <div id="addreclamation" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:1200px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Complaint</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <from class="">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <h3>Create Complaint</h3>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-4">
                                                <input type="hidden" class="form-control" id="lead_id_recla"
                                                    placeholder="N Lead">
                                            </div>
                                            <div class="col-md-12 col-sm-12 my-4">
                                                <select class="form-control" id="id_service" name="id_service">
                                                    <option value="">Select Service</option>
                                                    <option value="">Livreur</option>
                                                    <option value="">Stock</option>
                                                    <option value="">Call Center</option>
                                                    <option value="">Financier</option>
                                                </select>
                                            </div>
                                            <div class="col-md-12 col-sm-12 my-4">
                                                <textarea type="text" class="form-control" id="reclamation" placeholder="Reclamation"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect adrecla" id="adrecla">Save</button>
                        <button type="button" class="btn btn-primary waves-effect"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </from>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    @if (Auth::user()->id_role != '3')
        <div id="searchdetails" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Choose Date Delivred</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <from class="form-horizontal form-material">
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <h3></h3>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 my-4">
                                                    <input type="hidden" class="form-control" id="leads_id">
                                                    <input type="date" class="form-control" id="date_delivredss"
                                                        placeholder="">
                                                </div>
                                                <div class="col-md-12 col-sm-12 my-4">
                                                    <textarea class="form-control" id="comment_stas"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary waves-effect editlead"
                                id="datedelivreds">Save</button>
                            <button type="button" class="btn btn-primary waves-effect"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </from>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <div id="callaterpopup" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Choose Date Call Later</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <from class="form-horizontal form-material">
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <h3></h3>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 my-4">
                                                    <input type="hidden" class="form-control" id="leads_id">
                                                    <input type="date" class="form-control pickatime-format-label"
                                                        id="date_call" placeholder="">
                                                </div>
                                                <div class="col-md-12 col-sm-12 my-4">
                                                    <input type="time" class="form-control pickatime-format-label"
                                                        id="time_call" placeholder="">
                                                </div>
                                                <div class="col-md-12 col-sm-12 my-4">
                                                    <textarea class="form-control" id="comment_call"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary waves-effect editlead"
                                id="datecall">Save</button>
                            <button type="button" class="btn btn-primary waves-effect"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </from>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <div id="canceledform" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Note Canceled</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <from class="form-horizontal form-material">
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <h3></h3>
                                            <div class="row">
                                                <div class="col-md-12 col-sm-12 my-4">
                                                    <input type="hidden" class="form-control" id="leads_id">
                                                </div>
                                                <div class="col-md-12 col-sm-12 my-4">
                                                    <textarea class="form-control" id="comment_stas_can"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary waves-effect editlead"
                                id="notecanceled">Save</button>
                            <button type="button" class="btn btn-primary waves-effect"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </from>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endif


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    {{-- <script
        src="{{ asset('/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js') }}">
    </script> --}}
@section('script')

<script src="{{ asset('public/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/dataTables.scroller.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/datatable/datatable-extension/custom.js')}}"></script>
@endsection
    <script type="text/javascript">
        $(function(e) {
            $('#adrecla').click(function(e) {
                var idlead = $('#lead_id_recla').val();
                var reclamation = $('#reclamation').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('reclamations.store') }}',
                    cache: false,
                    data: {
                        id: idlead,
                        reclamation: reclamation,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.', 'Lead Has been Update Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                        }
                    }
                });
            });
        });
        $(function(e) {
            $('#datedelivreds').click(function(e) {
                var id = $('#lead_id').val();
                var customename = $('#customer_name').val();
                var customerphone = $('#mobile_customer').val();
                var customerphone2 = $('#mobile2_customer').val();
                var customeraddress = $('#customer_address').val();
                var customercity = $('#id_cityy').val();
                var customerzone = $('#id_zonee').val();
                var leadvalue = $('#lead_value').val();
                var datedelivred = $('#date_delivredss').val();
                var commentdeliv = $('#comment_stas').val();
                $.ajax({
                    type: "POST",
                    url: '{{ route('leads.confirmed') }}',
                    cache: false,
                    data: {
                        id: id,
                        customename: customename,
                        customerphone: customerphone,
                        customerphone2: customerphone2,
                        customeraddress: customeraddress,
                        customercity: customercity,
                        customerzone: customerzone,
                        leadvalue: leadvalue,
                        commentdeliv: commentdeliv,
                        datedelivred: datedelivred,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.', 'Lead Has been Update Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                            $('#searchdetails').modal('hide');
                            location.reload();
                        }
                    }
                });
            });
        });
        $(function(e) {
            $('#statusconfirmeds').click(function(e) {
                var id = $('#leads_ids').val();
                var datedelivred = $('#date_delivreds').val();
                var commentdeliv = $('#comment_stas').val();
                $.ajax({
                    type: "POST",
                    url: '{{ route('leads.confirmed') }}',
                    cache: false,
                    data: {
                        id: id,
                        commentdeliv: commentdeliv,
                        datedelivred: datedelivred,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.', 'Lead Has been Update Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                            $('#date_delivreds').val('');
                            $('#comment_stas').val('');
                            $('#leads_id').val('');
                            $('#statusconfirmed').modal('hide');
                            location.reload();
                        }
                    }
                });
            });
        });
        $(function(e) {
            $('#notecanceled').click(function(e) {
                var id = $('#leads_id').val();
                var commentecanceled = $('#comment_stas_can').val();
                $.ajax({
                    type: "POST",
                    url: '{{ route('leads.canceled') }}',
                    cache: false,
                    data: {
                        id: id,
                        commentecanceled: commentecanceled,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.', 'Lead Has been Update Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                            location.reload();
                        }
                    }
                });
            });
        });

        $(function(e) {
            $('#datecall').click(function(e) {
                var idlead = $('#leads_id').val();
                var date = $('#date_call').val();
                var time = $('#time_call').val();
                var comment = $('#comment_call').val();
                var status = "call later";
                //console.log(namecustomer);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.call') }}',
                    cache: false,
                    data: {
                        id: idlead,
                        date: date,
                        time: time,
                        comment: comment,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.', 'Lead Has been Update Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                            location.reload();
                        }
                    }
                });
            });
        });


        $(function(e) {
            $('#changestatus').click(function(e) {
                var idlead = $('#leads_id').val();
                var date = $('#date_status').val();
                var comment = $('#coment_sta').val();
                //console.log(namecustomer);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.notestatus') }}',
                    cache: false,
                    data: {
                        id: idlead,
                        date: date,
                        comment: comment,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            $('#datedeli').modal('hide');
                            toastr.success('Good Job.', 'Lead Has been Update Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                        }
                    }
                });
            });
        });

        $(function(e) {
            $('.seehystory').click(function(e) {
                $value = $(this).data('id');
                //alert($value);
                //console.log(namecustomer);
                $.ajax({
                    type: 'get',
                    url: '{{ route('leads.seehistory') }}',
                    cache: false,
                    data: {
                        'id': $value
                    },
                    success: function(data) {
                        $('#StatusLeads').modal('show');
                        $('#history').html(data);
                        console.log(data);
                    }
                });
            });
        });

        $(function(e) {
            $('#datedelivred').click(function(e) {
                var idlead = $('#lead_id').val();
                var date = $('#date_delivred').val()
                //console.log(namecustomer);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.date') }}',
                    cache: false,
                    data: {
                        id: idlead,
                        date: date,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            $('#datedeli').modal('hide');
                            toastr.success('Good Job.', 'Lead Has been Update Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                        }
                    }
                });
            });
        });

        ///list lead search



        $('#searchdetai').click(function(e) {
            e.preventDefault();
            var n_lead = $('#search_2').val();
            $('#listlead').modal('show');
            //console.log(agent);
            $.ajax({
                type: "get",
                url: '{{ route('leads.leadsearch') }}',
                data: {
                    n_lead: n_lead,
                },
                success: function(data) {
                    $('#listleadss').html(data);
                }
            });
        });

        $(document).on('click', '.next', function() {
            var id = $('#next_id').val();
            $.get("{{ route('leads.index') }}" + '/' + id + '/details', function(data) {
                $('#editsheet').modal('show');

                $('#lead_id').val(data[0].leads[0].id);
                $('#name_custome').val(data[0].leads[0].name);
                $('#mobile_customer').val(data[0].leads[0].phone);
                $('#mobile2_customer').val(data[0].leads[0].phone2);
                $('#customer_adress').val(data[0].leads[0].address);
                $('#link_products').val(data[0].products[0].link);
                $('#lead_note').val(data[0].leads[0].note);
                $('#id_cityy').val(data[0].leads[0].id_city);
                $('#next_id').val(data[0].leads[0].id - 1);
                $('#previous_id').val(data[0].leads[0].id + 1);
                for (var i in data) {
                    var quantity = data[0].leads[0].quantity;
                    var id_product = data[0].leads[0].id_product;
                    var price = data[0].leads[0].lead_value;
                    $('#addr' + i).html("<td>" + (i + 1) +
                        "</td><td><div class='form-control-wrap'><select class='form-control form-select select2-accessible' data-placeholder='sélectionnez une opltion' required='' id='product_lead' name='id_product[]' data-select2-id='fv-topics' tabindex='-1' aria-hidden='true'>@foreach ($productss as $v_product) @foreach ($v_product['products'] as $product)<option value='{{ $product['id'] }}' {{ $product['id'] == '"+ data[i].id_product +"' ? 'selected' : '' }}>{{ $product['name'] }}</option>@endforeach @endforeach</select></div> </td><td><input  name='quantity[]' id='quantity_lead' type='text' placeholder='Quantity' value='" +
                        quantity +
                        "' required class='form-control input-md'></td><td><input  name='price[]' id='total_lead' type='text' placeholder='Lead Value' value='" +
                        price + "' required class='form-control input-md'></td>");

                }

            });
        });
        $(document).on('click', '.previous', function() {
            var id = $('#previous_id').val();
            $.get("{{ route('leads.index') }}" + '/' + id + '/details', function(data) {
                $('#editsheet').modal('show');

                $('#lead_id').val(data[0].leads[0].id);
                $('#name_custome').val(data[0].leads[0].name);
                $('#mobile_customer').val(data[0].leads[0].phone);
                $('#mobile2_customer').val(data[0].leads[0].phone2);
                $('#customer_adress').val(data[0].leads[0].address);
                $('#lead_note').val(data[0].leads[0].note);
                $('#id_cityy').val(data[0].leads[0].id_city);
                $('#next_id').val(data[0].leads[0].id - 1);
                $('#previous_id').val(data[0].leads[0].id + 1);
                for (var i in data) {
                    var quantity = data[0].leads[0].quantity;
                    var id_product = data[0].leads[0].id_product;
                    var price = data[0].leads[0].lead_value;
                    $('#addr' + i).html("<td>" + (i + 1) +
                        "</td><td><div class='form-control-wrap'><select class='form-control form-select select2-accessible' data-placeholder='sélectionnez une opltion' required='' id='product_lead' name='id_product[]' data-select2-id='fv-topics' tabindex='-1' aria-hidden='true'>@foreach ($productss as $v_product) @foreach ($v_product['products'] as $product)<option value='{{ $product['id'] }}' {{ $product['id'] == '"+ data[i].id_product +"' ? 'selected' : '' }}>{{ $product['name'] }}</option>@endforeach @endforeach</select></div> </td><td><input  name='quantity[]' id='quantity_lead' type='text' placeholder='Quantity' value='" +
                        quantity +
                        "' required class='form-control input-md'></td><td><input  name='price[]' id='total_lead' type='text' placeholder='Lead Value' value='" +
                        price + "' required class='form-control input-md'></td>");

                }

            });
        });

        $(document).on('click', '#searchdetail', function() {
            var id = $('#search_2').val();
            $.get("{{ route('leads.index') }}" + '/' + id + '/seacrhdetails', function(data) {
                $('#editsheet').modal('show');
                //console.log(data);
                $('#lead_id').val(data.id);
                $('#name_custome').val(data.name);
                $('#mobile_customer').val(data.phone);
                $('#mobile2_customer').val(data.phone2);
                $('#customer_adress').val(data.address);
                $('#lead_note').val(data.note);
                $('#id_cityy').val(data.id_city);
                var quantity = data.quantity;
                var price = data.lead_value;
                $('#addr' + 1).html("<td>" + (1) +
                    "</td><td><div class='form-control-wrap'><select class='form-control form-select select2-accessible' data-placeholder='sélectionnez une opltion' required='' id='product_lead' name='id_product[]' data-select2-id='fv-topics' tabindex='-1' aria-hidden='true'>@foreach ($productss as $v_product) @foreach ($v_product['products'] as $product)<option value='{{ $product['id'] }}' {{ $product['id'] == '"+ data.id_product +"' ? 'selected' : '' }}>{{ $product['name'] }}</option>@endforeach @endforeach</select></div> </td><td><input  name='quantity[]' id='quantity_lead' type='text' placeholder='Quantity' value='" +
                    quantity +
                    "' required class='form-control input-md'></td><td><input  name='price[]' id='total_lead' type='text' placeholder='Lead Value' value='" +
                    price + "' required class='form-control input-md'></td>");
            });
        });

        //get price product

        $(document).on('click', '#id_product', function() {
            var id = $('#id_product').val();
            $.get("{{ route('products.index') }}" + '/' + id + '/price', function(data) {
                $('#total').val(data);
            });
        });


        $("#chkCheckAll").click(function() {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });
        $('#exportss').click(function(e) {
            e.preventDefault();
            var allids = [];
            $("input:checkbox[name=ids]:checked").each(function() {
                allids.push($(this).val());
            });
            if (allids != '') {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.exports') }}',
                    cache: false,
                    data: {
                        ids: allids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response, leads) {
                        $.each(allids, function(key, val, leads) {
                            var a = JSON.stringify(allids);
                            window.location = ('/leads/export-downloads/' + a);
                        });
                    }
                });
            } else {
                toastr.warning('Opss.', 'Please Selected Leads!', {
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp",
                    timeOut: 2000
                });
            }
        });


        function toggleText() {
            var x = document.getElementById("multi");
            if (x.style.display === "none") {
                x.style.display = "block";
                $('#timeseconds').val('');
            } else {
                x.style.display = "none";
            }
        }
    </script>
    

    <script>
        document.getElementById('pagination').onchange = function() {
            window.location = window.location.href + "?&items=" + this.value;

        };
    </script>
@endsection
