@extends('backend.layouts.app')
@section('content')
    <style>
        .hiddenRow {
            padding: 0 !important;
        }

        #up {
            display: none;
        }
    </style>
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper" style="display:block">

        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-lg-4 col-md-4  col-sm-12 my-2">
                    <div class="card card-shad card-hover">
                        <div class="card-body analytics-primary">
                            <h4 class="card-title">Incident 24h</h4>
                            <ul class="list-inline two-part row col-12 card-btm">
                                <li class="col-2"><span class="text-orange display-5" style="font-size: 50px;">
                                        <i class="mdi mdi-headset-off"></i>
                                    </span>
                                </li>
                                <li class="text-right col-10" style="font-size: 29px;margin-top: 4px;"> <span
                                        class="counter text-purple"></span>{{ $hour24 }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 my-2">
                    <div class="card card-shad card-hover">
                        <div class="card-body analytics-primary">
                            <h4 class="card-title">Incident 1/2 Days</h4>
                            <ul class="list-inline two-part row col-12 card-btm">
                                <li class="col-2"><span class="text-primary display-5" style="font-size: 50px;">
                                        <i class="mdi mdi-close-circle"></i>
                                    </span>
                                </li>
                                <li class="text-right col-10" style="font-size: 29px;margin-top: 4px;"> <span
                                        class="counter text-purple"></span>{{ $day1ou2 }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 my-2">
                    <div class="card card-shad card-hover">
                        <div class="card-body analytics-primary">
                            <h4 class="card-title">Incident 3/4 Days</h4>
                            <ul class="list-inline two-part row col-12 card-btm">
                                <li class="col-2"><span class="text-primary display-5" style="font-size: 50px;">
                                        <i class="mdi mdi-headset-off"></i>
                                    </span>
                                </li>
                                <li class="text-right col-10" style="font-size: 29px;margin-top: 4px;"> <span
                                        class="counter text-purple"></span>{{ $day3ou4 }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- <div class="row col-lg-12" style="justify-content: space-between; align-content: space-around;align-items: center;">
                                        <div class="form-group mt-2 text-left">
                                            <h4 class="page-title" style="font-size: 27px;">Leads</h4>
                                                <select id="pagination">
                                                    <option value="10" @if ($items == 10) selected @endif  >10</option>
                                                    <option value="50" @if ($items == 50) selected @endif >50</option>
                                                    <option value="100" @if ($items == 100) selected @endif >100</option>
                                                    <option value="200" @if ($items == 200) selected @endif >200</option>
                                                </select>
                                        </div>
                                    </div> -->

                            <div class=" col-lg-12">
                                <!-- <div class="">
                                            <form>
                                            <div class="row">
                                                <div class="col-md-11 col-sm-12">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" name="search" id="search" placeholder="Ref , Name Customer , Phone , Price" aria-label="" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-sm-12">
                                                    <div class="input-group-append">
                                                        <button class="btn bg-white"  type="button" id="down" onclick="toggleText()"><i class="mdi mdi-arrow-down-drop-circle" style="font-size: 34px;color: #0d94c2;line-height: 20.05px;"></i></button>
                                                        <button class="btn bg-white" type="button"  id="up" onclick="toggleText2()"><i class="mdi mdi-arrow-up-drop-circle" style="font-size: 34px;color: #676769;line-height: 20.05px;"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            </form>
                                        </div> -->
                                <div class="form-group multi">
                                    <form style="display: table;margin-left: auto;margin-right: auto;">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12 my-2">
                                                <input type="text" class="form-control" id="search_ref" name="ref"
                                                    placeholder="Ref">
                                            </div>
                                            <div class="col-md-3 col-sm-12 my-2">
                                                <input type="text" class="form-control" name="customer"
                                                    placeholder="Customer Name">
                                            </div>
                                            <div class="col-md-2 col-sm-12 my-2">
                                                <input type="text" class="form-control" name="phone1"
                                                    placeholder="Phone ">
                                            </div>
                                            <div class="col-md-2 col-sm-12 my-2">
                                                <select class="form-control" id="id_cit" name="city">
                                                    <option value="">Select City</option>
                                                    @foreach ($cities as $v_city)
                                                        <option value="{{ $v_city->id }}">{{ $v_city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if (Auth::user()->id_role != '3')
                                                <div class="col-md-2 col-sm-12 my-2">
                                                    <select class="form-control" id="id_seller" name="seller">
                                                        <option value="">Select Seller</option>
                                                        @foreach ($sellers as $v_seller)
                                                            <option value="{{ $v_seller->id }}">{{ $v_seller->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12 my-2">
                                                <select class="select form-control" name="confirmation"
                                                    style="width: 100%;height: 36px;">
                                                    <option value="">Status Confirmation</option>
                                                    <option value="new order">New Order</option>
                                                    <option value="confirmed">Confirmed</option>
                                                    <option value="no answer">No answer</option>
                                                    <option value="no answer 2">No answer 2</option>
                                                    <option value="no answer 3">No answer 3</option>
                                                    <option value="no answer 4">No answer 4</option>
                                                    <option value="no answer 5">No answer 5</option>
                                                    <option value="no answer 6">No answer 6</option>
                                                    <option value="no answer 7">No answer 7</option>
                                                    <option value="no answer 8">No answer 8</option>
                                                    <option value="no answer 9">No answer 9</option>
                                                    <option value="call later">Call later</option>
                                                    <option value="duplicated">Duplicated</option>
                                                    <option value="wrong">Wrong</option>
                                                    <option value="canceled">Canceled</option>
                                                    <option value="canceled by system">Canceled By System</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3 col-sm-12 my-2">
                                                <select class="select2 form-control" name="livraison"
                                                    style="width: 100%;height: 36px;">
                                                    <option value=" ">Status Delivery</option>
                                                    <option value="unpacked">Unpacked</option>
                                                    <option value="picking process">Picking Process</option>
                                                    <option value="item packed">Item Packed</option>
                                                    <option value="shipped">Shipped</option>
                                                    <option value="in transit">In Transit</option>
                                                    <option value="in delivery">In Delivery</option>
                                                    <option value="proseccing">Proseccing</option>
                                                    <option value="delivered">Delivered</option>
                                                    <option value="incident">Incident</option>
                                                    <option value="rejected">Rejected</option>
                                                    <option value="returned">Returned</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-2">
                                                <div class='input-group mb-3'>

                                                    <input type='text' name="date" value=""
                                                        class="form-control timeseconds" id="timeseconds" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="ti ti-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row col-12 my-4">
                                                <div class="col-6 align-self-center">
                                                    <div class="form-group mb-0 text-right">
                                                        <button type="submit" class="btn btn-primary waves-effect"
                                                            style="width:100%">Search</button>
                                                    </div>
                                                </div>
                                                <div class="col-6 align-self-center">
                                                    <div class="form-group mb-0 text-right">
                                                        <a type="button" id="exportss"
                                                            class="btn btn-primary waves-effect text-white"
                                                            style="width:100%">Export</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- End Bread crumb and right sidebar toggle -->
                            <!-- ============================================================== -->
                            <!-- ============================================================== -->
                            <!-- Container fluid  -->
                            <!-- ============================================================== -->

                            <!-- ============================================================== -->
                            <!-- Start Page Content -->
                            <!-- ============================================================== -->

                            <div class="table-responsive">
                                <table id="" class="table table-bordered table-striped table-hover contact-list"
                                    data-paging="true" data-paging-size="7">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="selectall custom-control-input"
                                                        id="chkCheckAll">
                                                    <label class="custom-control-label" for="chkCheckAll"></label>
                                                </div>
                                            </th>
                                            <th>N°</th>
                                            <th>Products</th>
                                            <th>Name</th>
                                            <th>City</th>
                                            <th>Phone</th>
                                            <th>Lead Value</th>
                                            <th>Status Confirmation</th>
                                            <th>Status Livrison</th>
                                            <th>Created At</th>
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
                                                <td data-toggle="tooltip">{{ $v_lead['n_lead'] }}</td>
                                                <td>
                                                    @foreach ($v_lead['product'] as $v_product)
                                                        {{ $v_product['name'] }}
                                                    @endforeach
                                                    <br>
                                                    @if (!empty($v_lead['leadproduct']))
                                                        <span
                                                            class="detaillead badge bg-primary">+{{ $v_lead['leadproduct']->where('isupsell', '1')->count() + $v_lead['leadproduct']->where('iscrosell', '1')->count() }}</span>
                                                    @endif
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
                                                <td><a href="tel:{{ $v_lead['phone'] }}">{{ $v_lead['phone'] }}</a></td>
                                                <td>{{ $v_lead['lead_value'] }}</td>
                                                <td>
                                                    {{ $v_lead['status_confirmation'] }}
                                                </td>
                                                <td>
                                                    {{ $v_lead['status_livrison'] }}
                                                </td>
                                                <td>{{ $v_lead['last_contact'] }}</td>
                                            </tr>
                                            <?php $counter = $counter + 1; ?>
                                        @endforeach
                                    </tbody>
                                    <tbody id="contentdata" class="datasearch"></tbody>
                                </table>
                                {{ $leads->withQueryString()->links('vendor.pagination.courier') }}
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
    </div>
    
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
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->

    <!-- Add Details Popup Model -->
    <div id="editsheet" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:1200px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Details Lead</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <from class="form-horizontal form-material">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <h3>primaryrmation Customer</h3>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 my-2">
                                                <label>Name Customer</label>
                                                <input type="hidden" class="form-control" id="lead_id"
                                                    placeholder="Name Customer">
                                                <input type="text" class="form-control" id="name_custome"
                                                    placeholder="Name Customer">
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-2">
                                                <label>Mobile1 Customer</label>
                                                <input type="text" class="form-control" id="mobile_customer"
                                                    placeholder="Mobile">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-2">
                                                <label>Phone2 Customer</label>
                                                <input type="text" class="form-control" id="mobile2_customer"
                                                    placeholder="Mobile 2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 my-2">
                                                <label>Select City</label>
                                                <select class="form-control" id="id_cityy">
                                                    <option>Select City</option>
                                                    @foreach ($cities as $v_city)
                                                        <option value="{{ $v_city->id }}">{{ $v_city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-2">
                                                <label>Zip Code</label>
                                                <input type="text" class="form-control" id="zipcod_customer"
                                                    placeholder="Zip Code">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-2">
                                                <label>Recipient Number</label>
                                                <input type="text" class="form-control" id="recipient_customer"
                                                    placeholder="Mobile 2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-2">
                                                <label>Address Customer</label>
                                                <textarea type="text" class="form-control" id="customer_adress" placeholder="Address Customer"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <h3>primaryrmation Product</h3>
                                        <div class="row clearfix display" id="divId">
                                            <div class="col-md-12 column">
                                                <table class="table table-bordered table-hover" id="tab_logic">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">
                                                                #
                                                            </th>
                                                            <th class="text-center">
                                                                Product
                                                            </th>
                                                            <th class="text-center">
                                                                Quantity
                                                            </th>
                                                            <th class="text-center">
                                                                Price
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr id='addr0'></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <h5>Confirmation Note</h5>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12">
                                                <textarea type="text" class="form-control" id="lead_note" placeholder="Note" style="height: 113px;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect editlead"
                            id="editsheets">Save</button>
                        <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">Cancel</button>
                    </div>
                    <!--
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary waves-effect previous" name="previous" >previous</button>
                                                    <button type="button" class="btn btn-primary waves-effect next" name="next">Next</button>
                                                    <input type="hidden" id="next_id" />
                                                    <input type="hidden" id="previous_id" />
                                                </div>-->
                </from>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- Add Delivered Date Popup Model -->
    <div id="datedeli" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Choose Date Delivred</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <from class="form-horizontal form-material">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <h3></h3>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-2">
                                                <input type="hidden" class="form-control" id="lead_id">
                                                <input type="date" class="form-control" id="date_delivred"
                                                    placeholder="">
                                            </div>
                                            <div class="col-md-12 col-sm-12 my-2">
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
                        <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">Cancel</button>
                    </div>
                </from>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- Add Status Popup Model -->
    <div id="autherstatus" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Note Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <from class="form-horizontal form-material">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <h3></h3>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-2">
                                                <input type="hidden" class="form-control" id="leads_id">
                                                <input type="date" class="form-control" id="date_status"
                                                    placeholder="">
                                            </div>
                                            <div class="col-md-12 col-sm-12 my-2">
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
                        <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">Cancel</button>
                    </div>
                </from>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Add Upsell Popup Model -->
    <div id="upsell" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Add New Upsell</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <from class="form-horizontal form-material">
                        <div class="form-group">
                            <div class="col-md-12 my-2">
                                <select class="form-control" id="product_upsell">
                                    <option>Select Product</option>
                                </select>
                            </div>
                            <div class="col-md-12 my-2">
                                <input type="hidden" class="form-control" id="lead_upsell" placeholder="Quantity">
                                <input type="text" class="form-control" id="upsell_quantity" placeholder="Quantity">
                            </div>
                            <div class="col-md-12 my-2">
                                <input type="text" class="form-control" id="price_upsell" placeholder="Price">
                            </div>
                        </div>
                    </from>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary waves-effect" id="saveupsell">Save</button>
                    <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(function(e) {
            $("#chkCheckAll").click(function() {
                $(".checkBoxClass").prop('checked', $(this).prop('checked'));
            });
            $('#exportss').click(function(e) {
                e.preventDefault();
                alert('p');
                var allids = [];
                $("input:checkbox[name=ids]:checked").each(function() {
                    allids.push($(this).val());
                });
                if (allids != '') {
                    $.ajax({
                        type: 'POST',
                        url: '', //{{ 'leads.exports' }}
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
        });
    </script>

    <script>
        document.getElementById('pagination').onchange = function() {
            window.location = window.location.href + "?&items=" + this.value;

        };
    </script>
@endsection
