@extends('backend.layouts.app')
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
    <!-- ============================================================== -->
    <div class="page-wrapper">

        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            @if (Auth::user()->id_role != '3')
                <div class="page-breadcrumb">
                    <div class="row">
                        <div class="col-10 align-self-center">
                            <h4 class="page-title"><span class="text-muted fw-light">Dashboard /</span> Leads</h4>
                            <div class="d-flex align-items-center">

                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <!-- Column -->
                    <div class="card">
                        <div class="card-body">
                            @if (Auth::user()->id_role != '3')
                                <div class="form-group mb-0">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-11 col-sm-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="search" id="search"
                                                        placeholder="Ref , Name Customer , Phone , Price" aria-label=""
                                                        aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-sm-12">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button"
                                                        onclick="toggleText()">Multi</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="form-group multi" id="multi">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12 my-4">
                                                <input type="text" class="form-control" id="search_ref" name="ref"
                                                    placeholder="Ref">
                                            </div>
                                            <div class="col-md-3 col-sm-12 my-4">
                                                <input type="text" class="form-control" name="customer"
                                                    placeholder="Customer Name">
                                            </div>
                                            <div class="col-md-3 col-sm-12 my-4">
                                                <input type="text" class="form-control" name="phone1"
                                                    placeholder="Phone ">
                                            </div>
                                            <div class="col-md-3 col-sm-12 my-4">
                                                <select class="form-control" id="id_cit" name="city">
                                                    <option value="">Select City</option>
                                                    @foreach ($cities as $v_city)
                                                        <option value="{{ $v_city->id }}">{{ $v_city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12 my-4">
                                                <label>Status Confirmation</label>
                                                <select class="select2 form-control" name="confirmation[]"
                                                    multiple="multiple" style="width: 100%;height: 36px;">
                                                    <option value="">Status Confirmation</option>
                                                    <option value="new order">New Order</option>
                                                    <option value="confirmed">Confirmed</option>
                                                    <option value="no answer">No answer</option>
                                                    <option value="call later">Call later</option>
                                                    <option value="canceled">Canceled</option>
                                                </select>
                                            </div>
                                            <div class="col-3 align-self-center">
                                                <label>Date Range</label>
                                                <div class='input-group mb-3'>
                                                    <input type='text' name="date" class="form-control timeseconds"
                                                        id="timeseconds" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="ti-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-12 my-4">
                                                <label>Select Product</label>
                                                <select class="form-control" name="id_prod"
                                                    style="width: 100%;height: 36px;">
                                                    <option value="">Select Product</option>
                                                    @foreach ($productss as $v_product)
                                                        @foreach ($v_product['products'] as $v_pro)
                                                            <option value="{{ $v_pro->id }}">{{ $v_pro->name }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-1 align-self-center">
                                                <div class="form-group mb-0">
                                                    <button type="submit"
                                                        class="btn btn-primary waves-effect btn-rounded m-t-10 mb-2 "
                                                        style="width:100%">Search</button>
                                                </div>
                                            </div>
                                            <div class="col-1 align-self-center">
                                                <div class="form-group mb-0">
                                                    <a href="{{ route('leads.index') }}"
                                                        class="btn btn-primary waves-effect btn-rounded m-t-10 mb-2 "
                                                        style="width:100%">Reset</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div class="form-group">
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
                                                        id="searchdetai">Multi</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
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
                            <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Add New Contact</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <from class="form-horizontal form-material">
                                                <div class="form-group">
                                                    <div class="col-md-12 my-4">
                                                        <input type="text" class="form-control"
                                                            placeholder="Type name">
                                                    </div>
                                                    <div class="col-md-12 my-4">
                                                        <input type="text" class="form-control" placeholder="Email">
                                                    </div>
                                                    <div class="col-md-12 my-4">
                                                        <input type="text" class="form-control" placeholder="Phone">
                                                    </div>
                                                    <div class="col-md-12 my-4">
                                                        <input type="text" class="form-control"
                                                            placeholder="Designation">
                                                    </div>
                                                    <div class="col-md-12 my-4">
                                                        <input type="text" class="form-control" placeholder="Age">
                                                    </div>
                                                    <div class="col-md-12 my-4">
                                                        <input type="text" class="form-control"
                                                            placeholder="Date of joining">
                                                    </div>
                                                    <div class="col-md-12 my-4">
                                                        <input type="text" class="form-control" placeholder="Salary">
                                                    </div>
                                                    <div class="col-md-12 my-4">
                                                        <div
                                                            class="fileupload btn btn-danger btn-rounded waves-effect waves-light btn-sm">
                                                            <span><i class="ion-upload m-r-5"></i>Upload Contact
                                                                Image</span>
                                                            <input type="file" class="upload">
                                                        </div>
                                                    </div>
                                                </div>
                                            </from>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary waves-effect"
                                                data-bs-dismiss="modal">Save</button>
                                            <button type="button" class="btn btn-default waves-effect"
                                                data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- Add Contact Popup Model -->
                            <div id="StatusLeads" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Add New Contact</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <from class="form-horizontal form-material">
                                                <div class="table-responsive">
                                                    <table id="demo-foo-addrow"
                                                        class="table m-t-30 table-hover contact-list" data-paging="true"
                                                        data-paging-size="7">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
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
                            @if (Auth::user()->id_role != '3')
                                <div class="table-responsive">
                                    <table id=""
                                        class="table table-bordered table-striped table-hover contact-list"
                                        data-paging="true" data-paging-size="7">
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
                                                <th>Delivery</th>
                                                <th>Delivery Man</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="alldata">
                                            <?php
                                            $counter = 1;
                                            ?>
                                            @foreach ($livreurs as $key => $v_lead)
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
                                                    <td data-id="{{ $v_lead['id'] }}" id="detaillead" class="detaillead"
                                                        data-toggle="tooltip">
                                                        {{ $v_lead['n_lead'] }}
                                                        <br>
                                                        @if (!empty($v_lead['leadproduct']))
                                                            <span
                                                                class="label label-info">+Upssel{{ $v_lead['leadproduct']->where('isupsell', '1')->count() }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @foreach ($v_lead['product'] as $v_product)
                                                            {{ $v_product['name'] }}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $v_lead['name'] }}</td>
                                                    <td>
                                                        @if (!empty($v_lead['id_city']))
                                                            @foreach ($v_lead['cities'] as $v_city)
                                                                {{ $v_city['name'] }}
                                                            @endforeach
                                                        @else
                                                            {{ $v_lead['city'] }}
                                                        @endif
                                                    </td>
                                                    <td><a href="tel:{{ $v_lead['phone'] }}">{{ $v_lead['phone'] }}</a>
                                                    </td>
                                                    <td>{{ $v_lead['lead_value'] }}</td>
                                                    <td>
                                                        {{ $v_lead['status_confirmation'] }}
                                                    </td>
                                                    <td>
                                                        {{ $v_lead['status_livrison'] }}
                                                    </td>
                                                    <td>
                                                        @foreach ($v_lead['livreur'] as $v_livreur)
                                                            {{ $v_livreur->name }}<br>{{ $v_livreur->telephone }}
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $v_lead['created_at']->Format('Y-m-d') }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="ti ti-settings"></i>
                                                            </button>
                                                            <div class="dropdown-menu animated slideInUp"
                                                                x-placement="bottom-start"
                                                                style="position: absolute; will-change: transform; transform: translate3d(0px, 35px, 0px);margin-left: -56px !important;">
                                                                <form
                                                                    action="{{ route('relancements.store', $v_lead['id']) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="lead_change"
                                                                        value="{{ $id }}" />
                                                                    <input type="hidden" name="lead_relance"
                                                                        value="{{ $v_lead['id'] }}" />
                                                                    <button class="dropdown-item seehystory"
                                                                        type="submit" data-id="{{ $v_lead['id'] }}"><i
                                                                            class="ti-edit"></i>Send To Shipped</button>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $counter = $counter + 1; ?>
                                            @endforeach
                                        </tbody>
                                        <tbody id="contentdata" class="datasearch"></tbody>
                                    </table>
                                </div>
                                <div id="statusconfirmed" class="modal fade in" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel" aria-hidden="true">
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
                            @endif
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
                                                        <a class="btn btn-success waves-effect mx-1" id="sapp"
                                                        href="https://wa.me/{{ $lead->phone }}?text=hi"
                                                        target="_blank"><i
                                                            class="mdi mdi-whatsapp"></i>Whtsapp</a>
                                                    <a class="btn btn-success waves-effect" id="ccall"
                                                        href="tel:{{ $lead->phone }}"><i
                                                            class="mdi mdi-call-made"></i>Call</a>
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
                                                                <option>Select City</option>

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
                                            <button type="button" class="btn btn-warning btn-rounded m-t-10 mb-2 "
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


    <!-- Add Contact Popup Model -->
    <div id="upsell" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Add New Upsell</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <from class="form-horizontal form-material">
                        <div class="form-group">
                            <div class="col-md-12 my-4">
                                <select class="form-control" id="product_upsell">
                                    <option>Select Product</option>
                                </select>
                            </div>
                            <div class="col-md-12 my-4">
                                <input type="hidden" class="form-control" id="lead_upsell" placeholder="Quantity">
                                <input type="text" class="form-control" id="upsell_quantity" placeholder="Quantity">
                            </div>
                            <div class="col-md-12 my-4">
                                <input type="text" class="form-control" id="price_upsell" placeholder="Price">
                            </div>
                        </div>
                    </from>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect" id="saveupsell">Save</button>
                    <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="info-upssel" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:1200px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Details Upsell</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <from class="form-horizontal form-material">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <h3>Information Upsell</h3>
                                        <div class="col-md-12 column">
                                            <table class="table table-bordered table-hover" id="tab_logic">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">
                                                            Quantity
                                                        </th>
                                                        <th class="text-center">
                                                            Discount
                                                        </th>
                                                        <th class="text-center">
                                                            Note
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="infoupsells">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary waves-effect"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </from>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script
        src="{{ asset('/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js') }}">
    </script>

    <script type="text/javascript">
        $("#mobile_customer").keyup(function() {
            $value = $(this).val();
            if ($value) {
                $('#whtsapp').attr("href", 'https://wa.me/' + $value);
                $('#call1').attr("href", 'tel:' + $value);
            }
        });
        $("#mobile2_customer").keyup(function() {
            $value = $(this).val();
            if ($value) {
                $('#wht2').attr("href", 'https://wa.me/' + $value);
                $('#call3').attr("href", 'tel:' + $value);
            }
        });
        $("#customer_phone").keyup(function() {
            $value = $(this).val();
            if ($value) {
                $('#sapp').attr("href", 'https://wa.me/' + $value);
                $('#ccall').attr("href", 'tel:' + $value);
            }
        });
        $("#search").keyup(function() {
            $value = $(this).val();
            if ($value) {
                $('.alldata').hide();
                $('.datasearch').show();
            } else {
                $('.alldata').show();
                $('.datasearch').hide();
            }
            $.ajax({
                type: 'get',
                url: '{{ route('leads.search') }}',
                data: {
                    'search': $value,
                },
                success: function(data) {
                    $('#contentdata').html(data);
                }
            });
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
        $(document).ready(function() {
            $("#id_cityy").select2({
                dropdownParent: $("#editsheet")
            });
        });

        $(document).ready(function() {
            $("#id_zonee").select2({
                dropdownParent: $("#editsheet")
            });
        });
    </script>
@endsection
