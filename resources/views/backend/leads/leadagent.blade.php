@extends('backend.layouts.app')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <style>
        .hiddenRow {
            padding: 0 !important;
        }


        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }

        /* .select2-dropdown.select2-dropdown--below {
            z-index: 9999;
        } */


               .custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    .custom-select-display {
        width: 100%;
        padding: 8px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        background: #fff;
        cursor: pointer;
    }

    .custom-options {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 0 0 6px 6px;
        max-height: 220px;
        overflow-y: auto;
        z-index: 9999;
    }

    .custom-options.show {
        display: block;
    }

    .custom-options input {
        width: 95%;
        margin: 6px;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .custom-options div {
        padding: 8px;
        cursor: pointer;
    }

    .custom-options div:hover {
        background: #f8f9fa;
    }

      

        .whatsapp-icon,
        .map-icon,
        .phone-icon {
            display: inline-block;
            padding: 0;
            background: none;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .whatsapp-icon i {
            color: #25D366;
        }

        .whatsapp-icon:hover i {
            color: #0e7569;
        }

   .map-icon iconify-icon {
            color: #4285F4;
        }

        .map-icon:hover iconify-icon {
            color: #2a58bb;
        }

        .phone-icon iconify-icon {
            color: #323b35;
        }

     .phone-icon:hover iconify-icon {
            color: #19201a;
        }


        #overlay {
            background: #ffffff;
            color: #666666;
            position: fixed;
            height: 100%;
            width: 100%;
            z-index: 5000;
            top: 0;
            left: 0;
            float: left;
            text-align: center;
            padding-top: 25%;
            opacity: .80;
        }

        .spinner {
            margin: 0 auto;
            height: 64px;
            width: 64px;
            animation: rotate 0.3s infinite linear;
            border: 5px solid firebrick;
            border-right-color: transparent;
            border-radius: 50%;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }


        .row.operationbtn button,
        .row.operationbtn a {
            text-transform: uppercase !important;
        }

        @media (max-width: 576px) {
            .col-xs-6 {
                flex: 0 0 auto;
                width: 90%;
            }

            .btn-sm-mobile {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
                line-height: 1.5;
                border-radius: 0.5rem;
            }



        }

        @media (max-width: 1483px) {
            .col-md-6 {
                flex: 0 0 auto;
                width: 50%;
            }

        }

        @media (max-width: 900px) {
            .userU {
                margin-left: 17%;


            }

        }

        @media (min-width: 900px) {
            .userU {
                margin-left: 30%;
                margin-right: 10%;
            }

        }

        @media (max-width: 576px) {
            .card-title {
                font-size: 14px !important;
            }

            .form-group label,
            .form-control,
            .form-control::placeholder {
                font-size: 12px !important;
            }

            .btn {
                font-size: 13px !important;
            }

            .table th,
            .table td {
                font-size: 11px !important;
            }

            .cont {
                width: 130%;
                padding: 3px;
            }

            .mobile-negative-margin {
                margin-left: -20px;
                width: 200%;


            }

            .small {
                margin-right: -55px;
            }

            .mobile-pair {
                display: flex;
                flex-wrap: wrap;
                gap: 0.1rem;
                width: 105%;
            }

            .mobile-pair>div {
                flex: 1 1 48%;
            }

            .mobile-pair>.form-group {
                padding-right: 0rem;
            }


            .mobile-link-btns {
                display: flex;
                justify-content: space-between;
                margin-top: 0.5rem;
            }

            .mobile-link-btns a {
                flex: 1;
                margin: 0 0.25rem;
            }

            .mobile-hide-input {
                display: none !important;
            }

            .card-body {
                width: 100%;
                padding: 15px;
            }

            .cardtable {

                padding: 0px;
            }
        }
    </style>
    @if (Auth::user()->id_role != '3')
        <style>
            .multi {
                display: none;
            }
        </style>
    @endif

    <div class="card card-body py-3">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">Detail Lead</h4>
                    <nav aria-label="breadcrumb" class="ms-auto">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item d-flex align-items-center">
                                <button class="btn btn-primary btn-rounded m-b-10 " data-bs-toggle="modal"
                                    data-bs-target="#add-new-lead">Create New Order</button>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <!-- Column -->
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <form>
                            <div class="row">
                                <div class="col-md-10 col-sm-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" id="search_2"
                                            placeholder="Ref , Name Customer , Phone , Price" aria-label=""
                                            aria-describedby="basic-addon1">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary col-md-12" type="button"
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

    @if (!empty($lead->id))
        <div class="card card-body py-3 cont">
            <div class="col-12 ">
                <!-- Column -->
                <div class="card">
                    <div class="card-body">
                        <!-- Add Contact Popup Model -->
                        <div id="StatusLeads" class="modal fade in" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">History</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <from class="form-horizontal form-material">
                                            <div class="col-md-12 column table-responsive" style="min-height: 100px;">
                                                <table class="table table-bordered table-striped">
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
                        @if (!empty($lead->n_lead))
                            <h4 class="card-title"style="font-size: 25px; margin-bottom: 40px;">
                                <span class="d-block d-md-inline">
                                    <span class="d-block d-md-inline mb-2">
                                        -Date Created:
                                        <span style="color: #4f4b69;" class="me-lg-5">
                                            {{ \Carbon\Carbon::parse($lead->created_at)->format('M d, Y') }}
                                        </span>
                                    </span>

                                    <span class="d-block d-md-inline  mb-2">
                                        - Status Confirmation:
                                        <span style="color: #70a2ca;" class="me-lg-5">
                                            {{ $lead->status_confirmation }}</span>


                                    </span>
                                    <span class="d-block d-md-inline">
                                        @if ($lead->status_confirmation == 'call later')
                                            <span style="color:#fb8c00;">- in {{ $lead->date_call }}</span>
                                        @endif
                                        @if ($lead->status_confirmation == 'no answer')
                                            - in {{ $lead->date_call }}
                                        @endif
                                        @if ($lead->status_confirmation == 'confirmed')
                                            <span class="d-block d-lg-inline">
                                                - Date Shipping:
                                                {{ \Carbon\Carbon::parse($lead->date_shipped)->format('M d, Y') }}
                                            </span>
                                        @endif


                                    </span>
                                </span>



                            </h4>
                            <div class="row small">
                                <div class="col-lg-6 col-sm-12 mobile-negative-margin">
                                    <div class="card" style="box-shadow: 0px 0px 3px 1px;">
                                        <div class="card-body">
                                            <p><!--{{ $lead->status_confirmation }}</p>-->

                                            <div style="display: flex; justify-content: center; align-items: center; ">
                                                <iconify-icon icon="ix:product" class="text-dark"
                                                    style="font-size: 70px;"></iconify-icon>
                                            </div>
                                            <h4 class="card-title"
                                                style="font-size: 25px; margin-left:25%; margin-top:15px;">
                                                Product :
                                                <span>
                                                    ID {{ $lead->n_lead }}
                                                    @if ($lead->isrollback)
                                                        <span
                                                            style="background-color: rgb(255, 179, 0);color:rgb(246, 243, 243)"
                                                            class="badge">Rolled back <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                class="icon icon-tabler icon-tabler-alert-circle-filled"
                                                                width="20" height="20" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor" fill="none"
                                                                stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path
                                                                    d="M12 2c5.523 0 10 4.477 10 10a10 10 0 0 1 -19.995 .324l-.005 -.324l.004 -.28c.148 -5.393 4.566 -9.72 9.996 -9.72zm.01 13l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -8a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z"
                                                                    stroke-width="0" fill="currentColor" />
                                                            </svg></span>
                                                    @endif
                                                </span>
                                            </h4>
                                            <form class="form pt-3"style="">
                                                <div class="row col-sm-12 mb-2 mobile-pair">
                                                    <div class="form-group col-lg-3 mb-2 col-md-12">
                                                        <label>Product Name :</label>
                                                        <select class="form-control" name="product" id="first_product">
                                                            <option value="">Select Product</option>
                                                            @foreach ($productseller as $v_pro)
                                                                <option value="{{ $v_pro->id }}"
                                                                    {{ $v_pro->id == $detailpro->id ? 'selected' : '' }}>
                                                                    {{ $v_pro->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-lg-3 mb-2 col-md-12">
                                                        <label>Stock:</label>
                                                        <input type="text" class="form-control"
                                                            @if (!empty($lead['stock']->qunatity)) value="{{ $lead['stock']->qunatity }}" @endif />
                                                    </div>




                                                    <div class="form-group col-lg-3 mb-2 col-md-12">
                                                        <label>Quantity :</label>
                                                        <input type="text" class="form-control" id="lead_quantity"
                                                            name="quantity"
                                                            value="{{ $lead['leadpro']->quantity ?? $lead->quantity }}" />
                                                    </div>
                                                    <div class="form-group col-lg-3 mb-2 col-md-12">
                                                        <label>Product Price :</label>
                                                        <input type="number" class="form-control" id="lead_values"
                                                            name="price"
                                                            value="{{ $lead['leadpro']->lead_value ?? $lead->lead_value }}" />
                                                    </div>
                                                </div>

                                                <div class="row col-12 mb-2 mobile-pair">
                                                    <div class="form-group col-lg-4 mb-2 col-md-12">
                                                        <label>Quantity Total :</label>
                                                        <input type="text" class="form-control"
                                                            id="totl_lead_quantity" name="quantity" disabled
                                                            value="{{ $lead->quantity ?? 1 }}" />
                                                    </div>
                                                    <div class="form-group col-lg-4 mb-2 col-md-12">
                                                        <label>Total Price :</label>
                                                        <input type="text" class="form-control" id="total_lead_values"
                                                            name="price" value="{{ $lead->lead_value }}" disabled />
                                                    </div>
                                                  <div class="form-group col-lg-4 mb-2 col-md-12">
                                                       <a class="btn btn-primary col-lg-12 waves-effect mx-1 text-white" style="margin-top: 20px;"
                                                                style="font-size: 10px" data-id="{{ $lead->id }}"
                                                                id="updateprice">Update</a> 
                                                    </div>
                                                
                                                    
                                                </div>

                                                <div class="row col-12 mb-2 mobile-pair">
                                                    <div class="form-group m-r-2  col-lg-6 mb-2 col-md-12">
                                                        <label>Discount :</label>
                                                        <div class="d-flex">
                                                            <input type="text" class="form-control"
                                                                id="discount_lead_values" name="price" value="{{ $lead->discount }}"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group m-r-2  col-lg-6 mt-4 col-md-12">
                                                        <div class="d-flex">
                                                            <a class="btn btn-secondary col-lg-6 waves-effect mx-1 text-white"
                                                                style="font-size: 10px" data-id="{{ $lead->id }}"
                                                                id="restoreprice">Return Old Price</a>

                                                            <a class="btn btn-primary col-lg-6 waves-effect mx-1 text-white"
                                                                style="font-size: 10px" data-id="{{ $lead->id }}"
                                                                id="discountprice">Apply
                                                                Discount</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row col-12 mobile-pair">
                                                    <div class="form-group col-lg-6 mb-2 col-md-12">
                                                        <label>Link Product :</label>
                                                        <div class="d-flex align-items-center">
                                                            <input type="text" class="form-control d-none d-lg-block"
                                                                value="{{ $detailpro->link }}" readonly />
                                                            <a class="btn btn-dark waves-effect mx-1"
                                                                href="{{ $detailpro->link }}" target="_blank">

                                                                <iconify-icon icon="ix:link-diagonal"
                                                                    class="fs-7 text-white"></iconify-icon>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-lg-6 mb-2 col-md-12">
                                                        <label>Link Video :</label>
                                                        <div class="d-flex align-items-center">

                                                            <input type="text" class="form-control d-none d-lg-block"
                                                                value="{{ $detailpro->link_video }}" readonly />
                                                            <a class="btn btn-dark waves-effect mx-1"
                                                                href="{{ $detailpro->link_video }}" target="_blank">

                                                                <iconify-icon icon="ix:link-diagonal"
                                                                    class="fs-7 text-white"></iconify-icon>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>

                                        <div class="row" style="width: 80%; margin-left: 2%;">
                                            <div class="form-group col-lg-6 col-md-12">
                                                <label>Description :</label>
                                                <textarea class="form-control">{{ $detailpro->description }}</textarea>
                                            </div>
                                            <div class="form-group col-lg-6 col-md-12">
                                                <img src="{{ $detailpro->image }}"
                                                    style="width: 140px;height:120px ; margin-bottom:65px;"
                                                    loading="lazy" />
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>


                                <div class="col-lg-6 col-sm-12 mobile-negative-margin">
                                    <div class="card" style="box-shadow: 0px 0px 3px 1px;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-12 text-center mb-3 mb-lg-0   userU"
                                                    style="width: 200px;">
                                                    <iconify-icon icon="ix:user-profile" class="text-dark"
                                                        style="font-size: 70px;"></iconify-icon>
                                                </div>

                                                <div
                                                    class="col-lg-3 col-md-12 d-flex justify-content-center justify-content-lg-end gap-3 mt-2">
                                                    <!-- WhatsApp Icon -->
                                                    <a id="sapp" href="https://wa.me/{{ $lead->phone }}?text=hi"
                                                        target="_blank" class="whatsapp-icon">
                                                        <i class="fa fa-whatsapp" style="font-size: 30px;"></i>
                                                    </a>

                                                    <!-- Location Icon -->
                                                    <a href="https://google.com/search?q={{ $lead->address }} {{ $lead->zipcod }}"
                                                        target="_blank" class="map-icon">
                                                        <iconify-icon icon="carbon:location"
                                                            style="font-size: 30px;"></iconify-icon>
                                                    </a>

                                                    <!-- Phone Icon -->
                                                    <a id="ccall" href="tel:{{ $lead->phone }}" class="phone-icon">
                                                        <iconify-icon icon="carbon:phone"
                                                            style="font-size: 30px;"></iconify-icon>
                                                    </a>
                                                </div>
                                            </div>


                                            <h4 class="card-title"
                                                style="font-size: 25px; margin-left:25%; margin-top:15px;">
                                                Customer Information
                                            </h4>
                                            <form class="form pt-3"style="">

                                                <div class="row col-12 mobile-pair">
                                                    <div class="form-group col-lg-6 mb-2 col-md-12">
                                                        <label>Customer Name :</label>
                                                        <input type="text" class="form-control" id="customers_name"
                                                            name="product" value="{{ $lead->name }}" />
                                                    </div>
                                                    <div class="form-group col-lg-6 mb-2 col-md-12">
                                                        <label>Phone 1 :</label>
                                                        <input type="text" class="form-control" id="customers_phone"
                                                            value="{{ $lead->phone }}" />
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="form-group col-lg-12 col-md-12">
                                                        <label>Address :</label>
                                                        <textarea class="form-control" id="customers_address">{{ $lead->address }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-6 ">
                                                        <label>City :</label>
                                                        <select class="form-control select2 o-high-z " id="id_cityy">
                                                            <option value=" ">Select City</option>
                                                            @foreach ($cities as $v_city)
                                                                <option value="{{ $v_city->id }}"
                                                                    {{ $v_city->id == $lead->id_city ? 'selected' : '' }}>
                                                                    {{ $v_city->name }} / {{ $v_city->last_mille }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Warehouses :</label>
                                                        <select class=" form-control" id="id_warehouse">
                                                            <option>Select Warehouse</option>
                                                            @foreach ($warehouses as $v_warehouse)
                                                                <option value="{{ $v_warehouse->id }}"
                                                                    {{ $v_warehouse->id == $lead->warehouse_id ? 'selected' : '' }}>
                                                                    {{ $v_warehouse->name }}
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                </div>
                                           
                                                <div class="row mt-3">
                                                    <div class="form-group col-12">
                                                        <label>Note :</label>
                                                        <textarea class="form-control" id="customer_note" style="height: 150px !important;">{{ $lead->note }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end mt-3" style="margin-bottom: 8px;">
                                                    <button type="button" class="btn btn-success"
                                                        id="updateCustomerBtn">
                                                        <i class="ti ti-save"></i> Update Customer Info
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3" style="width: 95%; margin-left: 1%;">
                                    <div class="col-md-12 column table-responsive" style="min-height: 100px;">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-center">Discount</th>
                                                    <th class="text-center">Note</th>
                                                </tr>
                                            </thead>
                                            <tbody id="infoupsells">
                                                @forelse ($detailupsell as $v_detailupsell)
                                                    <tr>
                                                        <td>{{ $v_detailupsell->quantity }}</td>
                                                        <td>{{ $v_detailupsell->discount }}</td>
                                                        <td>{{ $v_detailupsell->note }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>

                                                        <td colspan="3" class="text-center text-muted">No upsells
                                                            available.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card my-4">
                    <div class="card-body cardtable">

                        <div class="col-lg-12">
                            <div class="row">
                                <h3>Details Upsells</h3>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="col-md-12 column table-responsive" style="min-height: 100px;">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Product Name</th>
                                                        <th class="text-center">Quantity</th>
                                                        <th class="text-center">Price</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($leadproduct->isEmpty())
                                                        <tr>
                                                            <td colspan="4" class="text-center">
                                                                No upsells available.
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @foreach ($leadproduct as $v_leadproduct)
                                                            <tr>
                                                                <td class="text-center">
                                                                    @foreach ($v_leadproduct['product'] as $v_pro)
                                                                        {{ $v_pro->name }}
                                                                    @endforeach
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $v_leadproduct->quantity }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $v_leadproduct->lead_value }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="javascript:void(0)"
                                                                        class="dropdown-item delete-btn"
                                                                        data-id="{{ $v_leadproduct->id }}">
                                                                        <i class="ti ti-trash"></i> Delete
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 m-t2">
                            <div class="col-lg-12 align-self-center">
                                <div class="form-group mb-0 text-center">
                                    <input type="hidden" id="lead_id" value="{{ $lead->id }}" />
                                    <button type="button" class="btn btn-primary btn-rounded my-2 mb-2 testupsell"  data-bs-target="#multiupsell"
                                        data-id="{{ $lead->id }}">Add Upsell</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-4">
                    <div class="card-body cardtable">
                        <div class="col-lg-12">
                            <div class="row">
                                <h3>Details Products</h3>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="col-md-12 column table-responsive" style="min-height: 100px;">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Product Name</th>
                                                        <th class="text-center">Quantity</th>
                                                        <th class="text-center">Price</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($allproductlead->isEmpty())
                                                        <tr>
                                                            <td colspan="4" class="text-center">
                                                                No products available.
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @foreach ($allproductlead as $index => $v_allproductlead)
                                                            <tr>
                                                                <td class="text-center">
                                                                    @foreach ($v_allproductlead['product'] as $v_pro)
                                                                        {{ $v_pro->name }}
                                                                    @endforeach
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $v_allproductlead->quantity }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $v_allproductlead->lead_value }}
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($index > 0)
                                                                        <a href="javascript:void(0)"
                                                                            class="dropdown-item delete-btn"
                                                                            data-id="{{ $v_allproductlead->id }}">
                                                                            <i class="ti ti-trash"></i> Delete
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="overlay" style="display:none;">
                            <div class="spinner"></div>
                            <br />
                            Loading...
                        </div>
                    </div>
                </div>
                <div class="card my-4">
                    <div class="card-body">
                        @if (Auth::user()->id_role == '3')
                            @if ($lead->status_livrison == 'unpacked')
                                <div class="row operationbtn">
                                    <div class="col-12 align-self-center">
                                        <div class="form-group mb-0 text-center">
                                            <input type="hidden" id="lead_id" value="{{ $lead->id }}" />

                                            <button
                                                class="btn btn-success  m-t-10 mb-2 confiremds btn-lg btn-sm-mobile  text-white"
                                                id="confirmeds">Confirmed</button>
                                            <!-- <button type="button" class="btn btn-orange btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id }}" id="unrechs">no answer</button> -->
                                            <button type="button"
                                                class="btn btn-primary  m-t-10 mb-2  btn-sm-mobile text-white "
                                                data-id="{{ $lead->id }}" id="callater">CALL
                                                LATER</button>
                                            <button type="button"
                                                class="btn btn-danger  m-t-10 mb-2 btn-sm-mobile   text-white"
                                                data-id="{{ $lead->id }}" id="cancels">CANCELED</button>
                                            <button type="button"
                                                class="btn btn-warning  m-t-10 mb-2  btn-sm-mobile  text-white"
                                                data-id="{{ $lead->id }}" id="wrong">WRONG</button>
                                            <a type="button"
                                                class="btn btn-dark  btn-sm-mobile   m-t-10 mb-2  text-white "
                                                data-id="{{ $lead->id }}" id="duplicated"> DUPLICATED
                                            </a>
                                            <button type="button" style="background-color: #3d9efa"
                                                class="btn btn-out  m-t-10 mb-2 btn-sm-mobile text-white "
                                                data-id="{{ $lead->id }}" id="Horzone"> OUT OF AREA
                                            </button>
                                            <button type="button"
                                                class="btn btn-info btn-sm-mobile m-t-10 mb-2 text-white"
                                                data-id="{{ $lead->id }}" id="unrechstest">no
                                                answer</button>
                                            <button type="button"
                                                class="btn btn-primary m-t-10 mb-2 btn-sm-mobile text-white"
                                                data-id="{{ $lead->id }}" id="outofstock">Out Of Stock</button>
                                            <button type="button" class="btn btn-dark m-t-10 mb-2   text-white"
                                                data-id="{{ $lead->id }}" id="blacklist">Add to Black
                                                List</button>
                                            <a type="button" class="btn btn-primary m-t-10 mb-2 btn-sm-mobile text-white"
                                                href="{{ route('leads.client', $lead->id) }}" id="outofstock">List Order
                                                This Client -
                                                ({{ $lead->ListOforder($lead->id) }})</a>
                                        </div>
                                        {{-- href="{{ route('leads.duplicated' , $lead->id)}}" --}}
                                    </div>
                                </div>
                            @endif
                        @elseif(Auth::user()->id_role == '4')
                            <div class="row operationbtn">
                                <div class="col-12 align-self-center">
                                    <div class="form-group mb-0 text-center">
                                        <input type="hidden" id="lead_id" value="{{ $lead->id }}" />
                                        <button
                                            class="btn btn-success btn-rounded m-t-10 mb-2 btn-sm-mobile  btn-smconfiremds"
                                            id="confirmeds">Confirmed</button>
                                        <!-- <button type="button" class="btn btn-orange btn-rounded m-t-10 mb-2 " data-id="{{ $lead->id }}" id="unrechs">no answer</button> -->
                                        <button type="button"
                                            class="btn btn-primary btn-sm-mobile btn-rounded m-t-10 mb-2 "
                                            data-id="{{ $lead->id }}" id="callaters">CALL LATER</button>
                                        <button type="button"
                                            class="btn btn-danger btn-sm-mobile btn-rounded m-t-10 mb-2 "
                                            data-id="{{ $lead->id }}" id="cancels">CANCELED</button>
                                        <button type="button"
                                            class="btn btn-warning btn-sm-mobile btn-rounded m-t-10 mb-2 "
                                            data-id="{{ $lead->id }}" id="wrong">WRONG</button>
                                        <button type="button" class="btn btn-dark btn-sm-mobile btn-rounded m-t-10 mb-2 "
                                            data-id="{{ $lead->id }}" id="duplicated">DUPLICATED</button>
                                        <button style="background-color: #3d9efa" type="button"
                                            class="btn btn-out  m-t-10 mb-2 btn-sm-mobile text-white "
                                            data-id="{{ $lead->id }}" id="Horzone"> OUT OF AREA
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm-mobile btn-rounded m-t-10 mb-2 "
                                            data-id="{{ $lead->id }}" id="unrechstest">no answer</button>
                                        <button type="button"
                                            class="btn btn-primary btn-sm-mobile  m-t-10 mb-2   text-white"
                                            data-id="{{ $lead->id }}" id="outofstock">Out Of
                                            Stock</button>
                                        <button type="button" class="btn btn-dark m-t-10 mb-2   text-white"
                                            data-id="{{ $lead->id }}" id="blacklist">Add to Black
                                            List</button>
                                        <a type="button" class="btn btn-primary mb-2  btn-lg  text-white"
                                            href="{{ route('leads.client', $lead->id) }}" id="outofstock">List
                                            Order This Client - ({{ $lead->ListOforder($lead->id) }})</a>
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if (!empty($lead))
                    <div id="wrongforms" class="modal fade in" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Note Wrong</h4>
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
                                                                    id="leads_sid_wrong" value="{{ $lead->id }}">
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 my-4">
                                                                <textarea class="form-control" id="comment_stas_wrong" required></textarea > 
                                                                        <span id="textarea_wrong" class="text-danger"></span>
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

                            <div id="canceledforms" class="modal fade in" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Note Canceled</h4>
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
                                                                            id="leads_sid" value="{{ $lead->id }}">
                                                                    </div>
                                                                    <div class="col-md-12 col-sm-12 my-4">
                                                                        <textarea class="form-control" id="comment_stas_cans"></textarea >  
                                                                    </div>
                                                                    <span id="textarea_Canceled" class="text-danger"></span>
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
                            <!-- test multi upsell -->
                            <div id="multiupsell" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Add Upsell</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <from class="form-horizontal form-material">
                                            <div class="modal-body">
                                                <input type="hidden" id="lead_upsell" class="lead_upsell" />
                                                <div class="col-md-12">
                                                    <table class="table table-bordered table-hover table-sortable" id="tab_logic">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    Product
                                                                </th>
                                                                <th class="text-center">
                                                                    Quantity
                                                                </th>
                                                                <th class="text-center">
                                                                    Price
                                                                </th>
                                                                <th>
                                                                    <a id="add_row" class="btn btn-primary float-right text-white"
                                                                        style="font-size:10px" style="width: 83px;">Add Row</a>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr id='addr0' data-id="0" class="hidden">
                                                                <td data-name="name">
                                                                    <select id="product_upsell" class="form-control product_upsell"
                                                                        name="product_upsell">
                                                                        <option value="">Select Option</option>
                                                                    </select>
                                                                </td>
                                                                <td data-name="mail">
                                                                    <input type="number" name="upsell_quantity" id="upsell_quantity"
                                                                        class="form-control upsell_quantity" placeholder='quantity' />
                                                                </td>
                                                                <td data-name="desc">
                                                                    <input type="number" name="price_upsell" placeholder="price"
                                                                        id="price_upsell" class="form-control price_upsell" />
                                                                </td>
                                                                <td data-name="del">
                                                                    <button name="del0"
                                                                        class='btn btn-danger glyphicon glyphicon-remove row-remove'><span
                                                                            aria-hidden="true">×</span></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <button type="submit" id="saveupsell"
                                                            class="btn btn-primary float-right text-white mt-2">Save</button>
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
                                            id="datedelivre">Save</button>
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
                    <div id="autherstatus" class="modal fade in" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                                <input type="hidden" class="form-control"
                                                                    id="leads_id">
                                                                <input type="date" class="form-control"
                                                                    id="date_status" placeholder="">
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
                    <div id="addreclamation" class="modal fade in" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" style="max-width:1200px">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Complaint</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
                                                                <input type="hidden" class="form-control"
                                                                    id="lead_id_recla" placeholder="N Lead">
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 my-4">
                                                                <select class="form-control" id="id_service"
                                                                    name="id_service">
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
                                        <button type="submit" class="btn btn-primary waves-effect adrecla"
                                            id="adrecla">Save</button>
                                        <button type="button" class="btn btn-primary waves-effect"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </from>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <div id="searchdetails" class="modal fade in" tabindex="-1" role="dialog"
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
                                                                    id="leadsss_id" value="{{ $lead->id }}">
                                                                <input type="date" class="form-control"
                                                                    id="date_delivredsss" placeholder="">
                                                                <span id="date_text" class="text-danger"></span>
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 my-4">
                                                                <textarea class="form-control" id="comment_stasss"></textarea>
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
                    <div id="callaterpopups" class="modal fade in" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Choose Date Call Later</h4>
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
                                                                    id="leadssss_id">
                                                                <input type="date"
                                                                    class="form-control pickatime-format-label"
                                                                    id="date_calls" placeholder="">
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 my-4">
                                                                <input type="time"
                                                                    class="form-control pickatime-format-label"
                                                                    id="time_calls" placeholder="">
                                                            </div>
                                                            <div class="col-md-12 col-sm-12 my-4">
                                                                <textarea class="form-control" id="comment_calls"></textarea>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary waves-effect editlead"
                                            id="datecalls">Save</button>
                                        <button type="button" class="btn btn-primary waves-effect"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </from>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <div id="canceledform" class="modal fade in" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Note Canceled</h4>
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
                                                                <input type="hidden" class="form-control" id="leads_id"
                                                                    value="{{ $lead->id }}">
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
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <img src="{{ asset('public/Calling-cuate.png') }}"
                    style="margin-left: auto ; margin-right: auto; display: block;" width="500" />
            </div>
        </div>
    @endif

    <div id="listlead" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:1200px">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive border rounded-1" style="margin-top:-20px">
                                    <table class="table text-nowrap customize-table mb-0 align-middle">
                                        <thead class="text-dark fs-4">
                                            <tr>
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
    <div id="add-new-lead" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width: 720px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">New Lead</h4>
                </div>
                <form class="form-horizontal form-material">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                             
                                <div class="col-md-4 col-sm-12 mt-10">
                                    <label>Customer Name</label>
                                    <input type="hidden" class="form-control" id="lead_id">
                                    <input type="text" class="form-control" id="name_customer"
                                        placeholder="Customer Name">
                                </div>
                                <div class="col-md-4 col-sm-12 mt-10">
                                     <label>Mobile</label>
                                    <input type="text" class="form-control" id="mobile" placeholder="Mobile">
                                </div>
                                <div class="col-md-4 col-sm-12 mt-10">
                                     <label>Alternative Mobile</label>
                                    <input type="text" class="form-control" id="mobile2" placeholder="Mobile 2">
                                </div>
                            </div>
                            <div class="row">
                                <!-- City -->
                                <div class="col-md-4 col-sm-12 mt-10">
                                    <label>City</label>
                                    <div class="custom-select-wrapper" id="city-select">
                                        <div class="custom-select-display">Select City</div>
                                        <div class="custom-options">
                                            <input type="text" placeholder="Search City..." class="form-control form-control-sm">
                                            @foreach ($cities as $v_city)
                                                <div data-value="{{ $v_city->id }}">
                                                    {{ $v_city->name }} / {{ $v_city->last_mille }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_city" id="id_city">
                                </div>

                                <!-- Warehouse -->
                                <div class="col-md-4 col-sm-12 mt-10">
                                    <label>Warehouse</label>
                                    <div class="custom-select-wrapper" id="warehouse-select">
                                        <div class="custom-select-display">Select Warehouse</div>
                                        <div class="custom-options">
                                            <input type="text" placeholder="Search Warehouse..." class="form-control form-control-sm">
                                            @foreach ($warehouses as $v_warehouse)
                                                <div data-value="{{ $v_warehouse->id }}">
                                                    {{ $v_warehouse->name }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <input type="hidden" name="warehouse_id" id="warehouse_id">
                                </div>

                                      <div class="col-md-4 col-sm-12 mt-10">
                                <label>Address</label>
                                <textarea type="text" class="form-control" id="address" placeholder="Address"></textarea>
                            </div>

                               
                            </div>
                      

                                              <div class="row">
                                <div style="min-width:400px !important;">
                                      <form class="form-horizontal form-material">
                                        <div class="modal-body mt-3 p-0">
                                            <input type="hidden" id="lead_product" class="lead_product" />
                                            <label for="products_table" class="form-label" 
                                                style="color: #b4b3b3 ; margin-left:8px; font-weight:300;">
                                                Products
                                            </label>
                                            <div class="col-md-12 ">
                                                <table class="table table-bordered table-hover table-sortable" id="tab_logics">
                                                    <thead>
                                                        <tr>
                                                            <th class="align-middle text-center">
                                                                Product
                                                            </th>
                                                            <th class="align-middle text-center">
                                                                Quantity
                                                            </th>
                                                            <th class="align-middle text-center">
                                                                Price
                                                            </th>
                                                            <th class="text-center">
                                                                <button id="add_rows"
                                                                    class="btn btn-primary float-right text-white">+
                                                                    </button>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="products_table">
                                                        <tr id="addr0" data-id="0">
                                                            <td>
                                                                <select class="form-control products" name="products[]">
                                                                     <option value="0">Product</option>
                                                                     @foreach ($proo as $v_product)
                                                                        <option value="{{ $v_product->id }}">{{ $v_product->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="product_quantity[]" class="form-control product_quantity" placeholder="quantity" />
                                                            </td>
                                                            <td>
                                                                <input type="number" name="price_product[]" class="form-control price_product" placeholder="price" />
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger row-remove"><span aria-hidden="true">-</span></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                   </form>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary waves-effect" id="savelead">Save</button>
                            <button type="button" class="btn btn-danger waves-effect"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    {{-- <script
        src="{{ asset('/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js') }}">
    </script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('public/assets/libs/prismjs/prism.js') }}"></script>
    <!-- Page JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

   
<script>
    function setupCustomSelect(wrapperId, hiddenInputId) {
        const wrapper = document.getElementById(wrapperId);
        const display = wrapper.querySelector('.custom-select-display');
        const options = wrapper.querySelector('.custom-options');
        const searchBox = options.querySelector('input');
        const items = options.querySelectorAll('div[data-value]');
        const hiddenInput = document.getElementById(hiddenInputId);

        display.addEventListener('click', () => {
            options.classList.toggle('show');
            searchBox.value = '';
            filterOptions('');
            searchBox.focus();
        });

       
        items.forEach(item => {
            item.addEventListener('click', () => {
                display.textContent = item.textContent;
                hiddenInput.value = item.dataset.value;
                options.classList.remove('show');
            });
        });

        
        searchBox.addEventListener('keyup', (e) => {
            filterOptions(e.target.value.toLowerCase());
        });

        function filterOptions(value) {
            items.forEach(option => {
                option.style.display = option.textContent.toLowerCase().includes(value) ? '' : 'none';
            });
        }

        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                options.classList.remove('show');
            }
        });
    }

    setupCustomSelect("city-select", "id_city");
    setupCustomSelect("warehouse-select", "warehouse_id");
    setupCustomSelect("product-select", "id_product");



</script>

<script>
    

            $(document).ready(function() {
         
               
                $('#savelead').click(function (e) {
                    e.preventDefault();

                    let leadData = {
                        id_product: $('#id_product').val(),
                        namecustomer: $('#name_customer').val(),
                        mobile: $('#mobile').val(),
                        mobile2: $('#mobile2').val(),
                        warehouse: $('#warehouse_id').val(),
                        cityid: $('#id_city').val(),
                        address: $('#address').val(),
                        _token: '{{ csrf_token() }}'

                    };

                    let products = [];
                    let total = 0;
                    let totalQuantity = 0;

                    $("#products_table tr").each(function () {
                        let productId = $(this).find(".products").val();
                        let qty = parseFloat($(this).find(".product_quantity").val()) || 0;
                        let price = parseFloat($(this).find(".price_product").val()) || 0;

                        if (productId && qty > 0 && price > 0) {
                            products.push({
                                product_id: productId,
                                quantity: qty,
                                price: price
                            });
                            total += qty * price; 
                            totalQuantity += qty;
                        }
                    });

                    leadData.total = total;
                    leadData.total_quantity = totalQuantity;


                    $.ajax({
                        type: 'POST',
                        url: '{{ route('leads.store') }}',
                        data: {
                            ...leadData,
                            products: products
                        },
                        success: function (response) {
                            if (response.success) {
                                toastr.success('Lead has been added successfully! Total: ' + total);
                                $('#add-new-lead').modal('hide');
                                location.reload();
                            } else {
                                toastr.error('Something went wrong.');
                            }
                        },
                        error: function () {
                            toastr.error('Server error occurred.');
                        }
                    });
                });
            });  
        $('input[name="date"]').daterangepicker();
    

</script>

   <script>

    

$(document).ready(function () {
    let i = 0;

    $("#add_rows").click(function (e) {
        e.preventDefault();
        i++;

        let newRow = `
        <tr id="addr${i}" data-id="${i}">
            <td>
                <select class="form-control products" name="products[]">
                    <option value="">Product</option>
                       @foreach ($proo as $v_product)
                        <option value="{{ $v_product->id }}">{{ $v_product->name }}</option>
                     @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="product_quantity[]" class="form-control product_quantity" placeholder="quantity" />
            </td>
            <td>
                <input type="number" name="price_product[]" class="form-control price_product" placeholder="price" />
            </td>
            <td>
                <button type="button" class="btn btn-danger row-remove"><span aria-hidden="true">-</span></button>
            </td>
        </tr>
        `;

        $("#products_table").append(newRow);
    });

    $(document).on("click", ".row-remove", function () {
        $(this).closest("tr").remove();
    });
});

    </script>

    <script type="text/javascript">


        $("#upsell_quantity").keyup(function() {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 10 && parseInt($(this).val()) >= 1));
            else
                $(this).val(1);
        });
        $("#price_upsell").keyup(function() {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 1000000 && parseInt($(this).val()) >= 1));
            else
                $(this).val(1);
        });
        $("#lead_quantity").keyup(function() {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 10 && parseInt($(this).val()) >= 1));
            else
                $(this).val(1);
        });
        $("#lead_values").keyup(function() {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 1000000 && parseInt($(this).val()) >= 1));
            else
                $(this).val(1);
        });
        $("#quantity").keyup(function() {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 10 && parseInt($(this).val()) >= 1));
            else
                $(this).val(1);
        });
        $("#total").keyup(function() {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 100000 && parseInt($(this).val()) >= 1));
            else
                $(this).val(1);
        });
        $("#mobile_customer").keyup(function() {
            $value = $(this).val();
            if ($value) {
                $('#whtsapp').attr("href", 'https://wa.me/' + $value);
                $('#call1').attr("href", 'tel:' + $value);
            }
        });
        $("#customers_phone2").keyup(function() {
            $value = $(this).val();
            if ($value) {
                $('#wht2').attr("href", 'https://wa.me/' + $value);
                $('#call3').attr("href", 'tel:' + $value);
            }
        });
        $("#customers_phone").keyup(function() {
            $value = $(this).val();
            if ($value) {
                $('#sapp').attr("href", 'https://wa.me/' + $value);
                $('#ccall').attr("href", 'tel:' + $value);
            }
        });
        $("#mobile2_customer").keyup(function() {
            $value = $(this).val();
            if ($value) {
                $('#wht2').attr("href", 'https://wa.me/' + $value);
                $('#call3').attr("href", 'tel:' + $value);
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
        $(document).ready(function() {
            $(".delete-btn").on("click", function(e) {
                e.preventDefault();

                let id = $(this).data("id");

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "/leads/deleteupsell/" + id;
                    }
                });
            });
            $('#new_id_product').change(function() {
                var id = $(this).val();
                var ur = "{{ route('leads.edit', ':id') }}";
                ur = ur.replace(':id', id);
                $.ajax({
                    url: ur,
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }
                        console.log(response);
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                var option = "<option value='" + response['data'][i].id + "'>" +
                                    response['data'][i].name + "</option>";
                                $("#affiliate").empty
                                $("#affiliate").append(option);
                            }
                        }
                    }
                });
            });


    


            $('#id_city').change(function() {

                var id = $(this).val();
                var product = $('#id_product').val();
                // Empty the dropdown
                $('#id_warehouses').find('option').remove();
                // AJAX request 
                $.ajax({
                    url: '{{ route('filtercities') }}',
                    type: 'post',
                    cache: false,
                    data: {
                        id: id,
                        product: product,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }

                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {

                                var id = response['data'][i].id;
                                var name = response['data'][i].name;

                                var option = "<option value='" + id + "'>" + name + "</option>";

                                $("#id_warehouses").append(option);
                            }
                            $('#id_warehouses').select2();
                        }

                    }
                });

            });

            $('body').on('change', '.myform', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var statuu = '#statu_con' + id;
                var status = $(statuu).val();
                if (status == "confirmed") {
                    $('#leads_ids').val(id);
                    $('#searchdetails').modal('show');
                    $('#statusconfirmed').modal('show');
                } else {
                    $('#leads_id').val(id);
                    $('#autherstatus').modal('show');
                }

                //console.log(id);
                $.ajax({
                    type: "POST",
                    url: '{{ route('leads.statuscon') }}',
                    cache: false,
                    data: {
                        id: id,
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
                        }
                    }
                });
            });
        });

        $(function(e) {
            $('#confirmed').click(function(e) {
                $id = $('#lead_id').val();
                $('#lead_id').val($id);
                $('#datedeli').modal('show');
            });
        });

        $(function(e) {
            $('#callaters').click(function(e) {
                //console.log(namecustomer);
                $('#callaterpopup').modal('show');
            });
        });

        //popup call later lead search
        $(function(e) {
            $('#callater').click(function(e) {
                var id = $(this).data("id");
                $('#leadssss_id').val(id);
                $('#callaterpopups').modal('show');
            });
        });

        //lead princepal status canceled
        $(function(e) {
            $('#cancels').click(function(e) {
                //console.log(namecustomer);
                $('#canceledforms').modal('show');
            });
        });
        //lead princepal status wrong
        $(function(e) {
            $('#wrong').click(function(e) {
                //console.log(namecustomer);
                $('#wrongforms').modal('show');
            });
        });
        //lead search status canceled
        $(function(e) {
            $('#cancel').click(function(e) {
                //console.log(namecustomer);
                $('#canceledform').modal('show');
            });
        });

        $(function(e) {
            $('.addreclamationgetid').click(function(e) {
                //console.log(namecustomer);
                $('#addreclamation').modal('show');
                $('#lead_id_recla').val($(this).data('id'));
            });
        });

        $(function(e) {
            $('body').on('click', '.addreclamationgetid2', function(e) {
                //console.log(namecustomer);
                $('#addreclamation').modal('show');
                $('#lead_id_recla').val($(this).data('id'));
            });
        });

        $(function(e) {
            $('#adrecla').click(function(e) {
                var idlead = $('#lead_id_recla').val();
                var service = $('#id_service').val();
                var reclamation = $('#reclamation').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('reclamations.store') }}',
                    cache: false,
                    data: {
                        id: idlead,
                        service: service,
                        reclamation: reclamation,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.',
                                'Reclamation Has been Update Success!', {
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
                var id = $('#leadsss_id').val();
                var customename = $('#customers_name').val();
                var customerphone = $('#customers_phone').val();
                var customerphone2 = $('#customers_phone2').val();
                var customeraddress = $('#customers_address').val();
                var zipcod = $('#customers_zipcod').val();
                var email = $('#customers_email').val();
                var recipient = $('#customers_recipient_number').val();
                var customercity = $('#id_cityy').val();
                var customerzone = $('#id_zonee').val();
                var leadvalue = $('#lead_values').val();
                var leadvquantity = $('#lead_quantity').val();
                var product = $('#first_product').val();
                var datedelivred = $('#date_delivredsss').val();
                var commentdeliv = $('#comment_stasss').val();
                var province = $('#id_province').val();
                var warehouse = $('#id_warehouse').val();

                $('#datedelivreds').prop("disabled", true);
                $('#overlay').fadeIn();
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
                        zipcod: zipcod,
                        email: email,
                        customercity: customercity,
                        customerzone: customerzone,
                        leadvalue: leadvalue,
                        leadvquantity: leadvquantity,
                        commentdeliv: commentdeliv,
                        datedelivred: datedelivred,
                        product: product,
                        province: province,
                        warehouse: warehouse,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.', 'Lead Has been Confirmed Success!', {
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
            $('#confirmeds').click(function(e) {
                $id = $('#lead_id').val();
                $('#leadss_id').val($id);
                if ($('#id_cityy').val() != " ") {
                    $('#searchdetails').modal('show');
                } else {
                    toastr.warning('Opps.', 'please Select City!', {
                        "showMethod": "slideDown",
                        "hideMethod": "slideUp",
                        timeOut: 2000
                    });
                }
            });
        });

        //lead princepal popup status canceled
        $(function(e) {
            $('#notecanceleds').click(function(e) {
                var id = $('#leads_sid').val();
                var customename = $('#customers_name').val();
                var customerphone = $('#customers_phone').val();
                var customerphone2 = $('#customers_phone2').val();
                var commentecanceled = $('#comment_stas_cans').val();
                var customeraddress = $('#customers_address').val();
                var customercity = $('#id_cityy').val();
                var customerzone = $('#id_zonee').val();
                if ($('#comment_stas_cans').val() != "") {
                    $('#notecanceleds').prop("disabled", true);
                    $('#overlay').fadeIn();
                    $.ajax({
                        type: "POST",
                        url: '{{ route('leads.canceled') }}',
                        cache: false,
                        data: {
                            id: id,
                            commentecanceled: commentecanceled,
                            customerphone: customerphone,
                            customerphone2: customerphone2,
                            customename: customename,
                            customercity: customercity,
                            customerzone: customerzone,
                            customeraddress: customeraddress,
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
                } else {
                    $("#textarea_Canceled").empty();
                    $("#textarea_Canceled").append("Please insert Notes");
                }

            });
        });
        //lead princepal popup status canceled
        $(function(e) {
            $('#notewrong').click(function(e) {
                var id = $('#leads_sid_wrong').val();
                var customename = $('#customers_name').val();
                var customerphone = $('#customers_phone').val();
                var customerphone2 = $('#customers_phone2').val();
                var commentewrong = $('#comment_stas_wrong').val();
                var customeraddress = $('#customers_address').val();
                var customercity = $('#id_cityy').val();
                var customerzone = $('#id_zonee').val();
                if ($('#comment_stas_wrong').val() != "") {
                    $('#notewrong').prop("disabled", true);
                    $('#overlay').fadeIn();
                    $.ajax({
                        type: "POST",
                        url: '{{ route('leads.wrong') }}',
                        cache: false,
                        data: {
                            id: id,
                            commentewrong: commentewrong,
                            customerphone: customerphone,
                            customerphone2: customerphone2,
                            customename: customename,
                            customercity: customercity,
                            customerzone: customerzone,
                            customeraddress: customeraddress,
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
                } else {
                    $("#textarea_wrong").empty();
                    $("#textarea_wrong").append("Please insert Notes");
                }

            });
        });
        //lead search popup status canceled
        $(function(e) {
            $('#notecanceled').click(function(e) {
                var id = $('#lead_id').val();
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
        //lead princepal status unrechs
        $(function(e) {
            $('#unrechs').click(function(e) {
                var idlead = $('#lead_id').val();
                var note = $('#customer_note').val();
                //alert();
                var status = "no answer";
                //console.log(namecustomer);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.statusc') }}',
                    cache: false,
                    data: {
                        id: idlead,
                        status: status,
                        note: note,
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

        $('#duplicated').click(function(e) {
            $('#overlay').fadeIn();
            var data_id = $(this).data('id');
            var url = "{{ route('leads.duplicated', ':id') }}";
            url = url.replace(':id', data_id);
            location.href = url;
        });
        $('#Horzone').click(function(e) {
            $('#overlay').fadeIn();
            var data_id = $(this).data('id');
            var url = "{{ route('leads.horzone', ':id') }}";
            url = url.replace(':id', data_id);
            location.href = url;
        });
        $('#Relancement').click(function(e) {
            $('#overlay').fadeIn();
            var data_id = $(this).data('id');
            var url = "{{ route('relancements.index', ':id') }}";
            url = url.replace(':id', data_id);
            location.href = url;
        });

        $(function(e) {
            $('#unrechstest').click(function(e) {
                // .delay(2000).fadeOut()

                var idlead = $('#lead_id').val();
                //alert();
                var status = "no answer";
                var note = $('#customer_note').val();
                //console.log(namecustomer);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.statusctest') }}',
                    cache: false,
                    data: {
                        id: idlead,
                        status: status,
                        note: note,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function(data) {
                        // console.log(data);
                        $('#overlay').fadeIn();
                    },
                    success: function(data) {
                        // console.log(leads.statusctest);
                        console.log(data);
                        toastr.success(data.success);
                        // toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                        $('#overlay').fadeIn().delay(20000);
                        location.reload();
                    },
                    error: function(data) {
                        $('#overlay').fadeOut();
                        toastr.error(data.statusText);
                        // toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    },
                    complete: function() {
                        $('#overlay').fadeOut();
                    }
                });
            });
        });

        $(function(e) {
            $('#outofstock').click(function(e) {
                // .delay(2000).fadeOut()

                var idlead = $('#lead_id').val();
                //alert();
                var status = "outofstock";
                //console.log(namecustomer);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.outofstocks') }}',
                    cache: false,
                    data: {
                        id: idlead,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function(data) {
                        // console.log(data);
                        $('#overlay').fadeIn();
                    },
                    success: function(data) {
                        // console.log(leads.statusctest);
                        console.log(data);
                        toastr.success(data.success);
                        // toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                        $('#overlay').fadeIn().delay(20000);
                        location.reload();
                    },
                    error: function(data) {
                        $('#overlay').fadeOut();
                        toastr.error(data.statusText);
                        // toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    },
                    complete: function() {
                        $('#overlay').fadeOut();
                    }
                });
            });
        });

        $(function(e) {
            $('#blacklist').click(function(e) {
                // .delay(2000).fadeOut()

                var idlead = $('#lead_id').val();
                //alert();
                var status = "blacklist";
                //console.log(namecustomer);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.blacklist') }}',
                    cache: false,
                    data: {
                        id: idlead,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function(data) {
                        // console.log(data);
                        $('#overlay').fadeIn();
                    },
                    success: function(data) {
                        // console.log(leads.statusctest);
                        console.log(data);
                        toastr.success(data.success);
                        // toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                        $('#overlay').fadeIn().delay(20000);
                        location.reload();
                    },
                    error: function(data) {
                        $('#overlay').fadeOut();
                        toastr.error(data.statusText);
                        // toastr.success('Good Job.', 'Lead Has been Update Success!', { "showMethod": "slideDown", "hideMethod": "slideUp", timeOut: 2000 });
                    },
                    complete: function() {
                        $('#overlay').fadeOut();
                    }
                });
            });
        });
        //lead search status unrechs
        $(function(e) {
            $('#unrech').click(function(e) {
                var idlead = $('#leads_id').val();
                //alert();
                var status = "no answer";
                //console.log(namecustomer);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.statusc') }}',
                    cache: false,
                    data: {
                        id: idlead,
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
            $('#unrech').click(function(e) {
                var idlead = $('#lead_id').val();
                //alert();
                var status = "no answer";
                //console.log(namecustomer);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.statusc') }}',
                    cache: false,
                    data: {
                        id: idlead,
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
            $('#datecall').click(function(e) {
                var idlead = $('#lead_id').val();
                var date = $('#date_call').val();
                var time = $('#time_call').val();
                var comment = $('#comment_call').val();
                var customename = $('#customers_name').val();
                var customerphone = $('#customers_phone').val();
                var customerphone2 = $('#customers_phone2').val();
                var customeraddress = $('#customers_address').val();
                var zipcod = $('#customers_zipcod').val();
                var recipient = $('#customers_recipient_number').val();
                var customercity = $('#id_cityy').val();
                var customerzone = $('#id_zonee').val();
                var status = "call later";
                //console.log(namecustomer);
                if ($('#date_call').val() != "" && $('#time_call').val() != "") {
                    $('#datecall').prop("disabled", true);
                    $('#overlay').fadeIn();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('leads.call') }}',
                        cache: false,
                        data: {
                            id: idlead,
                            date: date,
                            time: time + ":00",
                            comment: comment,
                            status: status,
                            customename: customename,
                            customerphone: customerphone,
                            customerphone2: customerphone2,
                            customeraddress: customeraddress,
                            customercity: customercity,
                            customerzone: customerzone,
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
                } else {
                    $("#DataAndTime").empty();
                    $("#DataAndTime").append("Please insert Date and Time");
                }

            });
        });

        //update price
        $(function(e) {
            $('#updateprice').click(function(e) {
                var idlead = $(this).data('id');
                var quantity = $('#lead_quantity').val();
                var leadvalue = $('#lead_values').val();
                var product = $('#first_product').val();
                $('#overlay').fadeIn();
                //console.log(namecustomer);
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.updateprice') }}',
                    cache: false,
                    data: {
                        id: idlead,
                        quantity: quantity,
                        leadvalue: leadvalue,
                        product: product,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            $('#total_lead_values').val(response['update']['lead_value']);
                            $('#totl_lead_quantity').val(response['update']['quantity']);
                            toastr.success('Good Job.', 'Lead Has been Update Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                            $('#overlay').fadeOut();
                        }
                    }
                });
            });




            $('#discountprice').click(function(e) {
                e.preventDefault();

                var idlead = $(this).data('id');
                var discount = $('#discount_lead_values').val().trim(); 

                
                if (discount === '') {
                    toastr.error('Please enter a discount value!');
                    return;
                }

                if (!/^\d+$/.test(discount)) { 
                    toastr.error('Discount must be a whole number!');
                    return;
                }

                discount = parseInt(discount);

                if (discount < 0 || discount > 100) {
                    toastr.error('Discount must be between 0 and 100!');
                    return;
                }

            
                $('#overlay').fadeIn();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.discount') }}',
                    data: {
                        id: idlead,
                        discount: discount,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#discount_lead_values').val('');
                        if (response.success === true) {
                            toastr.success('Lead has been successfully discounted!', '', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                            
                            $('#discount_lead_values').val(discounta);
                            $('#total_lead_values').val(response.total);
                        } else {
                            toastr.error('Discount application failed!', '', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                        }
                        $('#overlay').fadeOut();
                    }
                });
            });


            $('#restoreprice').click(function(e) {
                e.preventDefault();

                var idlead = $(this).data('id');

                $('#overlay').fadeIn();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.restore') }}',
                    data: {
                        id: idlead,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success === true) {
                            toastr.success('Price restored successfully!', 'Success', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });

                            // 🔹 Update total price field
                            $('#total_lead_values').val(response.total);

                        } else {
                            toastr.error(response.message || 'Failed to restore price',
                                'Error', {
                                    "showMethod": "slideDown",
                                    "hideMethod": "slideUp",
                                    timeOut: 2000
                                });
                        }
                        $('#overlay').fadeOut();
                    }
                });
            });


        });

        //call later lead search

        $(function(e) {
            $('#datecalls').click(function(e) {
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
                    type: 'POST',
                    url: '{{ route('leads.call') }}',
                    cache: false,
                    data: {
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
                        'id': $value,
                    },
                    success: function(data) {
                        $('#StatusLeads').modal('show');
                        $('#history').html(data);
                    }
                });
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
                    id: "addr" + newid,
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


        $(function() {
            //multiupsell

            $('body').on('click', '.testupsell', function(products) {
                var id = $('#lead_id').val();
                $('#lead_upsell').val(id);
                var warehouse = $('#id_warehouse').val();
                $.get("{{ route('leads.index') }}" + '/detailspro' + '?id=' + id + '&warehouse=' +
                    warehouse,
                    function(response) {
                        $('#multiupsell').modal('show');
                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }
                        if (len > 0) {
                            $("#product_upsell").empty('');
                            for (var i = 0; i < len; i++) {
                                var id = response['data'][i].id;
                                var name = response['data'][i].name;
                                var price = response['data'][i].price;
                                var option = "<option value='" + id + "'>" + name + "/" + price +
                                    "</option>";
                                $("#product_upsell").append(option);
                            }
                        }
                    });
            });
            //multiupsells

            $('body').on('click', '.testupsells', function(products) {
                var id = $('#lead_id').val();
                $('#lead_upsells').val(id);
                $.get("{{ route('leads.index') }}" + '/' + id + '/detailspro', function(response) {
                    $('#multiupsells').modal('show');
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
                            $("#product_upsellss").append(option);
                        }
                    }
                });
            });
        });

        $(function() {
            $('body').on('click', '.upselllist', function(products) {
                var id = $('#lead_id').val();
                $('#lead_upsell').val(id);
                $.get("{{ route('leads.index') }}" + '/' + id + '/listupsell', function(response) {
                    $('#Upselliste').modal('show');
                    //alert(response);
                    $('#infoupsellss').html(response);
                });
            });
        });

        $(function(e) {
            $('#editsheets').click(function(e) {
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
                    type: 'POST',
                    url: '{{ route('leads.update') }}',
                    cache: false,
                    data: {
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

        $(function() {
            $('body').on('click', '.infoupsell', function(products) {
                var id = $('#lead_id').val();
                $.get("{{ route('leads.index') }}" + '/' + id + '/infoupsell', function(data) {
                    $('#info-upssel').modal('show');
                    $('#upsellsinfo').html(data);
                });
            });
        });

        $(function() {
            $('body').on('click', '.upsell', function(products) {
                var id = $(this).data('id');
                $.get("{{ route('leads.index') }}" + '/' + id + '/details', function(data) {
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

        $('#saveupsell').click(function(e) {
            e.preventDefault();
            var id = $('#lead_upsell').val();
            var leadquantity = $('#lead_quantity').val();
            var leadprice = $('#lead_values').val();
            var product = [];
            $('.product_upsell').find("option:selected").each(function() {
                product.push($(this).val());
            });
            var quantity = [];
            $(".upsell_quantity").each(function() {
                quantity.push($(this).val());
            });
            var price = [];
            $(".price_upsell").each(function() {
                price.push($(this).val());
            });
            //console.log(agent);
            $.ajax({
                type: "POST",
                url: '{{ route('leads.multiupsell') }}',
                cache: false,
                data: {
                    id: id,
                    product: product,
                    quantity: quantity,
                    price: price,
                    leadprice: leadprice,
                    leadquantity: leadquantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        toastr.success('Good Job.', 'Upsell Has been Added Success!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                        $('#upsell').modal('hide');
                        location.reload();
                    }
                }
            });
        });

        //sve multi upsell


        $('#saveupsells').click(function(e) {
            e.preventDefault();
            var id = $('.lead_upsells').val();
            var product = [];
            $('.product_upsells').find("option:selected").each(function() {
                product.push($(this).val());
            });
            var quantity = [];
            $(".upsell_quantity").each(function() {
                quantity.push($(this).val());
            });
            var price = [];
            $(".price_upsell").each(function() {
                price.push($(this).val());
            });
            $.ajax({
                type: "POST",
                url: '{{ route('leads.multiupsell') }}',
                cache: false,
                data: {
                    'id': id,
                    'product': product,
                    'quantity': quantity,
                    'price': price,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        toastr.success('Good Job.', 'Upsell Has been Added Success!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                        $('#upsell').modal('hide');
                    }
                    location.reload();
                }
            });
        });

        ///list lead search



        $('#searchdetai').click(function(e) {
            e.preventDefault();
            var n_lead = $('#search_2').val();
            $('#listlead').modal('show');
            //console.log(n_lead);
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
        //confirmed lead search

        $(function(e) {
            $('#datedelivre').click(function(e) {
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
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.', 'Lead Has been Update Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                            $('#datedelivre').modal('hide');
                            location.reload();
                        }
                    }
                });
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
        $('#updateCustomerBtn').click(function() {
            const data = {
                id: $('#lead_id').val(),
                name: $('#customers_name').val(),
                phone: $('#customers_phone').val(),
                address: $('#customers_address').val(),
                id_city: $('#id_cityy').val(),
                warehouse_id: $('#id_warehouse').val(),
                note: $('#customer_note').val(),
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route('leads.updateCustomer') }}',
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Customer information updated successfully!');
                    } else {
                        toastr.error('Failed to update customer information.');
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred: ' + xhr.responseText);
                }
            });
        });
    </script>




@endsection
