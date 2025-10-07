@extends('backend.layouts.app')
@section('content')
<style>
    .hiddenRow {
        padding: 0 !important;
        }
   .select2 {
       width: 100% !important;
   }
</style>
@if(Auth::user()->id_role != "3")
<style>
    .multi{
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
                @if(Auth::user()->id_role != "3")
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
                <div class="row">
                    <div class="col-12">
                        <!-- Column -->
                        <div class="card">
                            <div class="card-body">
                                @if(Auth::user()->id_role != "3")
                                <div class="form-group mb-0">
                                    <form>
                                    <div class="row">                                        
                                        <div class="col-md-11 col-sm-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="search" id="search" placeholder="Ref , Name Customer , Phone , Price" aria-label="" aria-describedby="basic-addon1">
                                            </div>
                                        </div>                                        
                                        <div class="col-md-1 col-sm-12">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" onclick="toggleText()">Multi</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                                <div class="form-group multi" id="multi" >
                                    <form>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12 my-4">
                                            <input type="text" class="form-control" id="search_ref" name="ref" placeholder="Ref">
                                        </div>
                                        <div class="col-md-3 col-sm-12 my-4">
                                            <input type="text" class="form-control" name="customer" placeholder="Customer Name">
                                        </div>
                                        <div class="col-md-3 col-sm-12 my-4">
                                            <input type="text" class="form-control" name="phone1" placeholder="Phone ">
                                        </div>
                                        <div class="col-md-3 col-sm-12 my-4">
                                            <select class="form-control" id="id_cit" name="city" >
                                                <option value="">Select City</option>
                                                @foreach($cities as $v_city)
                                                <option value="{{ $v_city->id}}">{{ $v_city->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12 my-4">
                                            <label>Status Confirmation</label>
                                            <select class="select2 form-control" name="confirmation[]" multiple="multiple" style="width: 100%;height: 36px;">
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
                                                <input type='text' name="date" class="form-control timeseconds" id="timeseconds"/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="ti-calendar"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 my-4">
                                            <label>Select Product</label>
                                            <select class="form-control" name="id_prod" style="width: 100%;height: 36px;">
                                                <option value="">Select Product</option>
                                                @foreach($productss as $v_product)
                                                @foreach($v_product['products'] as $v_pro)
                                                <option value="{{ $v_pro->id}}">{{ $v_pro->name}}</option>
                                                @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-1 align-self-center">
                                            <div class="form-group mb-0">
                                                <button type="submit" class="btn btn-primary waves-effect btn-rounded m-t-10 mb-2 " style="width:100%">Search</button>
                                            </div>
                                        </div>
                                        <div class="col-1 align-self-center">
                                            <div class="form-group mb-0">
                                                <a href="{{ route('leads.index')}}" class="btn btn-primary waves-effect btn-rounded m-t-10 mb-2 " style="width:100%">Reset</a>
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
                                                <input type="text" class="form-control" name="search" id="search_2" placeholder="Ref , Name Customer , Phone , Price" aria-label="" aria-describedby="basic-addon1">
                                            </div>
                                        </div>                                        
                                        <div class="col-sm-12 col-md-12">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary col-md-12" type="button" id="searchdetai">Search</button>
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
                @if(!empty($lead))
                <div class="row">
                    <div class="col-12">
                        <!-- Column -->
                        <div class="card">
                            <div class="card-body">
                               
                                <!-- Add Contact Popup Model -->        
                                <div id="StatusLeads" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">History</h4> 
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal form-material">
                                                    <div class="table-responsive">
                                                        <table id="demo-foo-addrow" class="table m-t-30 table-hover contact-list" data-paging="true" data-paging-size="7">
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
                                @if(!empty($lead->n_lead))
                                <div class="row">
                                    <div class="col-12 align-self-center">
                                        <div class="form-group mb-2 text-center">
                                            <button class="btn btn-primary btn-rounded m-b-10 " data-bs-toggle="modal" data-bs-target="#add-new-lead">Create New Order</button>
                                            <button class="btn btn-primary btn-rounded m-b-10 addreclamationgetid" data-id="{{ $lead->id}}" id="" title="Complaint">Complaint</button>
                                            <a class="btn btn-primary btn-rounded m-b-10" href="{{ route('leads.refresh', $lead->id)}}">Refresh Data</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card" style="box-shadow: 0px 0px 8px 3px;">
                                            <div class="card-body"><p><!--{{ $lead->status_confirmation}}</p>-->
                                                <h4 class="card-title"style="font-size: 25px;">
                                                    <i class="mdi mdi-menu font-50" style="margin-right: 30px;"></i>Detail Product -  <span>N Command : {{ $lead->n_lead}}</span><span>Status Confirmation : <span @if( $lead->status_confirmation == 'call later') style='color:#fb8c00' @endif>{{ $lead->status_confirmation}}</span></span>
                                                </h4>
                                                <form class="form pt-3"style="">
                                                    <div class="row col-12">
                                                        <div class="form-group col-lg-6 col-md-12">
                                                            <label>Product Name :</label>
                                                            <select class="form-control" name="product" id="first_product" style="width: 100%;height: 36px;">
                                                                <option value="">Select Product</option>
                                                                @foreach($productseller as $v_pro)
                                                                <option value="{{ $v_pro->id}}" {{ $v_pro->id == $detailpro->id ? 'selected':'' }}>{{ $v_pro->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group m-r-2  col-lg-3 col-md-12">
                                                            <label>Quantity :</label>
                                                            <input type="text" class="form-control" id="lead_quantity" name="quantity" @if( $lead->quantity != Null) value="{{ $lead['leadpro']->quantity}}" @else value="1" @endif />
                                                        </div>
                                                        <div class="form-group m-r-2  col-lg-3 col-md-12">
                                                            <label>Product Price :</label>
                                                            <input type="number" class="form-control" id="lead_values" name="price" value="{{ $lead['leadpro']->lead_value}}" />
                                                        </div>
                                                    </div>
                                                    <div class="row col-12">
                                                        <div class="form-group m-r-2  col-lg-3 col-md-12">
                                                            <label>Quantity Total :</label>
                                                            <input type="text" class="form-control" id="totl_lead_quantity" name="quantity" disabled @if( $lead->quantity != Null) value="{{ $lead->quantity}}" @else value="1" @endif />
                                                        </div>
                                                        <div class="form-group m-r-2  col-lg-3 col-md-12">
                                                            <label>Total Price :</label>
                                                            <input type="text" class="form-control" id="total_lead_values" name="price" value="{{ $lead->lead_value}}" disabled/>
                                                        </div>
                                                        <div class="form-group m-r-2  col-lg-3 col-md-12">
                                                            <a class="btn btn-primary btn-rounded m-t-20 mb-2 text-white" data-id="{{ $lead->id}}" id="updateprice">Update</a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-lg-6 col-md-12">
                                                            <label>Link Product :</label>
                                                            <div class="d-flex">
                                                                <input type="text" class="form-control w-70"
                                                                    style="width:70%"
                                                                    value="{{ $detailpro->link }}" />
                                                                <a class="btn btn-dark waves-effect mx-1"
                                                                    href="{{ $detailpro->link }}" target="_blank"><i
                                                                        class="fa fa-link"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-lg-6 col-md-12">
                                                            <label>Link Video :</label>
                                                            <div class="d-flex">
                                                                <input type="text" class="form-control"
                                                                    style="width:70%"
                                                                    value="{{ $detailpro->link_video }}" />
                                                                <a class="btn btn-dark waves-effect mx-1"
                                                                    href="{{ $detailpro->link_video }}"
                                                                    target="_blank"><i class="fa fa-link"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-lg-6 col-md-12">
                                                            <label>Description :</label>
                                                            <textarea class="form-control" >{{ $detailpro->description}}</textarea>
                                                        </div>
                                                        <div class="form-group col-lg-6 col-md-12">
                                                            <img src="{{ $detailpro->image}}" style="width: 120px;"/>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-12 column">
                                                            <table class="table table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Quantity</th>
                                                                        <th class="text-center">Discount</th>
                                                                        <th class="text-center">Note</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="infoupsells">
                                                                    @foreach($detailupsell as $v_detailupsell)
                                                                    <tr>
                                                                        <td>{{ $v_detailupsell->quantity}}</td>
                                                                        <td>{{ $v_detailupsell->discount}}</td>
                                                                        <td>{{ $v_detailupsell->note}}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-lg-12 align-self-center">
                                                            <div class="form-group mb-0 text-center">
                                                                <input type="hidden" id="lead_id" value="{{$lead->id}}" />
                                                                <!--<button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 upsell" data-id="{{ $lead->id}}">Add Upsell</button>-->
                                                                <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 testupsell" data-id="{{ $lead->id}}">Add Upsell</button>
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
                                                    <div class=" col-lg-8 col-md-12">
                                                        <h4 class="card-title"style="font-size: 25px;">
                                                            <i class="mdi mdi-account-circle" style="margin-right: 30px;"></i>Customer Information 
                                                        </h4>
                                                    </div>
                                                    <div class="col-lg-4 col-md-12">
                                                        <a class="btn btn-success waves-effect mx-1" id="sapp"
                                                        href="https://wa.me/{{ $lead->phone }}?text=hi"
                                                        target="_blank"><i
                                                            class="mdi mdi-whatsapp"></i>Whtsapp</a>
                                                    <a class="btn btn-success waves-effect" id="ccall"
                                                        href="tel:{{ $lead->phone }}"><i
                                                            class="mdi mdi-call-made"></i>Call</a>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <form class="form pt-3"style="">
                                                    <div class="row">
                                                        <div class="form-group col-lg-6 col-md-12">
                                                            <label>Customer Name :</label>
                                                            <input type="text" class="form-control" id="customers_name" name="product" value="{{ $lead->name}}"/>
                                                        </div>
                                                        <div class="form-group col-lg-6 col-md-12">
                                                            <label>Phone 1 :</label>
                                                            <input type="text" class="form-control" id="customers_phone" value="{{ $lead->phone}}" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-lg-6 col-md-12">
                                                            <label>Phone 2 :</label>
                                                            <input type="text" class="form-control" id="customers_phone2" value="{{ $lead->phone2}}" />
                                                        <a class="btn btn-success waves-effect" id="wht2" href="https://wa.me/{{ $lead->phone}}?text=hi" target="_blank"><i class="mdi mdi-whatsapp"></i>Whtsapp</a>
                                                        <a class="btn btn-success waves-effect" id="call3" href="tel:{{ $lead->phone2}}"><i class="mdi mdi-call-made"></i>Call</a>
                                                        </div>
                                                        <div class="form-group col-lg-6 col-md-12">
                                                            <label>Address :</label>
                                                            <textarea class="form-control" id="customers_address">{{ $lead->address}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 my-4">
                                                            <label>City :</label>
                                                            <select class="select2 form-control" id="id_cityy">
                                                                <option value=" ">Select City</option>
                                                                @foreach($cities as $v_city)
                                                                <option value="{{ $v_city->id}}" {{ $v_city->id == $lead->id_city ? 'selected':'' }}>{{ $v_city->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Zone :</label>
                                                            <select class="select2 form-control" id="id_zonee">
                                                                <option>Select Zone</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-12">
                                                            <label>Note :</label>
                                                            <textarea class="form-control" id="customer_note">{{ $lead->note}}</textarea>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <div class="col-md-12 column">
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Product Name</th>
                                                                <th class="text-center">Quantity</th>
                                                                <th class="text-center">Price</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($leadproduct as $v_leadproduct)
                                                            <tr>
                                                                <td class="text-center">
                                                                    @foreach($v_leadproduct['product'] as $v_pro)
                                                                    {{ $v_pro->name}}
                                                                    @endforeach
                                                                </td>
                                                                <td class="text-center">{{ $v_leadproduct->quantity}}</td>
                                                                <td class="text-center">{{ $v_leadproduct->lead_value}}</td>
                                                                <td class="text-center">
                                                                    <a class="dropdown-item" href="{{ route('leads.deleteupsell' , $v_leadproduct->id)}}" ><i class="ti ti-trash"></i> Deleted</a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(Auth::user()->id_role == "3")
                                    @if($lead->status_livrison == "unpacked")
                                    <div class="row">
                                        <div class="col-12 align-self-center">
                                            <div class="form-group mb-0 text-center">
                                                <input type="hidden" id="lead_id" value="{{$lead->id}}" />
                                                <a class="btn btn-success btn-rounded m-t-10 mb-2" _blank href="{{ route('relancements.index', $lead->id)}}">Relancement</a>
                                                <button class="btn btn-success btn-rounded m-t-10 mb-2 confiremds" id="confirmeds">Confirmed</button>
                                                <!-- <button type="button" class="btn btn-orange btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id}}" id="unrechs">no answer</button> -->
                                                <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id}}" id="callaters">CALL LATER</button>
                                                <button type="button" class="btn btn-danger btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id}}" id="cancels">CANCEL</button>
                                                <button type="button" class="btn btn-danger btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id}}" id="wrong">WRONG</button>
                                                <a type="button" class="btn btn-danger btn-rounded m-t-10 mb-2 " href="{{ route('leads.duplicated' , $lead->id)}}" id="duplicated">DUPLICATED</a>
                                                <button type="button" class="btn btn-orange btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id}}" id="unrechstest">no answer</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @elseif(Auth::user()->id_role == "4")
                                    <div class="row">
                                        <div class="col-12 align-self-center">
                                            <div class="form-group mb-0 text-center">
                                                <input type="hidden" id="lead_id" value="{{$lead->id}}" />
                                                <a class="btn btn-success btn-rounded m-t-10 mb-2" _blank href="{{ route('relancements.index', $lead->id)}}">Relancement</a>
                                                <button class="btn btn-success btn-rounded m-t-10 mb-2 confiremds" id="confirmeds">Confirmed</button>
                                                <!-- <button type="button" class="btn btn-orange btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id}}" id="unrechs">no answer</button> -->
                                                <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id}}" id="callaters">CALL LATER</button>
                                                <button type="button" class="btn btn-danger btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id}}" id="cancels">CANCEL</button>
                                                <button type="button" class="btn btn-danger btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id}}" id="wrong">WRONG</button>
                                                <a type="button" class="btn btn-danger btn-rounded m-t-10 mb-2 " href="{{ route('leads.duplicated' , $lead->id)}}" id="duplicated">DUPLICATED</a>
                                                <button type="button" class="btn btn-orange btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id}}" id="unrechstest">no answer</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div id="searchdetail" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" >
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
                                                                            <input type="hidden" class="form-control" id="leadss_id" value="{{ $lead->id}}">
                                                                            <input type="date" class="form-control" id="date_delivredss" placeholder="">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <textarea class="form-control" id="comment_stas" ></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect editlead" id="datedelivred">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <div id="callaterpopup" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" >
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
                                                                            <input type="hidden" class="form-control" id="leads_id" value="{{ $lead->id}}">
                                                                            <input type="date" class="form-control pickatime-format-label" id="date_call" placeholder="">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <input type="time" class="form-control pickatime-format-label" id="time_call" placeholder="">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <textarea class="form-control" id="comment_call" ></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect editlead" id="datecall">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div> 
                                <div id="canceledforms" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" >
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
                                                                            <input type="hidden" class="form-control" id="leads_sid" value="{{ $lead->id}}">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <textarea class="form-control" id="comment_stas_cans" ></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect editlead" id="notecanceleds">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div> 
                                <div id="wrongforms" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" >
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
                                                                            <input type="hidden" class="form-control" id="leads_sid_wrong" value="{{ $lead->id}}">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <textarea class="form-control" id="comment_stas_wrong" ></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect editlead" id="notewrong">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div> 
                                <div id="add-new-lead" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" style="max-width: 720px;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">New Lead</h4> 
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form class="form-horizontal form-material">
                                            <div class="modal-body">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-12 col-sm-12 my-4">
                                                                <select class="form-control custom-select" style="width: 100%; height:36px;" id="id_product">
                                                                    <option>Select Product</option>
                                                                    @foreach($proo as $v_product)
                                                                    <option value="{{ $v_product->id }}">{{ $v_product->name}} / {{ $v_product->price}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 col-sm-12 my-4">
                                                                <input type="hidden" class="form-control" id="lead_id" placeholder="Name Customer">
                                                                <input type="text" class="form-control" id="name_customer" placeholder="Name Customer">
                                                            </div>
                                                            <div class="col-md-4 col-sm-12 my-4">
                                                                <input type="text" class="form-control" id="mobile" placeholder="Mobile">
                                                            </div>
                                                            <div class="col-md-4 col-sm-12 my-4">
                                                                <input type="text" class="form-control" id="mobile2" placeholder="Mobile 2">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12 my-4">
                                                                <select class="select2 form-control custom-select" id="id_city">
                                                                    <option>Select City</option>
                                                                    @foreach($cities as $v_city)
                                                                    <option value="{{ $v_city->id}}">{{ $v_city->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12 my-4">
                                                                <select class="select2 form-control custom-select" id="id_zone">
                                                                    <option>Select Zone</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12 col-sm-12 my-4">
                                                            <textarea type="text" class="form-control" id="address" placeholder="Address"></textarea>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4 col-sm-12 my-4">
                                                                <input type="number" class="form-control" id="quantity" placeholder="Quantity">
                                                            </div>
                                                            <div class="col-md-4 col-sm-12 my-4">
                                                                <input type="numbre" class="form-control" id="total" placeholder="Total Price">
                                                            </div>
                                                        </div>
                                                        <!-- <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="statu_confirmation[]" checked>
                                                            <label class="form-check-label" for="statu_confirmation">
                                                                Confirm
                                                            </label>
                                                            </div>
                                                            <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="statu_confirmation[]"  >
                                                            <label class="form-check-label" for="statu_confirmation">
                                                                Expedition
                                                            </label>
                                                            </div>
                                                        
                                                        </div> -->
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary waves-effect" id="savelead">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                            </form>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                @else
                                <div class="row">
                                    <div class="col-12 align-self-center">
                                        <div class="form-group mb-2 text-center">
                                            <button class="btn btn-primary btn-rounded m-b-10 " data-bs-toggle="modal" data-bs-target="#add-new-lead">Create New Order</button>
                                        </div>
                                    </div>
                                </div>
                                <h3>No Leads</h3>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <h1 class="text-center">No Lead Available at this moment</h1>
                @endif
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
                                <div id="listlead" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                                <div class="table-responsive">
                                                                    <table id=""  class="table table-bordered table-striped table-hover contact-list" data-paging="true" data-paging-size="7">
                                                                    <thead >
                                                                        <tr>
                                                                            {{-- <th>
                                                                                <div class="custom-control custom-checkbox">
                                                                                    <input type="checkbox" class="selectall custom-control-input" id="chkCheckAll" required>
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
                                                                            <th>Delivery Man</th>
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
                                <!-- Add detail order Popup Model -->        
                                <div id="editsheet" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" style="max-width:1500px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Details Lead : <span id="statusleadss"></sapn></h4> 
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                                <from class="form-horizontal form-material">
                                            <div class="modal-body">
                                                    <div class="col-lg-12">
                                                        <div class="">
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="card" style="box-shadow: 0px 0px 8px 3px;">
                                                                        <div class="card-body"><p>
                                                                            <h4 class="card-title"style="font-size: 25px;">
                                                                                <i class="mdi mdi-menu font-50" style="margin-right: 30px;"></i>Detail Product -  <span id="n_order">N Command : </span>
                                                                            </h4>
                                                                            <form class="form pt-3">
                                                                                <div class="row col-12">
                                                                                    <div class="form-group col-lg-6 col-md-12" id="productLeads">
                                                                                        <label>Product Name :</label>
                                                                                        <!--<input type="text" class="form-control" id="product" name="product" value=""/>-->
                                                                                    </div>
                                                                                    <div class="form-group m-r-2 col-lg-3 col-md-12">
                                                                                        <label>Quantity :</label>
                                                                                        <input type="text" class="form-control" id="lead_quantits" name="lead_quantits" value="" />
                                                                                    </div>
                                                                                    <div class="form-group m-r-2 col-lg-3 col-md-12">
                                                                                        <label>Total Price :</label>
                                                                                        <input type="text" class="form-control" id="lead_value" name="price" value="" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="form-group col-lg-6 col-md-12">
                                                                                        <label>Link Product :</label>
                                                                                        <input type="text" class="form-control" id="link_products" value=""/>
                                                                                    </div>
                                                                                    <div class="form-group col-lg-6 col-md-12">
                                                                                        <label>Link Video :</label>
                                                                                        <input type="text" class="form-control" id="link_video"/>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="form-group col-lg-6 col-md-12">
                                                                                        <label>Description :</label>
                                                                                        <textarea class="form-control" id="description_product" style="height: 136px; max-height: 103px;"></textarea>
                                                                                    </div>
                                                                                    <div class="form-group col-lg-6 col-md-12" id="product_image">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="row">
                                                                                    <div class="col-12 align-self-center">
                                                                                        <div class="form-group mb-0 text-center">
                                                                                            <input type="hidden" id="lead_id" />
                                                                                            <!--<button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 upsell">Add Upsell</button>-->
                                                                                            <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 testupsells">Add Upsell</button>
                                                                                            <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 infoupsell">Information Upsell</button>
                                                                                            <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 upselllist">Upsell List</button>
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
                                                                                        <i class="mdi mdi-account-circle" style="margin-right: 30px;"></i>Customer Information 
                                                                                    </h4>
                                                                                </div>
                                                                                <div class="col-4" id="call">
                                                                                    <a class="btn btn-success waves-effect" id="whtsapp" href="https://wa.me/" target="_blank"><i class="mdi mdi-whatsapp"></i>Whtsapp</a>
                                                                                    <a class="btn btn-success waves-effect" id="call1" href="tel:"><i class="mdi mdi-call-made"></i>Call</a>
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            
                                                                            <form class="form pt-3">
                                                                                <div class="row">
                                                                                    <div class="form-group col-lg-6 col-md-12">
                                                                                        <label>Customer Name :</label>
                                                                                        <input type="text" class="form-control" id="customer_name" name="product" value=""/>
                                                                                    </div>
                                                                                    <div class="form-group col-lg-6 col-md-12">
                                                                                        <label>Phone 1 :</label>
                                                                                        <input type="text" class="form-control" id="mobile_customer" value="" />
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="form-group col-lg-6 col-md-12">
                                                                                        <label>Phone 2 :</label>
                                                                                        <input type="text" class="form-control" id="mobile2_customer" value="" />
                                                                                        <div id="call2">
                                                                                            <a class="btn btn-success waves-effect" id="wht2" href="https://wa.me/" target="_blank"><i class="mdi mdi-whatsapp"></i>Whtsapp</a>
                                                                                            <a class="btn btn-success waves-effect" id="call3" href="tel:"><i class="mdi mdi-call-made"></i>Call</a>
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                    <div class="form-group col-lg-6 col-md-12">
                                                                                        <label>Address :</label>
                                                                                        <textarea class="form-control" id="customer_address"></textarea>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-md-6 my-4">
                                                                                        <label>City :</label>
                                                                                        <select class="form-control" id="id_cityys">
                                                                                            <option value=" ">Select City</option>
                                                                                            
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label>Zone :</label>
                                                                                        <select class="form-control" id="id_zonees">
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
                                                                        <button class="btn btn-success btn-rounded m-t-10 mb-2 confiremd" id="confirmed">Confirmed</button>
                                                                        <button type="button" class="btn btn-orange btn-rounded m-t-10 mb-2 "  id="unrech">no answer</button>
                                                                        <button type="button" class="btn btn-primary btn-rounded m-t-10 mb-2 " id="callater">CALL LATER</button>
                                                                        <button type="button" class="btn btn-danger btn-rounded m-t-10 mb-2 " id="cancel">CANCEL</button>
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
                                
                                <div id="Upselliste" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel"> Upsell List</h4> 
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <from class="form-horizontal form-material">
                                                <div class="row">
                                                        <div class="col-md-12 column">
                                                            <table class="table table-bordered table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">Name</th>
                                                                        <th class="text-center">Quantity</th>
                                                                        <th class="text-center">Price</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="infoupsellss">
                                                                   
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </from>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <div id="info-upssel" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                                            <table class="table table-bordered table-hover">
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
                                                                                <tbody id="upsellsinfo">
                                                                                    
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>  
                                <!-- test multi upsells -->
                                <div id="multiupsells" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content" style="min-width:800px !important">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Add Upsell</h4> 
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                                <from class="form-horizontal form-material">
                                            <div class="modal-body">
                                                <input type="hidden" id="lead_upsells" class="lead_upsells" />
                                                <div class="col-md-12 table-responsive">
                                                    <table class="table table-bordered table-hover table-sortable" id="tab_logics">
                                                        <thead>
                                                            <tr >
                                                                <th class="text-center">
                                                                    Product
                                                                </th>
                                                                <th class="text-center">
                                                                    Quantity
                                                                </th>
                                                                <th class="text-center">
                                                                    Price
                                                                </th>
                                                                <th class="text-center">
                                                                <a id="add_rows" class="btn btn-primary float-right text-white"style="width: 83px;">Add Row</a>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr id='addrs0' data-id="0" class="hidden">
                                                                <td data-name="name">
                                                                    <select id="product_upsellss" class="form-control product_upsellss" name="product_upsellss">
                                                                        <option value="">Select Option</option>
                                                                    </select>
                                                                </td>
                                                                <td data-name="mail">
                                                                    <input type="number" name="upsells_quantity" id="upsells_quantity" class="form-control upsells_quantity" placeholder='quantity'/>
                                                                </td>
                                                                <td data-name="desc">
                                                                    <input type="number" name="price_upsells" placeholder="price" id="price_upsells" class="form-control price_upsells" />
                                                                </td>
                                                                <td data-name="del">
                                                                    <button name="del0" class='btn btn-danger glyphicon glyphicon-remove row-removes'><span aria-hidden="true">×</span></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <button type="submit" id="saveupsells" class="btn btn-primary float-right text-white">Save</button>
                                                </div>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                @if(!empty($lead))
                                <!-- test multi upsell -->
                                <div id="multiupsell" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content" style="min-width:800px !important">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Add Upsell</h4> 
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                                <from class="form-horizontal form-material">
                                            <div class="modal-body">
                                                <input type="hidden" id="lead_upsell" class="lead_upsell" />
                                                <div class="col-md-12 table-responsive">
                                                    <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                                                        <thead>
                                                            <tr >
                                                                <th class="text-center">
                                                                    Product
                                                                </th>
                                                                <th class="text-center">
                                                                    Quantity
                                                                </th>
                                                                <th class="text-center">
                                                                    Price
                                                                </th>
                                                                <th class="text-center">
                                                                <a id="add_row" class="btn btn-primary float-right text-white" style="width: 83px;">Add Row</a>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr id='addr0' data-id="0" class="hidden">
                                                                <td data-name="name">
                                                                    <select id="product_upsell" class="form-control product_upsell" name="product_upsell">
                                                                        <option value="">Select Option</option>
                                                                    </select>
                                                                </td>
                                                                <td data-name="mail">
                                                                    <input type="number" name="upsell_quantity" id="upsell_quantity" class="form-control upsell_quantity" placeholder='quantity'/>
                                                                </td>
                                                                <td data-name="desc">
                                                                    <input type="number" name="price_upsell" placeholder="price" id="price_upsell" class="form-control price_upsell" />
                                                                </td>
                                                                <td data-name="del">
                                                                    <button name="del0" class='btn btn-danger glyphicon glyphicon-remove row-remove'><span aria-hidden="true">×</span></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <button type="submit" id="saveupsell" class="btn btn-primary float-right text-white my-2">Save</button>
                                                </div>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>    
                                <div id="datedeli" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" >
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
                                                                            <input type="date" class="form-control" id="date_delivred" placeholder="">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <textarea class="form-control" id="comment_sta" ></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect editlead" id="datedelivre">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div> 
                                <!-- Add Contact Popup Model -->
                                <div id="autherstatus" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" >
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
                                                                            <input type="date" class="form-control" id="date_status" placeholder="">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <textarea class="form-control" id="coment_sta" ></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect editlead" id="changestatus">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- Add Contact Popup Model -->        
                                <div id="addreclamation" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                                            <input type="hidden" class="form-control" id="lead_id_recla"  placeholder="N Lead">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <select class="form-control" id="id_service" name="id_service" >
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
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <div id="searchdetails" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" >
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
                                                                            <input type="hidden" class="form-control" id="leadsss_id" value="{{ $lead->id}}">
                                                                            <input type="date" class="form-control" id="date_delivredsss" placeholder="">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <textarea class="form-control" id="comment_stasss" ></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect editlead" id="datedelivreds">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <div id="callaterpopups" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" >
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
                                                                            <input type="hidden" class="form-control" id="leadssss_id">
                                                                            <input type="date" class="form-control pickatime-format-label" id="date_calls" placeholder="">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <input type="time" class="form-control pickatime-format-label" id="time_calls" placeholder="">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <textarea class="form-control" id="comment_calls" ></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect editlead" id="datecalls">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div> 
                                <div id="canceledform" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" >
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
                                                                            <input type="hidden" class="form-control" id="leads_id" value="{{ $lead->id}}">
                                                                        </div>
                                                                        <div class="col-md-12 col-sm-12 my-4">
                                                                            <textarea class="form-control" id="comment_stas_can" ></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary waves-effect editlead" id="notecanceled">Save</button>
                                                <button type="button" class="btn btn-primary waves-effect" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                                </from>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                @endif 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="{{ asset('/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>

<script type="text/javascript">
    
    $("#upsell_quantity").keyup(function () {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 1000000 && parseInt($(this).val()) > 0));
            else
            $(this).val($(this).data("old"));
        });
    $("#price_upsell").keyup(function () {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 1000000 && parseInt($(this).val()) > 0));
            else
            $(this).val($(this).data("old"));
        });
    $("#lead_quantity").keyup(function () {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 1000000 && parseInt($(this).val()) > 0));
            else
            $(this).val($(this).data("old"));
        });
    $("#lead_values").keyup(function () {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 1000000 && parseInt($(this).val()) > 0));
            else
            $(this).val($(this).data("old"));
        });
    $("#quantity").keyup(function () {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 1000000 && parseInt($(this).val()) > 0));
            else
            $(this).val($(this).data("old"));
        });
    $("#total").keyup(function () {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 1000000 && parseInt($(this).val()) > 0));
            else
            $(this).val($(this).data("old"));
        });
