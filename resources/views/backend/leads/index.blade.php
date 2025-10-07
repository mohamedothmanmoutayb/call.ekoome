@extends('backend.layouts.app')

@section('content')

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #0d6efd;
            border: 3px solid white;
        }

        .timeline-time {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .timeline-content {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 3px solid #0d6efd;
        }

        .timeline-content h6 {
            margin-top: 0;
            color: #0d6efd;
        }

        .timeline-content p {
            margin-bottom: 0;
        }

        @media (min-width: 900px) {

            .btn1 {
                width: 50%
            }

        }

        @media (max-width: 400px) {

            .marg {
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

            .lil {
                margin-left: 10px !important;
            }

            .fle {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100%;
            }


        }

        .full-width-table {
            width: 90vw;
            margin-left: calc(-45vw + 50%);
        }
    </style>

    <!-- Large screens (default) -->
    <div class="card card-body py-3 d-none d-sm-block">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">Leads</h4>
                    <nav aria-label="breadcrumb" class="ms-auto">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item d-flex align-items-center">
                                <a class="text-muted text-decoration-none d-flex" href="{{ route('home') }}">
                                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile screens -->
    <div class="card card-body py-3 d-block d-sm-none">
        <div class="row align-items-center m-2">
            <div class="col-6">
                <h4 class="card-title mb-0">Leads Overview</h4>
            </div>
            <div class="col-6 text-end">
                <a class="text-muted text-decoration-none" href="{{ route('home') }}">
                    <iconify-icon icon="solar:home-2-line-duotone" class="fs-5"></iconify-icon>
                </a>
            </div>
        </div>
    </div>




    <!-- Desktop version (visible only on large screens and up) -->
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
                    </div>
                    <div class="row mt-3">
                        <div class="col-3 align-self-center">
                            <div class='theme-form mb-3'>
                                <input type="text" class="form-control flatpickr-input" name="date"
                                    value="{{ request()->input('date') }}" id="datepicker-range" readonly="readonly">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 m-b-20">
                            <select class="select2 form-control" id="select_product" name="id_prod"
                                placeholder="Selecte Product">
                                <option value="">Select Product</option>
                                @foreach ($proo as $v_product)
                                    <option value="{{ $v_product->id }}"
                                        {{ $v_product->id == request()->input('id_prod') ? 'selected' : '' }}>
                                        {{ $v_product->name }} / {{ $v_product->sku }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 align-self-center">
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary waves-effect btn-rounded "
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
                </form>
            </div>
        </ul>


    </div>

    <!-- Mobile version (visible only on small to medium screens) -->
    <div class="row mt-3 w-100 m-0 d-flex d-lg-none p-0">
        <ul class="nav nav-pills p-3 mb-3 rounded align-items-center card flex-row">
            <li class="nav-item mb-2">
                <a href="javascript:void(0)" onclick="toggleText()"
                    class="nav-link gap-6 note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 fs-11 active"
                    id="all-category">
                    <i class="ti ti-list fill-white"></i>
                    <span class="d-none d-md-block fw-medium">Filter</span>
                </a>
            </li>
            <div class="col-12 row form-group multi-sm" id="multi-sm">
                <form>
                    <div class="row">
                        <div class="col-md-3 col-sm-12 m-b-20 mb-lg-1 mb-3">
                            <input type="text" class="form-control" id="search_ref" name="ref"
                                value="{{ request()->input('ref') }}" placeholder="Ref">
                        </div>
                        <div class="col-md-3 col-sm-12 m-b-20 mb-lg-1 mb-3">
                            <input type="text" class="form-control" name="customer"
                                value="{{ request()->input('customer') }}" placeholder="Customer Name">
                        </div>
                        <div class="col-md-3 col-sm-12 m-b-20 mb-lg-1 mb-3">
                            <input type="text" class="form-control" name="phone1"
                                value="{{ request()->input('phone1') }}" placeholder="Phone ">
                        </div>
                        <div class="col-md-3 col-sm-12 m-b-20 ">
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
                                <option value="wrong"
                                    {{ 'wrong' == request()->input('confirmation') ? 'selected' : '' }}>Wrong</option>
                                <option value="duplicated"
                                    {{ 'duplicated' == request()->input('confirmation') ? 'selected' : '' }}>Duplicated
                                </option>
                                <option value="out of area"
                                    {{ 'out of area' == request()->input('confirmation') ? 'selected' : '' }}>out of area
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3 col-sm-12 m-b-20 ">
                        <div class="col-lg-3   col-sm-12 align-self-center">
                            <div class='theme-form mb-3'>
                                <input type="text" class="form-control flatpickr-input" name="date"
                                    value="{{ request()->input('date') }}" id="datepicker-range" readonly="readonly">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 m-b-20  mb-3">
                            <select class="select2 form-control" id="select_product" name="id_prod"
                                placeholder="Selecte Product">
                                <option value="">Select Product</option>
                                @foreach ($proo as $v_product)
                                    <option value="{{ $v_product->id }}"
                                        {{ $v_product->id == request()->input('id_prod') ? 'selected' : '' }}>
                                        {{ $v_product->name }} / {{ $v_product->sku }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-sm-3 mb-2 marg">
                            <div class="form-group d-flex  flex-lg-row flex-sm-row gap-2 gap-lg-4">
                                <button type="submit"
                                    class="btn1 btn btn-primary waves-effect btn-rounded">Search</button>
                                <a href="{{ route('leads.index') }}"
                                    class="btn btn1 btn-primary waves-effect btn-rounded">Reset</a>
                            </div>
                        </div>


                    </div>
                </form>
            </div>
        </ul>
    </div>


    <div class="modal fade" id="leadHistoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leadNameTitle">Lead History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="timeline" id="timelineContainer">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>













    <div class="row ross" style="padding:0px; margin:20px 0px; width:100%">
        <div class="col-12" style="padding:0px 0px 0px 0px; width:100% ;">
            <!-- Column -->
            <div class="card full-width-table">
                <div class="card-body" style="padding: 0px 0px 20px 0px;">
                    <!-- Add Contact Popup Model -->
                    {{-- <div id="StatusLeads" class="modal fade in" tabindex="-1" role="dialog"
                        aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">List History</h4>
                                </div>
                                <div class="modal-body">
                                    <from class="form-horizontal form-material">
                                        <div class="table-responsive">
                                            <table id="demo-foo-addrow" class="table m-t-30 table-hover contact-list"
                                                data-paging="true" data-paging-size="7">
                                                <thead>
                                                    <tr>
                                                        <th>User</th>
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
                    </div> --}}

                    <!-- /.modal-dialog -->
                </div>
                <div class="table-responsive border rounded-1" style="margin-top:-20px">
                    <table class="table text-nowrap customize-table mb-0 align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="selectall custom-control-input" id="chkCheckAll"
                                            required>
                                        <label class="custom-control-label" for="chkCheckAll"></label>
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>Mareket</th>
                                <th>Products</th>
                                <th>Name</th>
                                <th>City</th>
                                <th>Phone</th>
                                <th>Lead Value</th>
                                <th>Agent</th>
                                <th>Confirmation</th>
                                <th>Livrison</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 1;
                            ?>
                            @if (!$leads->isempty())
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
                                        <td>
                                            <span class="badge bg-secondary">{{ $v_lead['n_lead'] }}</span>
                                        </td>
                                        <td>
                                            @if ($v_lead['market'] == 'Shopify')
                                                <i class="tf-icons ti ti-brand-shopee"
                                                    style="margin-right: 10px;font-size: 33px;"></i>
                                            @elseif($v_lead['market'] == 'Google Sheet')
                                                <i class="tf-icons ti ti-brand-google"
                                                    style="margin-right: 10px;font-size: 33px;"></i>
                                            @elseif($v_lead['market'] == 'Manual')
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    style="margin-right: 10px;font-size: 33px;" width="30"
                                                    height="30" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class=" icon icon-tabler icons-tabler-outline icon-tabler-hand-click">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M8 13v-8.5a1.5 1.5 0 0 1 3 0v7.5" />
                                                    <path d="M11 11.5v-2a1.5 1.5 0 0 1 3 0v2.5" />
                                                    <path d="M14 10.5a1.5 1.5 0 0 1 3 0v1.5" />
                                                    <path
                                                        d="M17 11.5a1.5 1.5 0 0 1 3 0v4.5a6 6 0 0 1 -6 6h-2h.208a6 6 0 0 1 -5.012 -2.7l-.196 -.3c-.312 -.479 -1.407 -2.388 -3.286 -5.728a1.5 1.5 0 0 1 .536 -2.022a1.867 1.867 0 0 1 2.28 .28l1.47 1.47" />
                                                    <path d="M5 3l-1 -1" />
                                                    <path d="M4 7h-1" />
                                                    <path d="M14 3l1 -1" />
                                                    <path d="M15 6h1" />
                                                </svg>
                                            @elseif($v_lead['market'] == 'Excel')
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    style="margin-right: 10px;font-size: 33px;" width="30"
                                                    height="30" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-table-export">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12.5 21h-7.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7.5" />
                                                    <path d="M3 10h18" />
                                                    <path d="M10 3v18" />
                                                    <path d="M16 19h6" />
                                                    <path d="M19 16l3 3l-3 3" />
                                                </svg>
                                            @elseif($v_lead['market'] == 'api')
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    style="margin-right: 10px;font-size: 33px;" width="30"
                                                    height="30" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-api">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 13h5" />
                                                    <path d="M12 16v-8h3a2 2 0 0 1 2 2v1a2 2 0 0 1 -2 2h-3" />
                                                    <path d="M20 8v8" />
                                                    <path d="M9 16v-5.5a2.5 2.5 0 0 0 -5 0v5.5" />
                                                </svg>
                                            @elseif($v_lead['market'] == 'Lightfunnels')
                                                <img src="{{ asset('public/plateformes/lightlogo.png') }}" width="30"
                                                    height="30" alt="Lightfunnels" style="filter: grayscale(100%);">
                                            @elseif($v_lead['market'] == 'YouCan')
                                                <img src="{{ asset('public/plateformes/youcanlogo.png') }}"
                                                    width="30" height="30" alt="YouCan"
                                                    style="background-color: rgb(178, 172, 172);">
                                            @elseif($v_lead['market'] == 'WooCommerce')
                                                <img src="{{ asset('public/plateformes/woocommerce-logo.png') }}"
                                                    width="30" height="30" alt="YouCan"
                                                    style="filter: grayscale(100%);">
                                            @else
                                                {{ $v_lead['market'] }}
                                            @endif
                                        </td>
                                        <td>
                                            @foreach ($v_lead['product'] as $v_product)
                                                {{ Str::limit($v_product['name'], 20) }}
                                            @endforeach
                                        </td>
                                        <td>{{ $v_lead['name'] }}</td>
                                        <td>
                                            @if (!empty($v_lead['id_city']))
                                                @if (!empty($v_lead['cities'][0]['name']))
                                                    @foreach ($v_lead['cities'] as $v_city)
                                                        {{ $v_city['name'] }}
                                                        <br>
                                                    @endforeach
                                                @else
                                                    {{ $v_lead['city'] }}
                                                    <br>
                                                @endif
                                            @else
                                                {{ $v_lead['city'] }}
                                                <br>
                                            @endif
                                            {{ Str::limit($v_lead['address'], 10) }}
                                        </td>
                                        <td><a href="tel:{{ $v_lead['phone'] }}">{{ $v_lead['phone'] }}</a></td>
                                        <td>{{ $v_lead['lead_value'] }}</td>
                                        <td>
                                            @if (!empty($v_lead['assigned']))
                                                {{ $v_lead['assigned']->name }}
                                            @endif
                                        </td>
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
                                                <span class="badge bg-danger">{{ $v_lead['status_confirmation'] }}</span>
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
                                        <td>{{ $v_lead['created_at'] }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle show" type="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="true"><i class="icon-settings"></i></button>
                                                <div class="dropdown-menu" bis_skin_checked="1"
                                                    style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(184px, -325.203px, 0px);"
                                                    data-popper-placement="top-start">

                                                    <a class="dropdown-item seehystory" id="seehystory"
                                                        data-id="{{ $v_lead['id'] }}"> History</a>
                                                    <a class="dropdown-item "
                                                        href="{{ route('leads.edit', $v_lead['id']) }}"
                                                        id="">Details</a>
                                                    <a class="dropdown-item "
                                                        href="{{ route('leads.details', $v_lead['id']) }}"
                                                        id=""> order</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $counter = $counter + 1; ?>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11">
                                        <img src="{{ asset('public/Calling-cuate.png') }}"
                                            style="margin-left: auto ; margin-right: auto; display: block;"
                                            width="500" />
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <span class="mt-4"> </span>
                {{ $leads->withQueryString()->links('vendor.pagination.courier') }}
            </div>
        </div>
    </div>



    <!-- end Default Size Light Table -->

@section('script')
    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('public/assets/libs/prismjs/prism.js') }}"></script>
    <!-- Page JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $('input[name="date"]').daterangepicker();
    </script>
    <script type="text/javascript">
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
            $("#chkCheckAll").click(function() {
                $(".checkBoxClass").prop('checked', $(this).prop('checked'));
            });
            $(function(e) {
                $('#savelead').click(function(e) {
                    var idproduct = $('#id_product').val();
                    var namecustomer = $('#name_customer').val();
                    var quantity = $('#quantity').val();
                    var mobile = $('#mobile').val();
                    var mobile2 = $('#mobile2').val();
                    var country = $('#id_country').val();
                    var cityid = $('#id_city').val();
                    var zoneid = $('#id_zone').val();
                    var address = $('#address').val();
                    var total = $('#total').val();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('leads.store') }}',
                        cache: false,
                        data: {
                            id: idproduct,
                            namecustomer: namecustomer,
                            quantity: quantity,
                            mobile: mobile,
                            mobile2: mobile2,
                            country: country,
                            cityid: cityid,
                            zoneid: zoneid,
                            address: address,
                            total: total,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success == true) {
                                toastr.success('Good Job.',
                                    'Lead Has been Addess Success!');
                            }
                            location.reload();
                        }
                    });
                });
            });
            // Department Change
            $('#id_country').change(function() {

                // Department id
                var id = $(this).val();

            });

        });

        $(function(e) {
            $('#confirmed').click(function(e) {
                //console.log(namecustomer);
                $('#searchdetails').modal('show');
            });
        });

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
            $('.seehystory').click(function(e) {
                const leadId = $(this).data('id');
                loadLeadHistory(leadId);
            });
        });

        function loadLeadHistory(leadId) {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css('padding-right', '');

            $('.modal.show').each(function() {
                const modalInstance = bootstrap.Modal.getInstance(this);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });

            $.ajax({
                url: '{{ route('leads.seehistory') }}',
                type: 'GET',
                data: {
                    'id': leadId,
                },
                success: function(response) {
                    console.log(response);
                    const timeline = $('#timelineContainer');
                    timeline.empty();

                    if (response.length > 0) {
                        response.forEach(function(history) {
                            const leadNumber = history.lead.n_lead ?? 'N/A';
                            let statusDisplay = history.status ?
                                `<p><strong>Status:</strong> ${history.status}</p>` :
                                '';
                            let commentDisplay = history.comment ?
                                `<p><strong>Comment:</strong> ${history.comment}</p>` :
                                'There is no comment';

                            let agentDisplay = history.agent.length != 0 ?
                                `<p><strong>Agent:</strong> ${history.agent}</p>` :
                                '';

                            let deliveryDisplay = '';
                            if (history.delivery.length != 0) {
                                deliveryDisplay = `
                                    <p><strong>Delivery:</strong></p>
                                    <ul>
                                        <li><strong>Date:</strong> ${history.delivery.delivery_date || 'N/A'}</li>
                                        <li><strong>Time:</strong> ${history.delivery.delivery_time || 'N/A'}</li>
                                        <li><strong>Address:</strong> ${history.delivery.delivery_address || 'N/A'}</li>
                                    </ul>
                                `;
                            }

                            timeline.append(`
                                <div class="timeline-item">
                                    <div class="timeline-time">
                                        ${moment(history.created_at).format('YYYY-MM-DD')} 
                                        <strong>${moment(history.created_at).format('H:mm')}</strong>
                                        </div>
                                    <div class="timeline-content">
                                        <h6><a href="/leads/edit/${leadId}" target="_blank">Lead #${leadNumber}</a></h6>
                                        ${statusDisplay}
                                        ${commentDisplay}
                                        ${deliveryDisplay}
                                        ${agentDisplay}
                                    </div>
                                </div>
                            `);
                        });
                    } else {
                        timeline.html('<p>No activity recorded for this lead.</p>');
                    }

                    const modal = new bootstrap.Modal(document.getElementById('leadHistoryModal'));
                    modal.show();
                },
                error: function() {
                    alert('Error loading lead history');
                }
            });
        }



        $(function() {
            $('body').on('click', '.upsell', function(products) {
                var id = $('#lead_id').val();
                $('#lead_upsell').val(id);
                $.get("{{ route('leads.index') }}" + '/' + id + '/detailspro', function(response) {
                    $('#upsell').modal('show');

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

                            $("#product_upsell").append(option);
                        }
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

        //popup detail upsell
        $(function() {
            $('body').on('click', '.infoupsell', function(products) {
                var id = $('#lead_id').val();
                $.get("{{ route('leads.index') }}" + '/' + id + '/infoupsell', function(data) {
                    $('#info-upssel').modal('show');

                    $('#infoupsells').html(data);


                });
            });
        });

        $(function() {
            $('body').on('click', 'upsell', function(products) {
                var id = $('#lead_id').val();
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

        //Export
        $(function(e) {
            $("#chkCheckAll").click(function() {
                $(".checkBoxClass").prop('checked', $(this).prop('checked'));
            });
            //export date
            $('#exportss2').click(function(e) {
                e.preventDefault();
                var allids = [];
                $("input:checkbox[name=ids]:checked").each(function() {
                    allids.push($(this).val());
                });
                var date = $('#flatpickr-range').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.exports') }}',
                    cache: false,
                    data: {
                        date: date,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response, leads) {
                        $.each(response, function(key, val, leads) {
                            var a = response;
                            window.location = ('leads/export-downloads/' + a);
                        });
                    }
                });
            });

        });





        function toggleText() {
            $('#timeseconds').val('');

            if ($(window).width() <= 767) {
                $('#multi-sm').toggle();
            } else {
                $('#multi-lg').toggle();
            }
        }
    </script>
    <script>
        const leads = @json($leads);
        console.table(leads); // or use console.dir(leads)
    </script>
@endsection
@endsection
