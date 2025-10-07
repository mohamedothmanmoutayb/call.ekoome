@extends('backend.layouts.app')
@section('content')
    <style>
        .hiddenRow {
            padding: 0 !important;
        }

        .select2 {
            width: 100% !important;
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
    </style>
    <div id="overlay" style="display:none;">
        <div class="spinner"></div>
        <br />
        Loading...
    </div>
    <div class="page-wrapper">

        <div class="row 2cam" id="2cam" style="display:none">
            <div class="col-lg-12 col-md-12 align-self-center">
                <video id="preview" style="width:100%"></video>
                <button id="btn-front">Front</button>
                <button id="btn-back">Back</button>
            </div>
        </div>
    </div>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="page-breadcrumb">
            <div class="px-5 py-3 mb-3  d-flex flex-column flex-md-row align-items-center justify-content-between">
                <h4 class="fw-bold ">Pick One Order </h4>
                <div class="d-flex justify-content-end ">
                    <div class="form-group mb-0 text-right">
                        <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white"
                            id="call">Back To Call center</a>
                        <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white"
                            id="pickorder">Pick Orders</a>
                        <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white"
                            id="outstock">Out Of Stock</a>
                        <!--
                                        <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white" data-toggle="modal" data-target="#scans">Scan Orders Return</a>
                                        <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white" data-toggle="modal" data-target="#scan">Send Order For Delivred</a>
                                        <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white" onclick="toggleText()">2 Send Order For Delivred</a> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card order-card">
                    <div class="card-body">
                        <h4 class="card-title"></h4>
                        <span id="contentdata" class="datasearch my-2 mx-4 badge bg-info" style="width: 90px">0 selected</span>
                        <div class="table-responsive theme-scrollbar" style="overflow-y:auto;min-height:450px">
                            <table class="table table-bordernone">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="hidden" id="idproduct" value="{{ $id }}" />
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="selectall custom-control-input"
                                                    id="chkCheckAll" required>
                                                <label class="custom-control-label" for="chkCheckAll"></label>
                                            </div>
                                        </th>
                                        <th >N Lead</th>
                                        <th >Quantity Product</th>
                                        <th >Status</th>
                                        <th >Date Confirmed</th>
                                        <th >Details Product</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    ?>
                                    @foreach ($products as $v_lead)
                                        <tr>
                                            <td>
                                                @if ($v_lead->outstock != 1)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="ids"
                                                            class="custom-control-input checkBoxClass"
                                                            value="{{ $v_lead->id }}" id="pid-{{ $counter }}">
                                                        <label class="custom-control-label"
                                                            for="pid-{{ $counter }}"></label>
                                                    </div>
                                                @endif
                                            </td>
                                            <td> <span class="badge bg-primary">{{ $v_lead->lead }}</span></td>
                                            <td>
                                                {{ $v_lead->quantity }}
                                            </td>
                                            <td>
                                                <span class="badge if($v_lead->status_payment == 'no paid') bg-danger else bg-success">{{ $v_lead->status_payment }}</span> 
                                            </td>

                                            <td>
                                                @if ($v_lead->date_picking)
                                                    {{ $v_lead->date_picking }}
                                                @else
                                                    {{ $v_lead->last_status_change }}
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('leads.edit', $v_lead->id) }}" class="text-inverse pr-2 "
                                                    data-bs-toggle="tooltip" title="Details Product"><i
                                                        class="ti ti-edit"></i></a>
                                            </td>

                                        </tr>
                                        <?php
                                        $counter++;
                                        ?>
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                        <span>Total Orders : {{ $products->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <div id="scan" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Scan Order</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <from class="form-horizontal form-material">
                        <div class="form-group">
                            <div style="width: auto" id="reader"></div>
                            <div class="col-md-12 col-sm-12 m-b-20 m-t-20">
                                <input type="text" placeholder="qrcode" required class="form-control" id="qrcode">
                            </div>
                        </div>
                    </from>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Scan Order Popup Model -->
    <div id="scans" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Scan Order</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <from class="form-horizontal form-material">
                        <div class="form-group">
                            <div style="width: auto" id="reader"></div>
                            <div class="col-md-12 col-sm-12 m-b-20 m-t-20">
                                <input type="text" placeholder="qrcode" required class="form-control" id="qrcodes">
                            </div>
                        </div>
                    </from>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <!-- Add Contact Popup Model -->
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
                                                <th ><span class="sub-text">ID </span></th>
                                                <th ><span class="sub-text">STATUS</span></th>
                                                <th ><span class="sub-text">NOTE</span></th>
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
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Uncheck all checkboxes
            let checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
            });  
        });
    </script>
    <script type="text/javascript">

        $(function(e) {
            $("#chkCheckAll").click(function() {
                $(".checkBoxClass").prop('checked', $(this).prop('checked'));
                var count = $(".checkBoxClass:checked").length;
                    //empty 
                $('#contentdata').empty();
                $('#contentdata').html(count + ' selected');
            });
            $(document).on('click', '.checkBoxClass', function() {
                    var count = $(".checkBoxClass:checked").length;
                    //empty 
                    $('#contentdata').empty();
                    $('#contentdata').html(count + ' selected');
            });

            $('#call').click(function(e) {
                e.preventDefault();
                var userChoice = confirm("Are you sure you want this action!");
                if(userChoice){
                    var allids = [];
                    $("input:checkbox[name=ids]:checked").each(function() {
                        allids.push($(this).val());
                    }); //alert(allids);
                    var product = $('#idproduct').val();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('leads.call') }}',
                        cache: false,
                        data: {
                            ids: allids,
                            product: product,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success == true) {
                                toastr.success('Good Job.',
                                    'Orders Has been Back To Call Center!', {
                                        "showMethod": "slideDown",
                                        "hideMethod": "slideUp",
                                        timeOut: 2000
                                    });
                                location.reload();
                                window.location.href = "{{ route('products.productfordelivred') }}";
                            }
                        }
                    });
                }
            });

            $('#pickorder').click(function(e) {
                e.preventDefault();
                var allids = [];
                $("input:checkbox[name=ids]:checked").each(function() {
                    allids.push($(this).val());
                }); //alert(allids);
                var product = $('#idproduct').val();
                $('#overlay').fadeIn();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('leads.picklist') }}',
                    cache: false,
                    data: {
                        ids: allids,
                        product: product,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                            $('#overlay').hide();
                        if (response.success == true) {
                            toastr.success('Good Job.',
                                'Orders Has been Send For Delivred Success!', {
                                    "showMethod": "slideDown",
                                    "hideMethod": "slideUp",
                                    timeOut: 2000
                                });
                            //location.reload();
                            //redirect to another page

                            window.location.href = "{{ route('products.productfordelivred') }}";
                        }
                        if(response.error == false)
                        {
                            toastr.error('Good Job.',
                                'Quantity mapping is not enough!', {
                                    "showMethod": "slideDown",
                                    "hideMethod": "slideUp",
                                    timeOut: 2000
                                });
                        }
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
            $('#outstock').click(function(e) {
                e.preventDefault();
                var userChoice = confirm("Are you sure you want this action!");
                if(userChoice){
                    var allids = [];
                    $("input:checkbox[name=ids]:checked").each(function() {
                        allids.push($(this).val());
                    }); //alert(allids);
                    $('#outstock').prop("disabled", true);
                    $('#overlay').fadeIn();
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('leads.outstock') }}',
                        cache: false,
                        data: {
                            ids: allids,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success == true) {
                                toastr.success('Good Job.',
                                    'Orders Has been Send For Delivred Success!', {
                                        "showMethod": "slideDown",
                                        "hideMethod": "slideUp",
                                        timeOut: 2000
                                    });
                                location.reload();
                                window.location.href = "{{ route('products.productfordelivred') }}";
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