$("#mobile_customer").keyup( function(){
    $value = $(this).val();
    if($value){
        $('#whtsapp').attr("href", 'https://wa.me/' + $value);
        $('#call1').attr("href", 'tel:' + $value);
    }
});
$("#customers_phone2").keyup( function(){
    $value = $(this).val();
    if($value){
        $('#wht2').attr("href", 'https://wa.me/' + $value);
        $('#call3').attr("href", 'tel:' + $value);
    }
});
$("#customers_phone").keyup( function(){
    $value = $(this).val();
    if($value){
        $('#sapp').attr("href", 'https://wa.me/' + $value);
        $('#ccall').attr("href", 'tel:' + $value);
    }
});
$("#mobile2_customer").keyup( function(){
    $value = $(this).val();
    if($value){
        $('#wht2').attr("href", 'https://wa.me/' + $value);
        $('#call3').attr("href", 'tel:' + $value);
    }
});
$("#search").keyup( function(){
    $value = $(this).val();
    if($value){
        $('.alldata').hide();
        $('.datasearch').show();
    }else{
        $('.alldata').show();
        $('.datasearch').hide();
    }
    $.ajax({
        type: 'get',
        url: '{{ route('leads.search')}}',
        data: {'search': $value,},
        success:function(data)
        {
            $('#contentdata').html(data);
        }
    });
});
$(document).ready(function(){

    $(function(e){
        $('#savelead').click(function(e){
            var str_value = $('#mobile').val();
            var n = str_value.length;
            if($('#id_product').val() != " " && $('#quantity').val() != "" && $('#total').val() != "" &&  n >=10 && $('#address').val() != ""){
                var idproduct = $('#id_product').val();
                var namecustomer = $('#name_customer').val();
                var quantity = $('#quantity').val();
                var mobile = $('#mobile').val();
                var mobile2 = $('#mobile2').val();
                var cityid = $('#id_city').val();
                var zoneid = $('#id_zone').val();
                var address = $('#address').val();
                var total = $('#total').val();
                var type = $('input[name="statu_confirmation"]').not(this).prop('checked', false);
                alert(type);
                $.ajax({
                    type : 'POST',
                    url:'{{ route('leads.store')}}',
                    cache: false,
                    data:{
                        id: idproduct,
                        namecustomer: namecustomer,
                        quantity: quantity,
                        mobile: mobile,
                        mobile2: mobile2,
                        cityid: cityid,
                        zoneid: zoneid,
                        address: address,
                        total: total,
                        _token : '{{ csrf_token() }}'
                    },
                    success:function(response){
                        if(response.success == true){
                            toastr.success('Good Job.', 'Lead Has been Addess Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                        }
                        location.reload();
                }});
            }else{
                toastr.warning('Opps.', 'Please Complet Data!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
            }
            
        });
    });
// Department Change
$('#id_cit').change(function(){

   // Department id
   var id = $(this).val();

   // Empty the dropdown
   $('#id_zone').find('option').not(':first').remove();
   //console.log(id);
   // AJAX request 
   $.ajax({
     url: 'zone/'+id,
     type: 'get',
     dataType: 'json',
     success: function(response){

       var len = 0;
       if(response['data'] != null){
         len = response['data'].length;
       }

       if(len > 0){
         // Read data and create <option >
         for(var i=0; i<len; i++){

           var id = response['data'][i].id;
           var name = response['data'][i].name;

           var option = "<option value='"+id+"'>"+name+"</option>"; 

           $("#id_zone").append(option); 
         }
       }

     }
  });
});
// Department Change
$('#id_cityy').change(function(){

   // Department id
   var id = $(this).val();

   // Empty the dropdown
   $('#id_zonee').find('option').not(':first').remove();
   //console.log(id);
   // AJAX request 
   $.ajax({
     url: 'zone/'+id,
     type: 'get',
     dataType: 'json',
     success: function(response){

       var len = 0;
       if(response['data'] != null){
         len = response['data'].length;
       }

       if(len > 0){
         // Read data and create <option >
         for(var i=0; i<len; i++){

           var id = response['data'][i].id;
           var name = response['data'][i].name;

           var option = "<option value='"+id+"'>"+name+"</option>"; 

           $("#id_zonee").append(option); 
         }
       }

     }
  });
});
// Department Change
$('#id_city').change(function(){

   // Department id
   var id = $(this).val();

   // Empty the dropdown
   $('#id_zone').find('option').not(':first').remove();
   //console.log(id);
   // AJAX request 
   $.ajax({
     url: 'zone/'+id,
     type: 'get',
     dataType: 'json',
     success: function(response){

       var len = 0;
       if(response['data'] != null){
         len = response['data'].length;
       }

       if(len > 0){
         // Read data and create <option >
         for(var i=0; i<len; i++){

           var id = response['data'][i].id;
           var name = response['data'][i].name;

           var option = "<option value='"+id+"'>"+name+"</option>"; 

           $("#id_zone").append(option); 
         }
       }

     }
  });
});
// Department Change
$('#id_cityys').change(function(){

   // Department id
   var id = $(this).val();

   // Empty the dropdown
   $('#id_zonees').find('option').not(':first').remove();
   //console.log(id);
   // AJAX request 
   $.ajax({
     url: 'zone/'+id,
     type: 'get',
     dataType: 'json',
     success: function(response){

       var len = 0;
       if(response['data'] != null){
         len = response['data'].length;
       }

       if(len > 0){
         // Read data and create <option >
         for(var i=0; i<len; i++){

           var id = response['data'][i].id;
           var name = response['data'][i].name;

           var option = "<option value='"+id+"'>"+name+"</option>"; 

           $("#id_zonees").append(option); 
         }
       }

     }
  });
});
$('body').on('change', '.myform', function(e) {
   e.preventDefault();
   var id = $(this).data('id');
   var statuu = '#statu_con'+id;
   var status = $(statuu).val();
   if(status == "confirmed"){
       $('#leads_ids').val(id);
        $('#searchdetails').modal('show');
        $('#statusconfirmed').modal('show');
   }else{
       $('#leads_id').val(id);
       $('#autherstatus').modal('show');
   }
    
    //console.log(id);
   $.ajax({
      type: "POST",
      url:'{{ route('leads.statuscon')}}',
      cache: false,
      data:{
          id: id,
          status: status,
          _token : '{{ csrf_token() }}'
        },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                }
        }
   });
});
});

$(function(e){
    $('#confirmed').click(function(e){
        $id = $('#lead_id').val();
        $('#lead_id').val($id);
        $('#datedeli').modal('show');
    });
});

$(function(e){
    $('#callaters').click(function(e){
        //console.log(namecustomer);
        $('#callaterpopup').modal('show');
    });
});

//popup call later lead search
$(function(e){
    $('#callater').click(function(e){
        var id = $('#leads_id').val();
        $('#leadssss_id').val(id);
        $('#callaterpopups').modal('show');
    });
});

//lead princepal status canceled
$(function(e){
    $('#cancels').click(function(e){
        //console.log(namecustomer);
        $('#canceledforms').modal('show');
    });
});
//lead princepal status wrong
$(function(e){
    $('#wrong').click(function(e){
        //console.log(namecustomer);
        $('#wrongforms').modal('show');
    });
});
//lead search status canceled
$(function(e){
    $('#cancel').click(function(e){
        //console.log(namecustomer);
        $('#canceledform').modal('show');
    });
});

$(function(e){
    $('.addreclamationgetid').click(function(e){
        //console.log(namecustomer);
        $('#addreclamation').modal('show');
        $('#lead_id_recla').val($(this).data('id'));
    });
});

$(function(e){
    $('body').on('click', '.addreclamationgetid2', function(e){
        //console.log(namecustomer);
        $('#addreclamation').modal('show');
        $('#lead_id_recla').val($(this).data('id'));
    });
});

$(function(e){
    $('#adrecla').click(function(e){
        var idlead = $('#lead_id_recla').val();
        var service = $('#id_service').val();
        var reclamation = $('#reclamation').val();
        $.ajax({
            type : 'POST',
            url:'{{ route('reclamations.store')}}',
            cache: false,
            data:{
                id: idlead,
                service: service,
                reclamation: reclamation,
                _token : '{{ csrf_token() }}'
            },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Reclamation Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                }
        }});
    });
});
$(function(e){
    $('#datedelivreds').click(function(e){
        var id = $('#leadsss_id').val();
        var customename = $('#customers_name').val();
        var customerphone = $('#customers_phone').val();
        var customerphone2 = $('#customers_phone2').val();
        var customeraddress = $('#customers_address').val();
        var customercity = $('#id_cityy').val();
        var customerzone = $('#id_zonee').val();
        var leadvalue = $('#lead_values').val();
        var leadvquantity = $('#lead_quantity').val();
        var product = $('#first_product').val();
        var datedelivred = $('#date_delivredsss').val();
        var commentdeliv = $('#comment_stasss').val();
   $.ajax({
      type: "POST",
      url:'{{ route('leads.confirmed')}}',
      cache: false,
      data:{
          id: id,
          customename: customename,
          customerphone: customerphone,
          customerphone2: customerphone2,
          customeraddress: customeraddress,
          customercity: customercity,
          customerzone: customerzone,
          leadvalue: leadvalue,
          leadvquantity: leadvquantity,
          commentdeliv: commentdeliv,
          datedelivred: datedelivred,
          product: product,
          _token : '{{ csrf_token() }}'
        },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    $('#searchdetails').modal('hide');
                    location.reload();
                }
        }
   });
    });
});
$(function(e){
    $('#confirmeds').click(function(e){
        $id = $('#lead_id').val();
        $('#leadss_id').val($id);
        if($('#id_cityy').val() != " "){
            $('#searchdetails').modal('show');
        }else{
            toastr.warning('Opps.', 'please Select City!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
        }
    });
});

//lead princepal popup status canceled
$(function(e){
    $('#notecanceleds').click(function(e){
        var id = $('#leads_sid').val();
        var customename = $('#customers_name').val();
        var customerphone = $('#customers_phone').val();
        var customerphone2 = $('#customers_phone2').val();
        var commentecanceled = $('#comment_stas_cans').val();
        var customeraddress = $('#customers_address').val();
        var customercity = $('#id_cityy').val();
        var customerzone = $('#id_zonee').val();
        if($('#comment_stas_cans').val() != ""){
            $.ajax({
                type: "POST",
                url:'{{ route('leads.canceled')}}',
                cache: false,
                data:{
                    id: id,
                    commentecanceled: commentecanceled,
                    customerphone: customerphone,
                    customerphone2: customerphone2,
                    customename: customename,
                    customercity: customercity,
                    customerzone: customerzone,
                    customeraddress: customeraddress,
                    _token : '{{ csrf_token() }}'
                    },
                        success:function(response){
                            if(response.success == true){
                                toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                                location.reload();
                            }
                    }
            });
        }
   
    });
});
//lead princepal popup status canceled
$(function(e){
    $('#notewrong').click(function(e){
        var id = $('#leads_sid_wrong').val();
        var customename = $('#customers_name').val();
        var customerphone = $('#customers_phone').val();
        var customerphone2 = $('#customers_phone2').val();
        var commentewrong = $('#comment_stas_wrong').val();
        var customeraddress = $('#customers_address').val();
        var customercity = $('#id_cityy').val();
        var customerzone = $('#id_zonee').val();
        if($('#comment_stas_wrong').val() != ""){
            $.ajax({
                type: "POST",
                url:'{{ route('leads.wrong')}}',
                cache: false,
                data:{
                    id: id,
                    commentewrong: commentewrong,
                    customerphone: customerphone,
                    customerphone2: customerphone2,
                    customename: customename,
                    customercity: customercity,
                    customerzone: customerzone,
                    customeraddress: customeraddress,
                    _token : '{{ csrf_token() }}'
                    },
                        success:function(response){
                            if(response.success == true){
                                toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                                location.reload();
                            }
                    }
            });
        }
   
    });
});
//lead search popup status canceled
$(function(e){
    $('#notecanceled').click(function(e){
        var id = $('#lead_id').val();
        var commentecanceled = $('#comment_stas_can').val();
   $.ajax({
      type: "POST",
      url:'{{ route('leads.canceled')}}',
      cache: false,
      data:{
          id: id,
          commentecanceled: commentecanceled,
          _token : '{{ csrf_token() }}'
        },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                }
        }
   });
    });
});
//lead princepal status unrechs
$(function(e){
    $('#unrechs').click(function(e){
        var idlead = $('#lead_id').val();
        //alert();
        var status = "no answer";
        //console.log(namecustomer);
        $.ajax({
            type : 'POST',
            url:'{{ route('leads.statusc')}}',
            cache: false,
            data:{
                id: idlead,
                status: status,
                _token : '{{ csrf_token() }}'
            },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                }
        }});
    });
});
//lead princepal status unrechs test
$(function(e){
    $('#unrechstest').click(function(e){
        var idlead = $('#lead_id').val();
        //alert();
        var status = "no answer";
        //console.log(namecustomer);
        $.ajax({
            type : 'POST',
            url:'{{ route('leads.statusctest')}}',
            cache: false,
            data:{
                id: idlead,
                status: status,
                _token : '{{ csrf_token() }}'
            },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                }
        }});
    });
});
//lead search status unrechs
$(function(e){
    $('#unrech').click(function(e){
        var idlead = $('#leads_id').val();
        //alert();
        var status = "no answer";
        //console.log(namecustomer);
        $.ajax({
            type : 'POST',
            url:'{{ route('leads.statusc')}}',
            cache: false,
            data:{
                id: idlead,
                status: status,
                _token : '{{ csrf_token() }}'
            },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                }
        }});
    });
});
$(function(e){
    $('#unrech').click(function(e){
        var idlead = $('#lead_id').val();
        //alert();
        var status = "no answer";
        //console.log(namecustomer);
        $.ajax({
            type : 'POST',
            url:'{{ route('leads.statusc')}}',
            cache: false,
            data:{
                id: idlead,
                status: status,
                _token : '{{ csrf_token() }}'
            },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                }
        }});
    });
});
$(function(e){
    $('#datecall').click(function(e){
        var idlead = $('#leads_id').val();
        var date = $('#date_call').val();
        var time = $('#time_call').val();
        var comment = $('#comment_call').val();
        var customename = $('#customers_name').val();
        var customerphone = $('#customers_phone').val();
        var customerphone2 = $('#customers_phone2').val();
        var customeraddress = $('#customers_address').val();
        var customercity = $('#id_cityy').val();
        var customerzone = $('#id_zonee').val();
        var status = "call later";
        //console.log(namecustomer);
        $.ajax({
            type : 'POST',
            url:'{{ route('leads.call')}}',
            cache: false,
            data:{
                id: idlead,
                date: date,
                time: time,
                comment: comment,
                status: status,
                customename: customename,
                customerphone: customerphone,
                customerphone2: customerphone2,
                customeraddress: customeraddress,
                customercity: customercity,
                customerzone: customerzone,
                _token : '{{ csrf_token() }}'
            },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                }
        }});
    });
});

