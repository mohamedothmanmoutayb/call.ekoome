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
            @foreach ($agents->get() as $agents)

            <div class="col-12">
                <div class="card">
                  <div class="card-body p-4 pb-0" data-simplebar="">
                    <div class="row flex-wrap">
                      <div class="col-12 col-md mb-4">
                        <div class="card primary-gradient">
                          <div class="card-body text-center px-9 pb-4">
                            <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-primary flex-shrink-0 mb-3 mx-auto">
                              <iconify-icon icon="solar:dollar-minimalistic-linear" class="fs-7 text-white"></iconify-icon>
                            </div>
                            <h6 class="fw-normal fs-3 mb-1">New Leads</h6>
                            <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">{{ count($agents->CountTypeCall($id_assigned, $date_from, $date_two, 'new order')) }}</h4>
                            <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                              Details</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-md mb-4">
                        <div class="card success-gradient">
                          <div class="card-body text-center px-9 pb-4">
                            <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-success flex-shrink-0 mb-3 mx-auto">
                              <iconify-icon icon="solar:recive-twice-square-linear" class="fs-7 text-white"></iconify-icon>
                            </div>
                            <h6 class="fw-normal fs-3 mb-1">Total Lead Confirmed</h6>
                            <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">{{ count($agents->CountTypeCall($id_assigned, $date_from, $date_two, 'confirmed')) }}</h4>
                            <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                              Details</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-md mb-4">
                        <div class="card warning-gradient">
                          <div class="card-body text-center px-9 pb-4">
                            <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-warning flex-shrink-0 mb-3 mx-auto">
                              <iconify-icon icon="ic:outline-backpack" class="fs-7 text-white"></iconify-icon>
                            </div>
                            <h6 class="fw-normal fs-3 mb-1">Total Lead No Asnwer</h6>
                            <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">{{ count($agents->CountTypeCall($id_assigned, $date_from, $date_two, 'NO answer')) }}</h4>
                            <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                              Details</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-md mb-4">
                        <div class="card danger-gradient">
                          <div class="card-body text-center px-9 pb-4">
                            <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-danger flex-shrink-0 mb-3 mx-auto">
                              <iconify-icon icon="ic:baseline-sync-problem" class="fs-7 text-white"></iconify-icon>
                            </div>
                            <h6 class="fw-normal fs-3 mb-1">Total Lead Cancelled</h6>
                            <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">{{ count($agents->CountTypeCall($id_assigned, $date_from, $date_two, 'canceled')) }}</h4>
                            <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                              Details</a>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-md mb-4">
                        <div class="card secondary-gradient">
                          <div class="card-body text-center px-9 pb-4">
                            <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-secondary flex-shrink-0 mb-3 mx-auto">
                              <iconify-icon icon="ic:outline-forest" class="fs-7 text-white"></iconify-icon>
                            </div>
                            <h6 class="fw-normal fs-3 mb-1">Total Delivered</h6>
                               
                            <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">
                                @if (count($agents->CountTypeCall($id_assigned, $date_from, $date_two, 'confirmed')) != 0)
                                Rate =
                                ({{ round(($delivered * 100) / count($agents->CountTypeCall($id_assigned, $date_from, $date_two, 'confirmed')), 2) }}%)
                                 @endif {{ $delivered}}</h4>
                            <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                              Details</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
            <div class="row mt-3 w-100 m-0 p-0 d-none d-lg-flex">
                <ul class="nav nav-pills p-3 mb-3 rounded align-items-center card flex-row">
                    <li class="nav-item mb-2">
                        <a href="javascript:void(0)" onclick="toggleText()"
                            class="nav-link gap-6 note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 fs-11 active"
                            id="all-category">
                            <i class="ti ti-list fill-white"></i>
                            <span class="d-none d-md-block fw-medium">Filter</span>
                        </a>
                    </li>
                    <div class="col-12 row form-group multi-lg" id="multi-lg">
                        <form>
                            <div class="row">
                                <div class="col-md-3 col-sm-12 m-b-20">
                                    <input type="text" class="form-control" id="search_ref" name="ref"
                                        value="{{ request()->input('ref') }}" placeholder="Ref">
                                </div>
                                <div class="col-md-3 col-sm-12 m-b-20">
                                    <input type="text" class="form-control" name="customer"
                                        value="{{ request()->input('customer') }}" placeholder="Customer Name">
                                </div>
                                <div class="col-md-3 col-sm-12 m-b-20">
                                    <input type="text" class="form-control" name="phone1"
                                        value="{{ request()->input('phone1') }}" placeholder="Phone ">
                                </div>
                                <div class="col-md-3 col-sm-12 m-b-20">
                                    <select class="select2 form-control" name="confirmation">
                                        <option value="">Status Confirmation</option>
                                        <option value="new order"
                                            {{ 'new order' == request()->input('confirmation') ? 'selected' : '' }}>New Order
                                        </option>
                                        <option value="confirmed"
                                            {{ 'confirmed' == request()->input('confirmation') ? 'selected' : '' }}>Confirmed
                                        </option>
                                        <option value="no answer"
                                            {{ 'no answer' == request()->input('confirmation') ? 'selected' : '' }}>No answer
                                        </option>
                                        <option value="no answer 2"
                                            {{ 'no answer 2' == request()->input('confirmation') ? 'selected' : '' }}>No answer 2
                                        </option>
                                        <option value="no answer 3"
                                            {{ 'no answer 3' == request()->input('confirmation') ? 'selected' : '' }}>No answer 3
                                        </option>
                                        <option value="no answer 4"
                                            {{ 'no answer 4' == request()->input('confirmation') ? 'selected' : '' }}>No answer 4
                                        </option>
                                        <option value="no answer 5"
                                            {{ 'no answer 5' == request()->input('confirmation') ? 'selected' : '' }}>No answer 5
                                        </option>
                                        <option value="no answer 6"
                                            {{ 'no answer 6' == request()->input('confirmation') ? 'selected' : '' }}>No answer 6
                                        </option>
                                        <option value="no answer 7"
                                            {{ 'no answer 7' == request()->input('confirmation') ? 'selected' : '' }}>No answer 7
                                        </option>
                                        <option value="no answer 8"
                                            {{ 'no answer 8' == request()->input('confirmation') ? 'selected' : '' }}>No answer 8
                                        </option>
                                        <option value="no answer 9"
                                            {{ 'no answer 9' == request()->input('confirmation') ? 'selected' : '' }}>No answer 9
                                        </option>
                                        <option value="call later"
                                            {{ 'call later' == request()->input('confirmation') ? 'selected' : '' }}>Call later
                                        </option>
                                        <option value="canceled"
                                            {{ 'canceled' == request()->input('confirmation') ? 'selected' : '' }}>Canceled
                                        </option>
                                        <option value="canceled by system"
                                            {{ 'canceled by system' == request()->input('confirmation') ? 'selected' : '' }}>
                                            Canceld By System</option>
                                        <option value="outofstock"
                                            {{ 'outofstock' == request()->input('confirmation') ? 'selected' : '' }}>Out Of Stock
                                        </option>
                                        <option value="wrong" {{ 'wrong' == request()->input('confirmation') ? 'selected' : '' }}>
                                            Wrong</option>
                                        <option value="duplicated"
                                            {{ 'duplicated' == request()->input('confirmation') ? 'selected' : '' }}>Duplicated
                                        </option>
                                        <option value="out of area"
                                            {{ 'out of area' == request()->input('confirmation') ? 'selected' : '' }}>out of area
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3 col-sm-12 my-4">
                                    <select class="select2 form-control" name="livraison">
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
                                <div class="col-md-3 col-sm-12 my-4">
                                    <div class='input-group mb-3'>

                                        <input type='text' name="date"
                                            value="{{ $date_from . ' - ' . $date_two }}"
                                            class="form-control timeseconds" id="timeseconds" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <span class="ti-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 align-self-center">
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary waves-effect btn-rounded  "
                                            style="width:100%">Search</button>
                                    </div>
                                </div>
                                <div class="col-md-3 align-self-center">
                                    <div class="form-group mb-3">
                                        <a href="{{ route('leads.index') }}" class="btn btn-primary waves-effect btn-rounded "
                                            style="width:100%">Reset</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                            </div>
                        </form>
                    </div>
                </ul>
        
        
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

                            <!-- ============================================================== -->
                            <!-- End Bread crumb and right sidebar toggle -->
                            <!-- ============================================================== -->
                            <!-- ============================================================== -->
                            <!-- Container fluid  -->
                            <!-- ============================================================== -->

                            <!-- ============================================================== -->
                            <!-- Start Page Content -->
                            <!-- ============================================================== -->

                            <div class="table-responsive border rounded-1" style="margin-top:-20px">
                                <table class="table text-nowrap customize-table mb-0 align-middle">
                                    <thead class="text-dark fs-4">
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
                                                <td data-toggle="tooltip"><span class="badge bg-secondary">{{ $v_lead['n_lead'] }}</span></td>
                                                <td>
                                                    @foreach ($v_lead['product'] as $v_product)
                                                        {{ $v_product['name'] }}
                                                    @endforeach
                                                    <br>
                                                    @if (!empty($v_lead['leadproduct']))
                                                        <span
                                                            class="detaillead label label-info">+{{ $v_lead['leadproduct']->where('isupsell', '1')->count() + $v_lead['leadproduct']->where('iscrosell', '1')->count() }}</span>
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
                                                    @if ($v_lead['status_confirmation'] == 'confirmed')
                                                        <span class="badge bg-success">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'new order')
                                                        <span class="badge bg-info">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'call later')
                                                        <span class="badge bg-warning">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'canceled')
                                                        <span class="badge bg-danger">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'canceled by system')
                                                        <span
                                                            class="badge bg-inverse-subtle">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'out of area')
                                                        <span class="badge"
                                                            style="background-color: #7365f0">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'outofstock')
                                                        <span class="badge"
                                                            style="background-color: #52D3D8">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'duplicated')
                                                        <span
                                                            class="badge bg-primary-subtle">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'wrong')
                                                        <span
                                                            class="badge bg-dark-subtle">{{ $v_lead['status_confirmation'] }}</span>
                                                    @else
                                                        <span class="badge"
                                                            style="background-color: #B31312">{{ $v_lead['status_confirmation'] }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($v_lead['status_livrison'] == 'unpacked')
                                                        <span class="badge bg-warning">{{ $v_lead['status_livrison'] }}</span>
                                                    @elseif($v_lead['status_livrison'] == 'delivered')
                                                        <span class="badge bg-success">{{ $v_lead['status_livrison'] }}</span>
                                                    @elseif($v_lead['status_livrison'] == 'returned')
                                                        <span class="badge bg-danger">{{ $v_lead['status_livrison'] }}</span>
                                                    @elseif($v_lead['status_livrison'] == 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ $v_lead['status_livrison'] }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $v_lead['last_contact'] }}</td>
                                            </tr>
                                            <?php $counter = $counter + 1; ?>
                                        @endforeach
                                    </tbody>
                                    <tbody id="contentdata" class="datasearch"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- .right-sidebar -->
            <!-- ============================================================== -->
            <!-- End Right sidebar -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
    </div>
 
  
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
                     <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                </div>
                <from class="form-horizontal form-material">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <h3>Information Customer</h3>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 my-4">
                                                <label>Name Customer</label>
                                                <input type="hidden" class="form-control" id="lead_id"
                                                    placeholder="Name Customer">
                                                <input type="text" class="form-control" id="name_custome"
                                                    placeholder="Name Customer">
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-4">
                                                <label>Mobile1 Customer</label>
                                                <input type="text" class="form-control" id="mobile_customer"
                                                    placeholder="Mobile">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-4">
                                                <label>Phone2 Customer</label>
                                                <input type="text" class="form-control" id="mobile2_customer"
                                                    placeholder="Mobile 2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 my-4">
                                                <label>Select City</label>
                                                <select class="form-control" id="id_cityy">
                                                    <option>Select City</option>
                                                    @foreach ($cities as $v_city)
                                                        <option value="{{ $v_city->id }}">{{ $v_city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-4">
                                                <label>Zip Code</label>
                                                <input type="text" class="form-control" id="zipcod_customer"
                                                    placeholder="Zip Code">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-4">
                                                <label>Recipient Number</label>
                                                <input type="text" class="form-control" id="recipient_customer"
                                                    placeholder="Mobile 2">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 my-4">
                                                <label>Address Customer</label>
                                                <textarea type="text" class="form-control" id="customer_adress" placeholder="Address Customer"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <h3>Information Product</h3>
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
                        <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
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
                        <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
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
                     <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
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
            if (window.location.href == "https://call.FULFILLEMENT.com/leads") {
                //alert(window.location.href);
                window.location = window.location.href + "?&items=" + this.value;
            } else {
                window.location = window.location.href + "&items=" + this.value;
            }

        };
    </script>
@endsection
