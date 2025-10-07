@extends('backend.layouts.app')
@section('content')
    <style>
        .dropdown-menu.show {
            display: block;
        }

        .page-wrapper {
            display: block;
            height: 100vh;
        }
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

            <div class="card card-body py-3">
                <div class="row align-items-center">
                  <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                      <h4 class="mb-4 mb-sm-0 card-title">Pick</h4>
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
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> </span> </h4>
                    <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light" data-bs-toggle="modal"
                        data-bs-target="#editsheet">Pick One Order</button>
                </div>
            </div> --}}
            <div class="col-xl-12 col-lg-12 col-md-12 box-col-12">
                <div class="card order-card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1">
                                <p class="square-after f-w-600  dropdown-toggle show" type="button">Select Action<i class="fa fa-circle"></i></p>

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
                                    <th>Image</th>
                                    <th>Seller</th>
                                    <th>Product</th>
                                    <th>Quantity in Stock</th>
                                    <th>Mapping</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php $counter = 1; ?>
                                    @foreach ($lead as $v_lead)
                                        @if (!empty($v_lead->id))
                                            @if ($v_lead->CountLeadG($v_lead->id) != 0)
                                                <tr>
                                                    <td><span class="badge bg-primary">{{ $v_lead->sku }}</span></td>
                                                    <td><img class="detail" data-id="{{ $v_lead->id }}" src="{{ $v_lead->image }}" width=45 /></td>
                                                    <td>{{ $v_lead['seller']->name }}</td>
                                                    <td>
                                                        <span>{{ $v_lead->name }}</span>
                                                        <a href="{{ $v_lead->link }}" target="_blank"><i class="ti ti-link" data-bs-toggle="tooltip" title="Link"></i></a>
                                                    </td>
                                                    <td>{{ strval($v_lead->CountStock($v_lead->id)) }}</td>
                                                    <td>{{ $v_lead->Mapping($v_lead->id) }}</td>
                                                    <td>{{ $v_lead->CountLead($v_lead->id) }}</td>
                                                    <td>
                                                        @if ($v_lead->CountLead($v_lead->id) != 0)
                                                        <a data-id="{{ $v_lead->CheckStock($v_lead->id) }}" class="text-inverse pr-2 detail" data-bs-toggle="tooltip" title="Picking Product">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 1.4rem; height:1rem;">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 18.75 7.5-7.5 7.5 7.5"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 7.5-7.5 7.5 7.5"></path>
                                                            </svg>
                                                        </a>
                                                        @endif
                                                        <a href="{{ route('leads.pickingorder', $v_lead->id) }}" class="text-inverse pr-2 " data-bs-toggle="tooltip" title="Pick one order">
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="12" width="12" viewBox="0 0 512 512">
                                                                <!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                                <path fill="#000000" d="M48.5 224L40 224c-13.3 0-24-10.7-24-24L16 72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8L48.5 224z"/>
                                                            </svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php $counter++; ?>
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <!-- End Page wrapper  -->
        <div id="editsheet" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" style="max-width:500px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Picking Product</h4>
                    </div>
                    <from class="form-horizontal form-material">
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <div class="text-center">
                                    <div class="form-group d-flex flex-column gap-3">
                                        <div class="col-md-12 col-sm-12 m-b-20">
                                            <input type="text" class="form-control" id="name_product"
                                                placeholder="Name Product">
                                            <input type="hidden" id="barc_cod_stock" />
                                        </div>
                                        <div class="col-md-12 col-sm-12 m-b-20">
                                            <input type="text" class="form-control" id="scan_tagier"
                                                placeholder="Scan Shelf">
                                        </div>
                                        <div class="col-md-12 col-sm-12 m-b-20">
                                            <input type="number" value="1" name="demo3" id="quantity"
                                                class="form-control" placeholder="quantity">
                                        </div>
                                        {{-- <div class="col-md-12 col-sm-12 m-b-20">
                                                <form class="mt-3">
                                                    <input  type="number" >
                                                </form>
                                            </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer text-center">
                            <button type="submit" class="btn btn-primary waves-effect editlead" disabled
                                id="editsheets">Confirmed</button>
                        </div>
                    </from>
                </div>
            </div>
        </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $("#quantity").keyup(function() {
            // Check correct, else revert back to old value.
            if (!$(this).val() || (parseInt($(this).val()) <= 1000000 && parseInt($(this).val()) > 0));
            else
                $(this).val($(this).data("old"));
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
                    console.log(data);
                    $('#editsheet').modal('show');
                    $('#name_product').val(data.name);
                    $('#barc_cod_stock').val(data.bar_cod);
                }
            });
        });
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

            $('#editsheets').prop("disabled", true);
            $('#overlay').fadeIn();
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
                    if (response.outstock == false) {
                        toastr.error('Opss.', 'Product Not found In stock!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                    if (response.notedispo == false) {
                        toastr.error('Opss.', 'This Quantity Note Disponible In this Shelf!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                    if (response.quantityerorr == false) {
                        toastr.error('Opss.', 'This Quantity Note Correct!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                    if (response.success == true) {
                        toastr.success('Good Job.', 'Product  Has been Pick Success!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                        location.reload();
                    }
                }
            });
        });
    </script>
@endsection