//update price
$(function(e){
    $('#updateprice').click(function(e){
        var idlead = $(this).data('id');
        var quantity = $('#lead_quantity').val();
        var leadvalue = $('#lead_values').val();
        var product = $('#first_product').val();
        //console.log(namecustomer);
        $.ajax({
            type : 'POST',
            url:'{{ route('leads.updateprice')}}',
            cache: false,
            data:{
                id: idlead,
                quantity: quantity,
                leadvalue: leadvalue,
                product: product,
                _token : '{{ csrf_token() }}'
            },
            success:function(response){
                    $('#total_lead_values').val(response.lead_value);
                    $('#totl_lead_quantity').val(response.quantity);
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                }
        }});
    });
});

//call later lead search

$(function(e){
    $('#datecalls').click(function(e){
        var idlead = $('#leadssss_id').val();
        var date = $('#date_calls').val();
        var time = $('#time_calls').val();
        var comment = $('#comment_calls').val();
        var status = "call later";
        var customename = $('#customers_name').val();
        var customerphone = $('#customers_phone').val();
        var customerphone2 = $('#customers_phone2').val();
        var customeraddress = $('#customers_address').val();
        var customercity = $('#id_cityy').val();
        var customerzone = $('#id_zonee').val();
        //console.log(namecustomer);
        $.ajax({
            type : 'POST',
            url:'{{ route('leads.call')}}',
            cache: false,
            data:{
                id: idlead,
                date: date,
                time: time,
                comment: comment,
                status: status,
                customename: customename,
                customerphone: customerphone,
                customerphone2: customerphone2,
                customeraddress: customeraddress,
                customercity: customercity,
                customerzone: customerzone,
                _token : '{{ csrf_token() }}'
            },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    location.reload();
                }
        }});
    });
});


