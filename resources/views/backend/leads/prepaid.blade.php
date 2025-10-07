@extends('backend.layouts.app')
@section('content')
    <style>
        .hiddenRow {
            padding: 0 !important;
        }

        @media (min-width: 900px) {

            .btn1 {
                width: 50%   
            }
          
        }   
        @media (max-width: 400px) {

        .marg{
            margin-top: 20px !important;    
        }
        .customize-table {
      font-size: 12px; 
    }

    .customize-table td,
    .customize-table th {
      padding: 8px 11px !important; 
    }

    .customize-table .badge {
      font-size: 10px;
      padding: 2px 4px;
    }

    .custom-control-label,
    .btn,
    .dropdown-item {
      font-size: 12px;
    }

    .table-responsive {
      overflow-x: auto;
    }

    .card-body {
      padding: 10px 0px !important;
    }
    .lil{
        margin-left: 10px !important;
    }
    .fle {
  display: flex;
  justify-content: center; 
  align-items: center;  
  height: 100%;           
}


        }  
    </style>

<div class="card card-body py-3">
    <div class="row align-items-center">
      <div class="col-12">
        <div class="d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-4 mb-sm-0 card-title lil">Leads Confirmed</h4>
          <nav aria-label="breadcrumb" class="ms-auto">
            <ol class="breadcrumb fle">
              <li class="breadcrumb-item d-flex align-items-center">
                <div class="d-flex flex-column flex-sm-row ">
            
                  <button id="exportss" class="btn btn-primary btn-rounded my-1 ms-sm-2">Export</button>

                  <a type="button" href="{{ route('leads.inassigned') }}" class="btn btn-primary btn-rounded waves-effect waves-light my-1 ms-sm-2">InAssigned</a>
             
                  <button id="listconfirmed" class="btn btn-primary btn-rounded my-1 ms-sm-2">Confirmed Leads</button>
                </div>
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  
    <!-- ============================================================== -->
    
          
        <!-- Desktop version (visible only on large screens and up) -->
