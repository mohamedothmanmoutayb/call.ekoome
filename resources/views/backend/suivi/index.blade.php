@extends('backend.layouts.app')

@section('content')
    <style>
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
    </style>
    <!-- Filter code -->
    <div class="card card-body py-3 d-none d-sm-block">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">Suivi</h4>
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
                <h4 class="card-title mb-0">Suivi</h4>
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
                        <div class="col-md-3 col-sm-12 m-b-20 ">
                            <select class="select2 form-control" name="shipping">
                                <option value="">Status Shipping</option>
                                <option value="in transit" {{ 'in transit' == request()->input('shipping') ? 'selected' : '' }}>In Transit</option>
                                <option value="in delivery" {{ 'in delivery' == request()->input('shipping') ? 'selected' : '' }}>In Delivery</option>
                                <option value="proseccing" {{ 'proseccing' == request()->input('shipping') ? 'selected' : '' }}>Processing</option>
                                <option value="delivered" {{ 'delivered' == request()->input('shipping') ? 'selected' : '' }}>Delivered</option>
                                <option value="incident" {{ 'incident' == request()->input('shipping') ? 'selected' : '' }}>Incident</option>
                                <option value="rejected" {{ 'rejected' == request()->input('shipping') ? 'selected' : '' }}>Rejected</option>
                                <option value="returned" {{ 'returned' == request()->input('shipping') ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        {{-- <div class="col-3 align-self-center">
                            <div class='theme-form mb-3'>
                                <input type="text" class="form-control flatpickr-input" name="date"
                                    value="{{ request()->input('date') }}" id="datepicker-range" readonly="readonly">
                            </div>
                        </div> --}}
                        <div class="col-md-3 col-sm-12 m-b-20">
                            <select class="select2 form-control" name="id_prod" placeholder="Selecte Product">
                                <option value="">Select Product</option>
                                @foreach ($proo as $v_product)
                                    <option value="{{ $v_product->id }}"
                                        {{ $v_product->id == request()->input('id_prod') ? 'selected' : '' }}>
                                        {{ $v_product->name }} / {{ $v_product->sku }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 m-b-20 mb-lg-1 mb-3">
                        <select class="select2 form-control" name="shipping_company">
                            <option value="">Select Shipping Company</option>
                            @foreach ($shippingCompanies as $company)
                                <option value="{{ $company->id }}"
                                    {{ $company->id == request()->input('shipping_company') ? 'selected' : '' }}>
                                    {{ $company->name }} 
                                </option>
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
                                <a href="{{ route('suivi.index') }}" class="btn btn-primary waves-effect btn-rounded "
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
                            <select class="select2 form-control" name="shipping">
                                <option value="">Status Shipping</option>
                                <option value="in transit" {{ 'in transit' == request()->input('shipping') ? 'selected' : '' }}>In Transit</option>
                                <option value="in delivery" {{ 'in delivery' == request()->input('shipping') ? 'selected' : '' }}>In Delivery</option>
                                <option value="proseccing" {{ 'proseccing' == request()->input('shipping') ? 'selected' : '' }}>Processing</option>
                                <option value="delivered" {{ 'delivered' == request()->input('shipping') ? 'selected' : '' }}>Delivered</option>
                                <option value="incident" {{ 'incident' == request()->input('shipping') ? 'selected' : '' }}>Incident</option>
                                <option value="rejected" {{ 'rejected' == request()->input('shipping') ? 'selected' : '' }}>Rejected</option>
                                <option value="returned" {{ 'returned' == request()->input('shipping') ? 'selected' : '' }}>Returned</option>
                            </select>
                        </div>
                    </div>
                           <div class="col-md-3 col-sm-12 m-b-20 mb-lg-1 mb-3">
                        <select class="select2 form-control" name="shipping_company">
                            <option value="">Select Shipping Company</option>
                            @foreach ($shippingCompanies as $company)
                                <option value="{{ $company->id }}"
                                    {{ $company->id == request()->input('shipping_company') ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mt-3 col-sm-12 m-b-20 ">
                        {{-- <div class="col-lg-3   col-sm-12 align-self-center">
                            <div class='theme-form mb-3'>
                                <input type="text" class="form-control flatpickr-input" name="date"
                                    value="{{ request()->input('date') }}" id="datepicker-range" readonly="readonly">
                            </div>
                        </div> --}}
                        <div class="col-lg-3 col-sm-12 m-b-20  mb-3">
                            <select class="select2 form-control" name="id_prod" placeholder="Selecte Product">
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
                                <a href="{{ route('suivi.index') }}"
                                    class="btn btn1 btn-primary waves-effect btn-rounded">Reset</a>
                            </div>
                        </div>


                    </div>
                </form>
            </div>
        </ul>
    </div>



    <!-- Tabs code -->
    <div class="row mt-3">
        <div class="col-12">
            <ul class="nav nav-underline mx-auto" style="width: fit-content" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="yesterday-tab" data-bs-toggle="tab" href="#yesterday"
                        role="tab">
                        Yesterday ({{ $yesterdayLeads->total() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="two-days-tab" data-bs-toggle="tab" href="#two-days" role="tab">
                        2 Days Ago ({{ $twoDaysAgoLeads->total() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="three-days-tab" data-bs-toggle="tab" href="#three-days" role="tab">
                        3 Days Ago ({{ $threeDaysAgoLeads->total() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="older-tab" data-bs-toggle="tab" href="#older" role="tab">
                        Older ({{ $olderLeads->total() }})
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="yesterday" role="tabpanel">
                    @include('backend.suivi.partials.leads_table', ['leads' => $yesterdayLeads])
                </div>
                <div class="tab-pane fade" id="two-days" role="tabpanel">
                    @include('backend.suivi.partials.leads_table', ['leads' => $twoDaysAgoLeads])
                </div>
                <div class="tab-pane fade" id="three-days" role="tabpanel">
                    @include('backend.suivi.partials.leads_table', ['leads' => $threeDaysAgoLeads])
                </div>
                <div class="tab-pane fade" id="older" role="tabpanel">
                    @include('backend.suivi.partials.leads_table', ['leads' => $olderLeads])
                </div>
            </div>
        </div>
    </div>
@endsection

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