$(function(e){
    $('#changestatus').click(function(e){
        var idlead = $('#leads_id').val();
        var date = $('#date_status').val();
        var comment = $('#coment_sta').val();
        //console.log(namecustomer);
        $.ajax({
            type : 'POST',
            url:'{{ route('leads.notestatus')}}',
            cache: false,
            data:{
                id: idlead,
                date: date,
                comment: comment,
                _token : '{{ csrf_token() }}'
            },
            success:function(response){
                if(response.success == true){
                    $('#datedeli').modal('hide');
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                }
        }});
    });
});

$(function(e){
    $('.seehystory').click(function(e){
        $value = $(this).data('id');
        //alert($value);
        //console.log(namecustomer);
        $.ajax({
            type : 'get',
            url:'{{ route('leads.seehistory')}}',
            cache: false,
            data: {'id': $value,},
            success:function(data){
                $('#StatusLeads').modal('show');
                $('#history').html(data);
        }});
    });
});


//table dynamic
$(document).ready(function() {
    $("#add_row").on("click", function() {
        var newid = 0;
        $.each($("#tab_logic tr"), function() {
            if (parseInt($(this).data("id")) > newid) {
                newid = parseInt($(this).data("id"));
            }
        });
        newid++;
        var tr = $("<tr></tr>", {
            id: "addr"+newid,
            "data-id": newid
        });
        $.each($("#tab_logic tbody tr:nth(0) td"), function() {
            var td;
            var cur_td = $(this);
            var children = cur_td.children();
            if ($(this).data("name") !== undefined) {
                td = $("<td></td>", {
                    "data-name": $(cur_td).data("name")
                });
                var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                c.attr("name", $(cur_td).data("name") + newid);
                c.appendTo($(td));
                td.appendTo($(tr));
            } else {
                td = $("<td></td>", {
                    'text': $('#tab_logic tr').length
                }).appendTo($(tr));
            }
        });
        $(tr).appendTo($('#tab_logic'));
        $(tr).find("td button.row-remove").on("click", function() {
             $(this).closest("tr").remove();
        });
    });
    // Sortable Code
    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
    
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width())
        });
        
        return $helper;
    };
  
    $(".table-sortable tbody").sortable({
        helper: fixHelperModified      
    }).disableSelection();

    $(".table-sortable thead").disableSelection();



    $("#add_row").trigger("click");
});

