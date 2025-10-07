@extends('backend.layouts.app')
@section('content')
    <style>
        .dropdown-menu.show {
            display: block;
            left: -50px !important;
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
            animation: rotate 0.8s infinite linear;
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
    </style>
    <div id="overlay" style="display:none;">
        <div class="spinner"></div>
        <br />
        Loading...
    </div>





    <div id="assigned" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Scan Order</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <from class="form-horizontal form-material">
                        <div class="form-group">
                            <div style="width: auto" id="readers"></div>
                            <div class="col-md-12 col-sm-12 my-2 my-4">
                                <select class="form-control" id="livreur_id" style="direction: ltr;">
                                    @foreach ($delivery as $v_livreur)
                                        <option value="{{ $v_livreur->id }}">{{ $v_livreur->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 col-sm-12 my-2 my-2">
                                <a type="button" href="#" class="btn btn-primary" id="qrcodes">Send</a>
                            </div>
                        </div>
                    </from>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>



    <div id="scanReturn" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Scan Order Return</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <from class="form-horizontal form-material">
                        <div class="form-group col-lg-12 col-md-12">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 my-2 my-4">
                                    <input type="text" placeholder="qrcode" required class="form-control" id="qrcod2">
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-12 my-2">
                                <a type="button" class="btn btn-primary waves-effect btn-rounded text-white" id="deleteds2"
                                    style="width:100%">deleted rows</a>
                            </div>
                            <div class="col-md-4 col-sm-12 my-2">
                                <a type="button" class="btn btn-primary waves-effect btn-rounded text-white"
                                    id="return_order" style="width:100%">Change</a>
                            </div>
                            <div class="col-md-4 col-sm-12 my-2 my-4">
                                <a type="button" class="btn btn-primary waves-effect btn-rounded text-white"
                                    id="orderscane2" style="width:100%">Send</a>
                            </div>
                        </div>

                    </from>

                    <div class="table-responsive my-4">
                        <table id="" class="table table-bordered table-striped table-hover contact-list"
                            data-paging="true" data-paging-size="7">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="selectall2 custom-control-input"
                                                id="chkCheckAlls2" required>
                                            <label class="custom-control-label" for="chkCheckAlls2"></label>
                                        </div>
                                    </th>
                                    <th>Ref</th>
                                    <th>Status Livrison</th>
                                    <th>Next Status</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="contentdata2" class="datasearch"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


        <div class="card card-body py-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                        <h4 class="mb-4 mb-sm-0 card-title">Ship</h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item d-flex align-items-center">
                                <a class="text-muted text-decoration-none d-flex" href="{{ route('home')}}">
                                <iconify-icon icon="solar:home-2-line-duotone" class="fs-6"></iconify-icon>
                                </a>
                            </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="page-breadcrumb">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Ship</h4>
                    <div class="form-group mb-3 text-left">
                        <select id="pagination" class="form-control" style="width: 80px">
                            <option value="20" @if ($items == 20) selected @endif>20</option>
                            <option value="50" @if ($items == 50) selected @endif>50</option>
                            <option value="100" @if ($items == 100) selected @endif>100</option>
                            <option value="250" @if ($items == 250) selected @endif>250</option>
                            <option value="500" @if ($items == 500) selected @endif>500</option>
                            <option value="1000" @if ($items == 1000) selected @endif>1000</option>
                        </select>
                    </div>
                </div>
                <div class=" d-flex gap-3">


                </div>
            </div>

        </div> --}}
        
        <div class="row">
            <div class="col-12">
                <!-- Column -->
                <div class="card">
                    <div class="card-body">

                        <div class="form-group">
                            <form>
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 my-2">
                                        <input type="text" class="form-control" id="search_ref" name="ref"
                                            placeholder="Ref">
                                    </div>
                                    <div class="col-md-4 col-sm-12 my-2">
                                        <input type="text" class="form-control" name="customer"
                                            placeholder="Customer Name">
                                    </div>
                                    <div class="col-md-4 col-sm-12 my-2">
                                        <input type="text" class="form-control" name="phone1" placeholder="Phone ">
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-md-4 align-self-center">
                                        <div class='input-group'>
                                            <input type="text" name="date" id="dateInput" value=""
                                                class="form-control dated" />
                                        </div>

                                    </div>
                                    <div class="col-md-4 col-sm-12 my-2">

                                        <select class="form-control" name="id_prod">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $v_pro)
                                                <option value="{{ $v_pro->id }}">{{ $v_pro->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-sm-12 my-2">

                                        <input type="text" class="form-control" name="tracking"
                                            placeholder="Tracking ">
                                    </div>
                                    <div class="col-md-2 col-sm-12 align-self-center">
                                        <div class="form-group mb-0">
                                            <button type="submit"
                                                class="btn btn-primary waves-effect btn-rounded m-t-10 mb-2 "
                                                style="width:100%">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-xl-12 col-lg-12 col-md-12 box-col-12">
                <div class="card order-card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1">
                                <p class="square-after f-w-600  dropdown-toggle show" type="button" id="btnGroupDrop1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Select Action<i class="fa fa-circle"></i></p>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" bis_skin_checked="1" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(0px, -44px, 0px);" data-popper-placement="top-start" data-popper-reference-hidden="">
                                    <a type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#scanReturn">Return</a>
                                    <a type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#assigned">Assigned Order</a>

                                    <a type="button" class="dropdown-item" id="GlsBtn">CloseWorkDay</a>
                                    <a type="button" class="dropdown-item" id="exportship"  >Export</a>
                                    <a class="dropdown-item" id="lastWaybillOrder" role="button" target="_blank">Waybill Model</a>
                                </div>

                            </div>
                            <div class="setting-list">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive theme-scrollbar" style="overflow-y:auto;min-height:450px">
                            <table class="table table-bordernone">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="selectall custom-control-input"
                                                id="chkCheckAll" required>
                                            <label class="custom-control-label" for="chkCheckAll"></label>
                                        </div>
                                    </th>
                                    <th>Lead Number</th>
                                    <th>Customer Name</th>
                                    <th>Prepaid</th>
                                    <th>Delivered Status</th>
                                    <th>Products Quantity</th>
                                    <th>Total Price</th>
                                    <th>City</th>
                                    <th>Tracking Number</th>
                                    <th>Last Update</th>
                                    <th>Details Product</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    ?>
                                    @foreach ($leads as $v_lead)
                                        <tr>
                                            <td>
                                                @if ($v_lead['status_livrison'] != 'delivered')
                                                    <div class="custom-control custom-checkbox" style="margin-top:20px ">
                                                        <input type="checkbox" name="ids"
                                                            class="custom-control-input checkBoxClass"
                                                            value="{{ $v_lead->id }}" id="pid-{{ $counter }}">
                                                        <label class="custom-control-label"
                                                            for="pid-{{ $counter }}"></label>
                                                    </div>
                                                @endif
                                            </td>
                                            <td style="width:100px;font-size:14px">
                                                <span class="badge bg-success">{{ $v_lead->n_lead }}</span>
                                            </td>
                                            <td>
                                                {{ $v_lead->name }}
                                            </td>
                                            <td>
                                                @if ($v_lead['ispaidapp'] == 1)
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </td>
                                            <td>
                                                {{ $v_lead['status_livrison'] }}
                                            </td>
                                            <td>
                                                {{ $v_lead['leadproduct']->sum('quantity') }}
                                            </td>
                                            <td>
                                                {{ $v_lead->lead_value }}
                                            </td>
                                            <td>
                                                @foreach ($v_lead['cities'] as $v_city)
                                                    @if ($v_city->name)
                                                        {{ $v_city->name }}
                                                    @else
                                                        {{ $v_lead->city }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                {{ $v_lead->tracking }}
                                            </td>
                                            <td>{{ $v_lead->updated_at }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary rounded"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ti ti-settings"></i>
                                                    </button>
                                                    <div class="dropdown-menu animated slideInUp"
                                                        x-placement="bottom-start"
                                                        style="position: absolute; will-change: transform; transform: translate3d(0px, 35px, 0px);margin-left: -56px !important;">
                                                        <a class="dropdown-item transfer"
                                                            data-id="{{ $v_lead->id }}"><i
                                                                class="ti ti-list-details"></i> Details Products</a>
                                                        <a class="dropdown-item productorder" id="seehystory"
                                                            data-id="{{ $v_lead->id }}"> <i
                                                                class="ti ti-edit"></i>Update Order</a>
                                                        <a class="dropdown-item "
                                                            href="{{ route('products.rollBackFromShipping', $v_lead->id) }}"><i
                                                                class="ti ti-arrow-back"></i> Roll Back </a>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <?php
                                        $counter++;
                                        ?>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- End Page wrapper  -->
    <!-- LisProduct Popup Model -->
    <div id="listproduct" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">List Products</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="demo-foo-addrow"
                                        class="table table-bordered m-t-30 table-hover contact-list" data-paging="true"
                                        data-paging-size="7">
                                        <thead>
                                            <tr>
                                                <th class="nk-tb-col"><span class="sub-text">Product </span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Image</span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Stock</span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="listpro">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>
    <!-- LisProduct Popup Model -->
    <div id="productorders" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">List Products</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <from class="">
                    <div class="modal-body" id="contentorder">

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary waves-effect" id="productsorder">Save</button>
                        <button type="button" class="btn btn-primary waves-effect"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </from>
            </div>

        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".allcheckbox").click(function() {
                var Listids = [];
                var $that = $(this);
                $(':checkbox').each(function() {
                    Listids.push($(this).val());
                    this.checked = $that.is(':checked');
                });
                Listid = Listids.shift();
                $('#list_ids').val(Listids);
            });
            $(".checkBoxClass").click(function() {
                var allids = [];
                $("input:checkbox[name=ids]:checked").each(function() {
                    allids.push($(this).val());
                });
                $('#number').html($("input:checkbox[name=ids]:checked").length);
                $('#list_ids').val(allids);
            });
        });


        // bullk change status return


        //bullk status

        $(function(e) {


            $('#lastWaybillOrder').click(function(e) {
                e.preventDefault();
                var allids = [];
                $("input:checkbox[name=ids]:checked").each(function() {
                    allids.push($(this).val());
                });
                $.ajax({
                    type: 'POST',
                    url: '{{ route('products.apiorder') }}',
                    cache: false,
                    data: {
                        ids: allids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) { //alert(allids);
                        var a = JSON.stringify(allids);
                        // window.open(
                        //     'https://warehouse.ecomfulfilment.eu/products/pack/last-download/' +
                        //     a, '_blank');
                        if (response.success) {
                            toastr.success('Good Job.',
                                'Orders Has been Send For Delivred Success!', {
                                    "showMethod": "slideDown",
                                    "hideMethod": "slideUp",
                                    timeOut: 2000
                                });
                        }
                    }
                });
            });
        });
        $(".detail").click(function() {
            $value = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: '{{ route('products.searchprocess') }}',
                data: {
                    'search': $value,
                },
                success: function(data) {
                    $('#editsheet').modal('show');
                    $('#name_product').val(data.name);
                    $('#barc_cod_stock').val(data.bar_cod);
                }
            });
        });

        //bullk status

        $("#scan_tagier").keyup(function() {
            $value = $(this).val();
            $stock = $('#barc_cod_stock').val();

            $.ajax({
                type: 'GET',
                url: '{{ route('products.check') }}',
                data: {
                    'search': $value,
                    'stock': $stock,
                },
                success: function(response) {
                    if (response.error == false) {
                        toastr.error('Opss.',
                            'Chack Another Shelf This Product not fonde in this Shelf!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                    }
                    if (response.success == true) {
                        $('#editsheets').prop('disabled', false);
                    }
                }
            });
        });

        $("#editsheets").click(function() {
            $stock = $('#barc_cod_stock').val();
            $quantity = $('#quantity').val();
            $tagier = $('#scan_tagier').val();

            $.ajax({
                type: 'POST',
                url: '{{ route('products.outstock') }}',
                data: {
                    'stock': $stock,
                    'quantity': $quantity,
                    'tagier': $tagier,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        toastr.success('Good Job.', 'Upsell Has been Addess Success!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                    location.reload();
                }
            });
        });

        $(".transfer").click(function() {
            $value = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: '{{ route('products.listpro') }}',
                data: {
                    'value': $value,
                },
                success: function(data) {
                    $('#listproduct').modal('show');
                    $('#listpro').html(data);
                }
            });
        });
    </Script>
    <script>
        document.getElementById('pagination').onchange = function() {
            window.location = window.location.href + "?&items=" + this.value;

        };
    </script>
    @if (session()->has('error'))
        <script>
            toastr.error('Good Job.', 'Waybill not exists!', {
                "showMethod": "slideDown",
                "hideMethod": "slideUp",
                timeOut: 2000
            });
        </script>
    @endif
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        $('input[name="date"]').daterangepicker();
    </script>
    <script>
        window.onload = function() {
            document.getElementById("dateInput").value = "";
        };
    </script>
    <script>
        //closingWorkdayGls
        $("#GlsBtn").click(function() {
            var list_ids = $('#list_ids').val();

            var id = $(this).attr('id');
            var allids = [];
            $("input:checkbox[name=ids]:checked").each(function() {
                allids.push($(this).val());
            });

            var choice = confirm("Are you sure, you want to Send those Orders?");

            if (choice) {
                $('#overlay').fadeIn();
                $.ajax({
                    url: '{{ route('products.glsapiorder') }}',
                    type: 'POST',
                    data: {
                        type: 'ValidateOrder',
                        ids: allids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        $('#overlay').hide();
                        toastr.success('Good Job.', 'Orders Has been Send For Delivery Success!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });

                    },
                    error: function(error) {
                        $('#overlay').hide();
                        toastr.error('Error', 'Something wrong', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                });
            }

        });
        $('#exportship').click(function(e) {
                e.preventDefault();
                if(confirm('Are you sure you want to export those orders?')){
                    var allids = [];
                    $("input:checkbox[name=ids]:checked").each(function() {
                        allids.push($(this).val());
                    });
                    $('#labelids').val(allids);
                    $('.shipExport').submit();
                }
        });
    </script>
    <script>
        $(document).on('click', '.checkBoxClass', function() {
            var count = $(".checkBoxClass:checked").length;
            //empty
            $('#contentdata').empty();
            $('#contentdata').html(count + ' selected');
        });
    </script>
@endsection