<div class="row mt-3 w-100 m-0 d-none d-lg-flex">
    <ul class="nav nav-pills p-3 mb-3 rounded align-items-center card flex-row">
        <li class="nav-item mb-2">
          <a href="javascript:void(0)" onclick="toggleText()" class="nav-link gap-6 note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 fs-11 active" id="all-category">
            <i class="ti ti-list fill-white"></i>
            <span class="d-none d-md-block fw-medium">Filter</span>
          </a>
        </li>
        <div class="col-12 row form-group multi" id="multi" >
            <form>
                <div class="row">
                    <div class="col-md-3 col-sm-12 m-b-20">
                        <input type="text" class="form-control" id="search_ref" name="ref" value="{{ request()->input('ref') }}" placeholder="Ref">
                    </div>
                    <div class="col-md-3 col-sm-12 m-b-20">
                        <input type="text" class="form-control" name="customer" value="{{ request()->input('customer') }}" placeholder="Customer Name">
                    </div>
                    <div class="col-md-3 col-sm-12 m-b-20">
                        <input type="text" class="form-control" name="phone1" value="{{ request()->input('phone1') }}" placeholder="Phone ">
                    </div>
                    <div class="col-md-3 col-sm-12 m-b-20">
                        <select class="select2 form-control" name="confirmation">
                            <option value="">Status Confirmation</option>
                            <option value="new order" {{ 'new order' == request()->input('confirmation') ? 'selected' : '' }}>New Order</option>
                            <option value="confirmed" {{ 'confirmed' == request()->input('confirmation') ? 'selected' : '' }}>Confirmed</option>
                            <option value="no answer" {{ 'no answer' == request()->input('confirmation') ? 'selected' : '' }}>No answer</option>
                            <option value="no answer 2" {{ 'no answer 2' == request()->input('confirmation') ? 'selected' : '' }}>No answer 2</option>
                            <option value="no answer 3" {{ 'no answer 3' == request()->input('confirmation') ? 'selected' : '' }}>No answer 3</option>
                            <option value="no answer 4" {{ 'no answer 4' == request()->input('confirmation') ? 'selected' : '' }}>No answer 4</option>
                            <option value="no answer 5" {{ 'no answer 5' == request()->input('confirmation') ? 'selected' : '' }}>No answer 5</option>
                            <option value="no answer 6" {{ 'no answer 6' == request()->input('confirmation') ? 'selected' : '' }}>No answer 6</option>
                            <option value="no answer 7" {{ 'no answer 7' == request()->input('confirmation') ? 'selected' : '' }}>No answer 7</option>
                            <option value="no answer 8" {{ 'no answer 8' == request()->input('confirmation') ? 'selected' : '' }}>No answer 8</option>
                            <option value="no answer 9" {{ 'no answer 9' == request()->input('confirmation') ? 'selected' : '' }}>No answer 9</option>
                            <option value="call later" {{ 'call later' == request()->input('confirmation') ? 'selected' : '' }}>Call later</option>
                            <option value="canceled" {{ 'canceled' == request()->input('confirmation') ? 'selected' : '' }}>Canceled</option>
                            <option value="canceled by system" {{ 'canceled by system' == request()->input('confirmation') ? 'selected' : '' }}>Canceld By System</option>
                            <option value="outofstock" {{ 'outofstock' == request()->input('confirmation') ? 'selected' : '' }}>Out Of Stock</option>
                            <option value="wrong" {{ 'wrong' == request()->input('confirmation') ? 'selected' : '' }}>Wrong</option>
                            <option value="duplicated" {{ 'duplicated' == request()->input('confirmation') ? 'selected' : '' }}>Duplicated</option>
                            <option value="out of area" {{ 'out of area' == request()->input('confirmation') ? 'selected' : '' }}>out of area</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-3 align-self-center" >
                        <div class='theme-form mb-3'>
                            <input type="text" class="form-control flatpickr-input" name="date" value="{{ request()->input('date') }}" id="datepicker-range" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12 m-b-20">
                        <select class="select2 form-control" id="select_product" name="id_prod" placeholder="Selecte Product">
                            <option value="">Select Product</option>
                            @foreach ($proo as $v_product)
                            <option value="{{ $v_product->id }}" {{ $v_product->id == request()->input('id_prod') ? 'selected' : '' }}> {{ $v_product->name }} / {{ $v_product->sku }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-12 m-b-20">
                        <select class="select2 form-control" name="shipping">
                            <option value="">Status Shipping</option>
                            <option value="unpacked" {{ 'unpacked' == request()->input('shipping') ? 'selected' : '' }}>
                                Unpacked</option>
                            <option value="picking process"
                                {{ 'picking process' == request()->input('shipping') ? 'selected' : '' }}>Picking Process
                            </option>
                            <option value="item packed"
                                {{ 'item packed' == request()->input('shipping') ? 'selected' : '' }}>Item Packed</option>
                            <option value="shipped" {{ 'shipped' == request()->input('shipping') ? 'selected' : '' }}>
                                Shipped</option>
                            <option value="in transit"
                                {{ 'in transit' == request()->input('shipping') ? 'selected' : '' }}>In Transit</option>
                            <option value="in delivery"
                                {{ 'in delivery' == request()->input('shipping') ? 'selected' : '' }}>In Delivery</option>
                            <option value="proseccing"
                                {{ 'proseccing' == request()->input('shipping') ? 'selected' : '' }}>Processing</option>
                            <option value="delivered" {{ 'delivered' == request()->input('shipping') ? 'selected' : '' }}>
                                Delivered</option>
                            <option value="incident" {{ 'incident' == request()->input('shipping') ? 'selected' : '' }}>
                                Incident</option>
                            <option value="rejected" {{ 'rejected' == request()->input('shipping') ? 'selected' : '' }}>
                                Rejected</option>
                            <option value="returned" {{ 'returned' == request()->input('shipping') ? 'selected' : '' }}>
                                Returned</option>
                        </select>
                    </div>
                    <div class="col-md-1 align-self-center">
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-primary waves-effect btn-rounded  " style="width:100%">Search</button>
                        </div>
                    </div>
                    <div class="col-md-1 align-self-center">
                        <div class="form-group mb-3">
                            <a href="{{ route('leads.index') }}" class="btn btn-primary waves-effect btn-rounded " style="width:100%">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </ul>

 
</div>

<!-- Mobile version (visible only on small to medium screens) -->
<div class="row mt-3 w-100 m-0 d-flex d-lg-none p-0">
    <ul class="nav nav-pills p-3 mb-3 rounded align-items-center card flex-row">
        <li class="nav-item mb-2">
            <a href="javascript:void(0)" onclick="toggleText()" class="nav-link gap-6 note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 fs-11 active" id="all-category">
              <i class="ti ti-list fill-white "></i>
              <span class="d-none d-md-block fw-medium">Filter</span>
            </a>
          </li>
        <div class="col-12 row form-group multi" id="multi" >
            <form >
                <div class="row">
                    <div class="col-md-3 col-sm-12 m-b-20 mb-lg-1 mb-3">
                        <input type="text" class="form-control" id="search_ref" name="ref" value="{{ request()->input('ref') }}" placeholder="Ref">
                    </div>
                    <div class="col-md-3 col-sm-12 m-b-20 mb-lg-1 mb-3">
                        <input type="text" class="form-control" name="customer" value="{{ request()->input('customer') }}" placeholder="Customer Name">
                    </div>
                    <div class="col-md-3 col-sm-12 m-b-20 mb-lg-1 mb-3">
                        <input type="text" class="form-control" name="phone1" value="{{ request()->input('phone1') }}" placeholder="Phone ">
                    </div>
                    <div class="col-md-3 col-sm-12 m-b-20 " >
                        <select class="select2 form-control"  name="confirmation">
                            <option value="">Status Confirmation</option>
                            <option value="new order" {{ 'new order' == request()->input('confirmation') ? 'selected' : '' }}>New Order</option>
                            <option value="confirmed" {{ 'confirmed' == request()->input('confirmation') ? 'selected' : '' }}>Confirmed</option>
                            <option value="no answer" {{ 'no answer' == request()->input('confirmation') ? 'selected' : '' }}>No answer</option>
                            <option value="no answer 2" {{ 'no answer 2' == request()->input('confirmation') ? 'selected' : '' }}">No answer 2</option>
                            <option value="no answer 3" {{ 'no answer 3' == request()->input('confirmation') ? 'selected' : '' }}>No answer 3</option>
                            <option value="no answer 4" {{ 'no answer 4' == request()->input('confirmation') ? 'selected' : '' }}>No answer 4</option>
                            <option value="no answer 5" {{ 'no answer 5' == request()->input('confirmation') ? 'selected' : '' }}>No answer 5</option>
                            <option value="no answer 6" {{ 'no answer 6' == request()->input('confirmation') ? 'selected' : '' }}>No answer 6</option>
                            <option value="no answer 7" {{ 'no answer 7' == request()->input('confirmation') ? 'selected' : '' }}>No answer 7</option>
                            <option value="no answer 8" {{ 'no answer 8' == request()->input('confirmation') ? 'selected' : '' }}>No answer 8</option>
                            <option value="no answer 9" {{ 'no answer 9' == request()->input('confirmation') ? 'selected' : '' }}>No answer 9</option>
                            <option value="call later" {{ 'call later' == request()->input('confirmation') ? 'selected' : '' }}>Call later</option>
                            <option value="canceled" {{ 'canceled' == request()->input('confirmation') ? 'selected' : '' }}>Canceled</option>
                            <option value="canceled by system" {{ 'canceled by system' == request()->input('confirmation') ? 'selected' : '' }}>Canceld By System</option>
                            <option value="outofstock" {{ 'outofstock' == request()->input('confirmation') ? 'selected' : '' }}>Out Of Stock</option>
                            <option value="wrong" {{ 'wrong' == request()->input('confirmation') ? 'selected' : '' }}>Wrong</option>
                            <option value="duplicated" {{ 'duplicated' == request()->input('confirmation') ? 'selected' : '' }}>Duplicated</option>
                            <option value="out of area" {{ 'out of area' == request()->input('confirmation') ? 'selected' : '' }}>out of area</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3 col-sm-12 m-b-20 ">
                    <div class="col-lg-3   col-sm-12 align-self-center">
                        <div class='theme-form mb-3'>
                            <input type="text" class="form-control flatpickr-input" name="date" value="{{ request()->input('date') }}" id="datepicker-range" readonly="readonly">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-12 m-b-20  mb-3">
                        <select class="select2 form-control" id="select_product" name="id_prod" placeholder="Selecte Product">
                            <option value="">Select Product</option>
                            @foreach ($proo as $v_product)
                            <option value="{{ $v_product->id }}" {{ $v_product->id == request()->input('id_prod') ? 'selected' : '' }}> {{ $v_product->name }} / {{ $v_product->sku }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 col-sm-3 mb-2 marg">
                        <div class="form-group d-flex  flex-lg-row flex-sm-row gap-2 gap-lg-4">
                            <button type="submit" class="btn1 btn btn-primary waves-effect btn-rounded">Search</button>
                            <a href="{{ route('leads.index') }}" class="btn btn1 btn-primary waves-effect btn-rounded">Reset</a>
                        </div>
                    </div>
                    
                   
                </div>
            </form>
        </div>
    </ul>
</div>

            <!-- ============================================================== -->
            {{-- <div class="row my-4">
                <div class="col-12">
                    <!-- Column -->
                    <div class="card">
                        <div class="card-body">

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
                                                    id="searchdetai">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row ross" style="padding:0px; margin:20px 0px; width:100%">
                <div class="col-12" style="padding:0px 0px 0px 0px; width:100% ;">
                    <!-- Column -->
                    <div class="card">
                        <div class="card-body" style="padding: 20px 0px 20px 0px;">                          
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
                            <div class="table-responsive border rounded-1" style="margin-top:-20px">
                                <table class="table text-nowrap customize-table mb-0 align-middle">
                                    <thead class="text-dark fs-4">
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
                                            <th>Livrison</th>
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
                                                    <span class="badge bg-secondary mb-2">{{ $v_lead['n_lead'] }}</span>  @if($v_lead['isrollback'])  <span class="badge bg-danger">rolled back</span>   @endif
                                                    {{-- <br>
                                                    @if (!empty($v_lead['leadproduct']))
                                                        <span
                                                            class="badge bg-info">+Upssel{{ $v_lead['leadproduct']->where('isupsell', '1')->count() }}</span>
                                                    @endif --}}
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
                                                            x-placement="bottom-start"
                                                            style="position: absolute; will-change: transform; transform: translate3d(0px, 35px, 0px);margin-left: -56px !important;">
                                                            <a class="dropdown-item seehystory" id="seehystory"
                                                                data-id="{{ $v_lead['id'] }}"><i class="ti ti-eye"></i>
                                                                History</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('leads.edit', $v_lead['id']) }}"><i
                                                                    class="ti ti-eye"></i> Details</a>
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
                                {{ $leads->withQueryString()->links('vendor.pagination.courier') }}
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->

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


    @section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    {{-- <script
        src="{{ asset('/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js') }}">
    </script> --}}

    <script type="text/javascript">
        // $("#mobile_customer").keyup(function() {
        //     $value = $(this).val();
        //     if ($value) {
        //         $('#whtsapp').attr("href", 'https://wa.me/' + $value);
        //         $('#call1').attr("href", 'tel:' + $value);
        //     }
        // });
        // $("#mobile2_customer").keyup(function() {
        //     $value = $(this).val();
        //     if ($value) {
        //         $('#wht2').attr("href", 'https://wa.me/' + $value);
        //         $('#call3').attr("href", 'tel:' + $value);
        //     }
        // });
        // $("#customer_phone").keyup(function() {
        //     $value = $(this).val();
        //     if ($value) {
        //         $('#sapp').attr("href", 'https://wa.me/' + $value);
        //         $('#ccall').attr("href", 'tel:' + $value);
        //     }
        // });
        // $("#search").keyup(function() {
        //     $value = $(this).val();
        //     if ($value) {
        //         $('.alldata').hide();
        //         $('.datasearch').show();
        //     } else {
        //         $('.alldata').show();
        //         $('.datasearch').hide();
        //     }
        //     $.ajax({
        //         type: 'get',
        //         url: '{{ route('leads.search') }}',
        //         data: {
        //             'search': $value,
        //         },
        //         success: function(data) {
        //             $('#contentdata').html(data);
        //         }
        //     });
        // });
        // $(document).ready(function() {

        //     $(function(e) {
        //         $('#savelead').click(function(e) {
        //             var idproduct = $('#id_product').val();
        //             var namecustomer = $('#name_customer').val();
        //             var quantity = $('#quantity').val();
        //             var mobile = $('#mobile').val();
        //             var mobile2 = $('#mobile2').val();
        //             var cityid = $('#id_city').val();
        //             var zoneid = $('#id_zone').val();
        //             var address = $('#address').val();
        //             var total = $('#total').val();
        //             $.ajax({
        //                 type: 'POST',
        //                 url: '{{ route('leads.store') }}',
        //                 cache: false,
        //                 data: {
        //                     id: idproduct,
        //                     namecustomer: namecustomer,
        //                     quantity: quantity,
        //                     mobile: mobile,
        //                     mobile2: mobile2,
        //                     cityid: cityid,
        //                     zoneid: zoneid,
        //                     address: address,
        //                     total: total,
        //                     _token: '{{ csrf_token() }}'
        //                 },
        //                 success: function(response) {
        //                     if (response.success == true) {
        //                         toastr.success('Good Job.',
        //                             'Upsell Has been Addess Success!', {
        //                                 "showMethod": "slideDown",
        //                                 "hideMethod": "slideUp",
        //                                 timeOut: 2000
        //                             });
        //                     }
        //                     location.reload();
        //                 }
        //             });
        //         });
        //     });
        //     Department Change
        //     $('#id_cit').change(function() {

        //         // Department id
        //         var id = $(this).val();

        //         // Empty the dropdown
        //         $('#id_zone').find('option').not(':first').remove();
        //         //;
        //         // AJAX request 
        //         $.ajax({
        //             url: 'zone/' + id,
        //             type: 'get',
        //             dataType: 'json',
        //             success: function(response) {

        //                 var len = 0;
        //                 if (response['data'] != null) {
        //                     len = response['data'].length;
        //                 }

        //                 if (len > 0) {
        //                     // Read data and create <option >
        //                     for (var i = 0; i < len; i++) {

        //                         var id = response['data'][i].id;
        //                         var name = response['data'][i].name;

        //                         var option = "<option value='" + id + "'>" + name + "</option>";

        //                         $("#id_zone").append(option);
        //                     }
        //                 }

        //             }
        //         });
        //     });
        //     // Department Change
        //     $('#id_cityy').change(function() {

        //         // Department id
        //         var id = $(this).val();

        //         // Empty the dropdown
        //         $('#id_zonee').find('option').not(':first').remove();
        //         //;
        //         // AJAX request 
        //         $.ajax({
        //             url: 'zone/' + id,
        //             type: 'get',
        //             dataType: 'json',
        //             success: function(response) {

        //                 var len = 0;
        //                 if (response['data'] != null) {
        //                     len = response['data'].length;
        //                 }

        //                 if (len > 0) {
        //                     // Read data and create <option >
        //                     for (var i = 0; i < len; i++) {

        //                         var id = response['data'][i].id;
        //                         var name = response['data'][i].name;

        //                         var option = "<option value='" + id + "'>" + name + "</option>";

        //                         $("#id_zonee").append(option);
        //                     }
        //                 }

        //             }
        //         });
        //     });
        //     // Department Change
        //     $('#id_city').change(function() {

        //         // Department id
        //         var id = $(this).val();

        //         // Empty the dropdown
        //         $('#id_zone').find('option').not(':first').remove();
        //         //;
        //         // AJAX request 
        //         $.ajax({
        //             url: 'zone/' + id,
        //             type: 'get',
        //             dataType: 'json',
        //             success: function(response) {

        //                 var len = 0;
        //                 if (response['data'] != null) {
        //                     len = response['data'].length;
        //                 }

        //                 if (len > 0) {
        //                     // Read data and create <option >
        //                     for (var i = 0; i < len; i++) {

        //                         var id = response['data'][i].id;
        //                         var name = response['data'][i].name;

        //                         var option = "<option value='" + id + "'>" + name + "</option>";

        //                         $("#id_zone").append(option);
        //                     }
        //                 }

        //             }
        //         });
        //     });
        //     $('body').on('change', '.myform', function(e) {
        //         e.preventDefault();
        //         var id = $(this).data('id');
        //         var statuu = '#statu_con' + id;
        //         var status = $(statuu).val();
        //         if (status == "confirmed") {
        //             $('#leads_ids').val(id);
        //             $('#searchdetails').modal('show');
        //             $('#statusconfirmed').modal('show');
        //         } else {
        //             $('#leads_id').val(id);
        //             $('#autherstatus').modal('show');
        //         }

        //         //;
        //         $.ajax({
        //             type: "POST",
        //             url: '{{ route('leads.statuscon') }}',
        //             cache: false,
        //             data: {
        //                 id: id,
        //                 status: status,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function(response) {
        //                 if (response.success == true) {
        //                     toastr.success('Good Job.', 'Lead Has been Update Success!', {
        //                         "showMethod": "slideDown",
        //                         "hideMethod": "slideUp",
        //                         timeOut: 2000
        //                     });
        //                 }
        //             }
        //         });
        //     });
        // });

        // $(function(e) {
        //     $('#confirmed').click(function(e) {
        //         $id = $('#lead_id').val();
        //         $('#leads_id').val($id);
        //         $('#searchdetails').modal('show');
        //     });
        // });

        // $(function(e) {
        //     $('#callater').click(function(e) {
        //         //console.log(namecustomer);
        //         $('#callaterpopup').modal('show');
        //     });
        // });

        // $(function(e) {
        //     $('#cancel').click(function(e) {
        //         //console.log(namecustomer);
        //         $('#canceledform').modal('show');
        //     });
        // });

        // $(function(e) {
        //     $('.addreclamationgetid').click(function(e) {
        //         //console.log(namecustomer);
        //         $('#addreclamation').modal('show');
        //         $('#lead_id_recla').val($(this).data('id'));
        //     });
        // });

        // $(function(e) {
        //     $('body').on('click', '.addreclamationgetid2', function(e) {
        //         //console.log(namecustomer);
        //         $('#addreclamation').modal('show');
        //         $('#lead_id_recla').val($(this).data('id'));
        //     });
        // });

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

        $(function() {
            $('body').on('click', '.detaillead', function(products) {
                var id = $(this).data('id');
                $.get("{{ route('leads.index') }}" + '/' + id + '/details', function(data) {
                    $('#editsheet').modal('show');
                    $('#lead_id').val(data[0].leads[0].id);
                    $('#statusleadss').html(data[0].leads[0].status_confirmation);
                    $('#n_order').html(data[0].leads[0].n_lead);
                    $('#customer_name').val(data[0].leads[0].name);
                    $('#mobile_customer').val(data[0].leads[0].phone);
                    $('#mobile2_customer').val(data[0].leads[0].phone2);
                    $('#customer_address').val(data[0].leads[0].address);
                    $('#customers_note').val(data[0].leads[0].note);
                    $('#link_products').val(data[0].product[0].link);
                    $('#product').val(data[0].product[0].name);
                    $('#lead_value').val(data[0].leads[0].lead_value);
                    $('#product_image').html("<img src='" + data[0].product[0].image +
                        "' width='80px' />");
                    $('#link_video').val(data[0].product[0].link_video);
                    $('#customer_note').val(data[0].leads[0].note);
                    $('#id_cityy').html(
                        "<option value=' '>Select City</option>@foreach ($cities as $v_city)<option value='{{ $v_city->id }}' {{ $v_city->id == '"+ data[0].leads[0].id_city +"' ? 'selected' : '' }}>{{ $v_city->name }}</option>@endforeach"
                        );
                    $('#description_product').val(data[0].product[0].description);
                    $('#seedetailupsell').val(data[0].leads[0].id);
                    $('#call').html(
                        "<a class='btn btn-success waves-effect' id='whtsapp' href='https://wa.me/" +
                        data[0].leads[0].phone +
                        "' target='_blank'><i class='mdi mdi-whatsapp'></i>Whtsapp</a><a class='btn btn-success waves-effect' id='call1' href='tel:" +
                        data[0].leads[0].phone + "'><i class='mdi mdi-call-made'></i>Call</a>");
                    $('#call2').html(
                        "<a class='btn btn-success waves-effect' id='wht2' href='https://wa.me/" +
                        data[0].leads[0].phone2 +
                        "' target='_blank'><i class='mdi mdi-whatsapp'></i>Whtsapp</a><a class='btn btn-success waves-effect' id='call3' href='tel:" +
                        data[0].leads[0].phone2 + "'><i class='mdi mdi-call-made'></i>Call</a>");
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
        });

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
    {{-- <script>
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
    </script> --}}

    <script>
        document.getElementById('pagination').onchange = function() {
            window.location = window.location.href + "?&items=" + this.value;

        };
    </script>
    @endsection
@endsection