//table dynamic
$(document).ready(function() {
    //addrows
    $("#add_rows").on("click", function() {
        var newid = 0;
        $.each($("#tab_logics tr"), function() {
            if (parseInt($(this).data("id")) > newid) {
                newid = parseInt($(this).data("id"));
            }
        });
        newid++;
        var tr = $("<tr></tr>", {
            id: "addrs"+newid,
            "data-id": newid
        });
        $.each($("#tab_logics tbody tr:nth(0) td"), function() {
            var td;
            var cur_td = $(this);
            var children = cur_td.children();
            if ($(this).data("name") !== undefined) {
                td = $("<td></td>", {
                    "data-name": $(cur_td).data("name")
                });
                var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
                c.attr("name", $(cur_td).data("name") + newid);
                c.appendTo($(td));
                td.appendTo($(tr));
            } else {
                td = $("<td></td>", {
                    'text': $('#tab_logics tr').length
                }).appendTo($(tr));
            }
        });
        $(tr).appendTo($('#tab_logics'));
        $(tr).find("td button.row-removes").on("click", function() {
             $(this).closest("tr").remove();
        });
    });




    // Sortable Code
    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
    
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width())
        });
        
        return $helper;
    };
  
    $(".table-sortable tbody").sortable({
        helper: fixHelperModified      
    }).disableSelection();

    $(".table-sortable thead").disableSelection();



    $("#add_rows").trigger("click");
});
    
$(function () {
    $('body').on('click', '.upsell', function (products) {
        var id = $('#lead_id').val();
        $('#lead_upsell').val(id);
        $.get("{{ route('leads.index') }}" +'/' + id +'/detailspro', function (response) {
            $('#upsell').modal('show');
            var len = 0;
            if(response['data'] != null){
                len = response['data'].length;
            }
            if(len > 0){
                for(var i=0; i<len; i++){
                    var id = response['data'][i].id;
                    var name = response['data'][i].name;
                    var option = "<option value='"+id+"'>"+name+"</option>";
                    $("#product_upsell").empty('');
                    $("#product_upsell").append(option);
                }
            }
        });
    });
    //multiupsell
    
    $('body').on('click', '.testupsell', function(products) {
                var id = $('#lead_id').val();
                $('#lead_upsell').val(id);
                $.get("{{ route('leads.index') }}" + '/' + id + '/detailspro', function(response) {
                    $('#multiupsell').modal('show');
                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }
                    
                    if (len > 0) {
                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var price = response['data'][i].price;
                            var option = "<option value='" + id + "'>" + name + "/" + price +
                                "</option>";
                            $("#product_upsell").empty('');
                            $("#product_upsell").append(option);
                        }
                    }
                });
            });
    //multiupsells
    
    $('body').on('click', '.testupsells', function (products) {
        var id = $('#lead_id').val();
        $('#lead_upsells').val(id);
        $.get("{{ route('leads.index') }}" +'/' + id +'/detailspro', function (response) {
            $('#multiupsells').modal('show');
            var len = 0;
            if(response['data'] != null){
                len = response['data'].length;
            }
            if(len > 0){
                for(var i=0; i<len; i++){
                    var id = response['data'][i].id;
                    var name = response['data'][i].name;
                    var price = response['data'][i].price;
                    var option = "<option value='"+id+"'>"+name+"/"+price+"</option>";
                    $("#product_upsell").empty('');
                    $("#product_upsellss").append(option);
                }
            }
        });
    });
});

$(function () {
    $('body').on('click', '.upselllist', function (products) {
        var id = $('#lead_id').val();
        $('#lead_upsell').val(id);
        $.get("{{ route('leads.index') }}" +'/' + id +'/listupsell', function (response) {
            $('#Upselliste').modal('show');
            //alert(response);
            $('#infoupsellss').html(response);
        });
    });
});

$(function(e){
    $('#editsheets').click(function(e){
        var idlead = $('#lead_id').val();
        var namecustomer = $('#name_custome').val();
        var quantity = $('#quantity_lead').val();
        var mobile = $('#mobile_customer').val();
        var mobile2 = $('#mobile2_customer').val();
        var cityid = $('#id_cityy').val();
        var zoneid = $('#id_zonee').val();
        var address = $('#customer_adress').val();
        var total = $('#total_lead').val();
        var note = $('#lead_note').val();
        //console.log(namecustomer);
        $.ajax({
            type : 'POST',
            url:'{{ route('leads.update')}}',
            cache: false,
            data:{
                id: idlead,
                namecustomer: namecustomer,
                quantity: quantity,
                mobile: mobile,
                mobile2: mobile2,
                cityid: cityid,
                zoneid: zoneid,
                address: address,
                total: total,
                note: note,
                _token : '{{ csrf_token() }}'
            },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                }
        }});
    });
});
    $(function () {
        $('body').on('click', '.detaillead', function (products) {
        var id = $(this).data('id');
            $.get("{{ route('leads.index') }}" +'/' + id +'/details', function (data) {
                $('#editsheet').modal('show');
                
                $('#lead_id').val(data[0].leads[0].id);
                $('#leads_id').val(data[0].leads[0].id);
                $('#statusleadss').html(data[0].leads[0].status_confirmation);
                $('#n_order').html(data[0].leads[0].n_lead);
                $('#customer_name').val(data[0].leads[0].name);
                $('#mobile_customer').val(data[0].leads[0].phone);
                $('#mobile2_customer').val(data[0].leads[0].phone2);
                $('#customer_address').val(data[0].leads[0].address);
                $('#link_products').val(data[0].product[0].link);
                $('#productLeads').html("<select class='form-control form-select select2-accessible' data-placeholder='sélectionnez une opltion' required='' id='product' name='id_product[]' data-select2-id='fv-topics' tabindex='-1' aria-hidden='true'>@foreach($productss as $v_product) @foreach($v_product['products'] as $product)<option value='{{ $product['id']}}' {{ $product['id'] == "+ data[0].product[0].id +" ? 'selected':'' }}>{{ $product['name']}}</option>@endforeach @endforeach</select>");
                $('#lead_value').val(data[0].leads[0].lead_value);
                $('#lead_quantits').val(data[0].leads[0].quantity);
                $('#product_image').html("<img src='"+ data[0].product[0].image+"' width='80px' />");
                $('#link_video').val(data[0].product[0].link_video);
                $('#customers_note').val(data[0].leads[0].note);
                $('#id_cityys').html("<option value=' '>Select City</option>@foreach($cities as $v_city)<option value='{{ $v_city->id }}' {{ $v_city->id == '"+ data[0].leads[0].id_city +"' ? 'selected':'' }}>{{ $v_city->name }}</option>@endforeach");
                $('#description_product').val(data[0].product[0].description);
                $('#seedetailupsell').val(data[0].leads[0].id);
                $('#call').html("<a class='btn btn-success waves-effect' id='whtsapp' href='https://wa.me/"+ data[0].leads[0].phone +"' target='_blank'><i class='mdi mdi-whatsapp'></i>Whtsapp</a><a class='btn btn-success waves-effect' id='call1' href='tel:"+ data[0].leads[0].phone +"'><i class='mdi mdi-call-made'></i>Call</a>");
                $('#call2').html("<a class='btn btn-success waves-effect' id='wht2' href='https://wa.me/"+ data[0].leads[0].phone2 +"' target='_blank'><i class='mdi mdi-whatsapp'></i>Whtsapp</a><a class='btn btn-success waves-effect' id='call3' href='tel:"+ data[0].leads[0].phone2 +"'><i class='mdi mdi-call-made'></i>Call</a>");
                $('#next_id').val(data[0].leads[0].id - 1);
                $('#previous_id').val(data[0].leads[0].id + 1);
                for(var i in data){
                    var quantity = data[0].leads[0].quantity;
                    var id_product = data[0].leads[0].id_product;
                    var price = data[0].leads[0].lead_value;
                    $('#addr'+i).html("<td>"+ (i+1) +"</td><td><div class='form-control-wrap'><select class='form-control form-select select2-accessible' data-placeholder='sélectionnez une opltion' required='' id='product_lead' name='id_product[]' data-select2-id='fv-topics' tabindex='-1' aria-hidden='true'>@foreach($productss as $v_product) @foreach($v_product['products'] as $product)<option value='{{ $product['id']}}' {{ $product['id'] == '"+ data[i].id_product +"' ? 'selected':'' }}>{{ $product['name']}}</option>@endforeach @endforeach</select></div> </td><td><input  name='quantity[]' id='quantity_lead' type='text' placeholder='Quantity' value='"+quantity+"' required class='form-control input-md'></td><td><input  name='price[]' id='total_lead' type='text' placeholder='Lead Value' value='"+price+"' required class='form-control input-md'></td>");
                    $('#listupsells').html("<tr><td>"+ data[i].product[i].name +"</td><td>"+ data[i].quantity+"</td><td>"+ data[i].lead_value+"</td></tr>");
                }
            });
        });
    });
    
    $(function () {
        $('body').on('click', '.infoupsell', function (products) {
        var id = $('#lead_id').val();
            $.get("{{ route('leads.index') }}" +'/' + id +'/infoupsell', function (data) {
                $('#info-upssel').modal('show');
                $('#upsellsinfo').html(data);
            });
        });
    });
    /*
    $(function () {
        $('body').on('click', '#seedetailupsell', function (products) {
        var id = $('#seedetailupsell').val();
            $.get("{{ route('leads.index') }}" +'/' + id +'/infoupsell', function (data) {
                $('#info-upssel').modal('show');
                
                    $('#infoupsells').html(data);
                
                
            });
        });
    });
    */
    $(function () {
        $('body').on('click', '.upsell', function (products) {
        var id = $(this).data('id');
            $.get("{{ route('leads.index') }}" +'/' + id +'/details', function (data) {
                $('#upsell').modal('show');
                
                $('#lead_id').val(data[0].leads[0].id);
                $('#name_custome').val(data[0].leads[0].name);
                $('#mobile_customer').val(data[0].leads[0].phone);
                $('#mobile2_customer').val(data[0].leads[0].phone2);
                $('#customer_adress').val(data[0].leads[0].address);
                $('#lead_note').val(data[0].leads[0].note);
                $('#id_cityy').val(data[0].leads[0].id_city);
                $('#next_id').val(data[0].leads[0].id - 1);
                $('#previous_id').val(data[0].leads[0].id + 1);
                for(var i in data){
                    var quantity = data[0].leads[0].quantity;
                    var id_product = data[0].leads[0].id_product;
                    var price = data[0].leads[0].lead_value;
                    $('#addr'+i).html("<td>"+ (i+1) +"</td><td><div class='form-control-wrap'><select class='form-control form-select select2-accessible' data-placeholder='sélectionnez une opltion' required='' id='product_lead' name='id_product[]' data-select2-id='fv-topics' tabindex='-1' aria-hidden='true'>@foreach($productss as $v_product) @foreach($v_product['products'] as $product)<option value='{{ $product['id']}}' {{ $product['id'] == '"+ data[i].id_product +"' ? 'selected':'' }}>{{ $product['name']}}</option>@endforeach @endforeach</select></div> </td><td><input  name='quantity[]' id='quantity_lead' type='text' placeholder='Quantity' value='"+quantity+"' required class='form-control input-md'></td><td><input  name='price[]' id='total_lead' type='text' placeholder='Lead Value' value='"+price+"' required class='form-control input-md'></td>");
                
                }
                
            });
        });
    });
    
        $('#saveupsell').click(function(e){
            e.preventDefault();
            var id = $('#lead_upsell').val();
            var leadquantity = $('#lead_quantity').val();
            var leadprice = $('#lead_values').val();
            var product = [];
                $('.product_upsell').find("option:selected").each(function(){
                    product.push($(this).val());
                });
            var quantity = [];
                $(".upsell_quantity").each(function(){
                    quantity.push($(this).val());
                });
            var price = [];
                $(".price_upsell").each(function(){
                    price.push($(this).val());
                });
            //console.log(agent);
            $.ajax({
                type: "POST",
                url:'{{ route('leads.multiupsell')}}',
                cache: false,
                data:{
                    id: id,
                    product: product,
                    quantity: quantity,
                    price: price,
                    leadprice: leadprice,
                    leadquantity: leadquantity,
                    _token : '{{ csrf_token() }}'
                    },
                        success:function(response){
                            if(response.success == true){
                                toastr.success('Good Job.', 'Upsell Has been Added Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                                $('#upsell').modal('hide');
                            location.reload();
                            }
                    }
            });
        });

        //sve multi upsell

        
        $('#saveupsells').click(function(e){
            e.preventDefault();
            var id = $('.lead_upsells').val();
            var product = [];
                $('.product_upsells').find("option:selected").each(function(){
                    product.push($(this).val());
                });
            var quantity = [];
                $(".upsell_quantity").each(function(){
                    quantity.push($(this).val());
                });
            var price = [];
                $(".price_upsell").each(function(){
                    price.push($(this).val());
                });
            $.ajax({
                type: "POST",
                url:'{{ route('leads.multiupsell')}}',
                cache: false,
                data:{
                    'id': id,
                    'product': product,
                    'quantity': quantity,
                    'price': price,
                    _token : '{{ csrf_token() }}'
                    },
                        success:function(response){
                            if(response.success == true){
                                toastr.success('Good Job.', 'Upsell Has been Added Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                                $('#upsell').modal('hide');
                            }
                            location.reload();
                    }
            });
        });

        ///list lead search

        
    
    $('#searchdetai').click(function(e){
            e.preventDefault();
            var n_lead = $('#search_2').val();
            $('#listlead').modal('show');
            //console.log(n_lead);
            $.ajax({
                type: "get",
                url:'{{ route('leads.leadsearch')}}',
                data:{
                    n_lead: n_lead,
                    },
                        success:function(data){
                            $('#listleadss').html(data);
                    }
            });
        });

    $(document).on('click', '.next', function(){
        var id = $('#next_id').val();
        $.get("{{ route('leads.index') }}" +'/' + id +'/details', function (data) {
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
                        for(var i in data){
                            var quantity = data[0].leads[0].quantity;
                            var id_product = data[0].leads[0].id_product;
                            var price = data[0].leads[0].lead_value;
                            $('#addr'+i).html("<td>"+ (i+1) +"</td><td><div class='form-control-wrap'><select class='form-control form-select select2-accessible' data-placeholder='sélectionnez une opltion' required='' id='product_lead' name='id_product[]' data-select2-id='fv-topics' tabindex='-1' aria-hidden='true'>@foreach($productss as $v_product) @foreach($v_product['products'] as $product)<option value='{{ $product['id']}}' {{ $product['id'] == '"+ data[i].id_product +"' ? 'selected':'' }}>{{ $product['name']}}</option>@endforeach @endforeach</select></div> </td><td><input  name='quantity[]' id='quantity_lead' type='text' placeholder='Quantity' value='"+quantity+"' required class='form-control input-md'></td><td><input  name='price[]' id='total_lead' type='text' placeholder='Lead Value' value='"+price+"' required class='form-control input-md'></td>");
                        
                        }
                        
                    });
                });
                $(document).on('click', '.previous', function(){
        var id = $('#previous_id').val();
        $.get("{{ route('leads.index') }}" +'/' + id +'/details', function (data) {
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
                        for(var i in data){
                            var quantity = data[0].leads[0].quantity;
                            var id_product = data[0].leads[0].id_product;
                            var price = data[0].leads[0].lead_value;
                            $('#addr'+i).html("<td>"+ (i+1) +"</td><td><div class='form-control-wrap'><select class='form-control form-select select2-accessible' data-placeholder='sélectionnez une opltion' required='' id='product_lead' name='id_product[]' data-select2-id='fv-topics' tabindex='-1' aria-hidden='true'>@foreach($productss as $v_product) @foreach($v_product['products'] as $product)<option value='{{ $product['id']}}' {{ $product['id'] == '"+ data[i].id_product +"' ? 'selected':'' }}>{{ $product['name']}}</option>@endforeach @endforeach</select></div> </td><td><input  name='quantity[]' id='quantity_lead' type='text' placeholder='Quantity' value='"+quantity+"' required class='form-control input-md'></td><td><input  name='price[]' id='total_lead' type='text' placeholder='Lead Value' value='"+price+"' required class='form-control input-md'></td>");
                        
                        }
                        
        });
    });
//confirmed lead search

$(function(e){
    $('#datedelivre').click(function(e){
        var id = $('#lead_id').val();
        var customename = $('#customer_name').val();
        var customerphone = $('#mobile_customer').val();
        var customerphone2 = $('#mobile2_customer').val();
        var customeraddress = $('#customer_address').val();
        var customercity = $('#id_cityys').val();
        var customerzone = $('#id_zonees').val();
        var leadvalue = $('#lead_value').val();
        var datedelivred = $('#date_delivred').val();
        var commentdeliv = $('#comment_sta').val();
   $.ajax({
      type: "POST",
      url:'{{ route('leads.confirmed')}}',
      cache: false,
      data:{
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
          _token : '{{ csrf_token() }}'
        },
            success:function(response){
                if(response.success == true){
                    toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    $('#datedelivre').modal('hide');
                    location.reload();
                }
        }
   });
    });
});

$(document).on('click', '#searchdetail', function(){
    var id = $('#search_2').val();
    $.get("{{ route('leads.index') }}" +'/' + id +'/seacrhdetails', function (data) {
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
                        $('#addr'+1).html("<td>"+ (1) +"</td><td><div class='form-control-wrap'><select class='form-control form-select select2-accessible' data-placeholder='sélectionnez une opltion' required='' id='product_lead' name='id_product[]' data-select2-id='fv-topics' tabindex='-1' aria-hidden='true'>@foreach($productss as $v_product) @foreach($v_product['products'] as $product)<option value='{{ $product['id']}}' {{ $product['id'] == '"+ data.id_product +"' ? 'selected':'' }}>{{ $product['name']}}</option>@endforeach @endforeach</select></div> </td><td><input  name='quantity[]' id='quantity_lead' type='text' placeholder='Quantity' value='"+quantity+"' required class='form-control input-md'></td><td><input  name='price[]' id='total_lead' type='text' placeholder='Lead Value' value='"+price+"' required class='form-control input-md'></td>");
                    
                  
                    
                });
            });

            //get price product

            $(document).on('click', '#id_product', function(){
                var id = $('#id_product').val();
                $.get("{{ route('products.index')}}" + '/' + id +'/price', function(data){
                    $('#total').val(data);
                });
            });
            



function toggleText(){
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
  $("#id_city").select2({
    dropdownParent: $("#add-new-lead")
  });
});

$(document).ready(function() {
  $("#id_product").select2({
    dropdownParent: $("#add-new-lead")
  });
});

$(document).ready(function() {
  $("#id_zone").select2({
    dropdownParent: $("#add-new-lead")
  });
});

</script>
@endsection