@extends('backend.layouts.app')

@section('content')
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">

        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-8 align-self-center">
                        <h4 class="page-title"> <span class="text-muted fw-light">Dashboard /</span>Agent Performance</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <form class="row col-4">
                        <div class="col-8 align-self-center">
                            <div class='input-group mb-3'>
                                <input type='text' name="date" class="form-control timeseconds" />
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="ti ti-calendar py-1"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 align-self-center">
                            <div class='input-group mb-3'>
                                <button class="btn btn-primary" type="submit">Filter</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Earnings -->
            <!-- ============================================================== -->

            <div class="row">
                <!-- Column -->
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-body border-bottom">
                            <h4 class="card-title">Overview</h4>
                            <h5 class="card-subtitle">Total Earnings of the Month</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mt-2">
                                <!-- col -->
                                <div class="col-md-3 col-sm-12 col-lg-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2"><span class="text-orange display-5"><i
                                                    class="mdi mdi-square-inc-cash"></i></span></div>
                                        <div><span class="text-muted">CONFIRMED RATE</span>
                                            <h3 class="font-medium mb-0">{{ $rate_confirmed }}%</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- col -->
                                <!-- col -->
                                <div class="col-md-3 col-sm-12 col-lg-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2"><span class="text-primary display-5"><i
                                                    class="mdi mdi-cube-send"></i></span></div>
                                        <div><span class="text-muted">NO ANSWER RATE</span>
                                            <h3 class="font-medium mb-0">{{ $rate_noanswer }}%</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- col -->
                                <div class="col-md-3 col-sm-12 col-lg-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2"><span class="text-orange display-5"><i
                                                    class="mdi mdi-square-inc-cash"></i></span></div>
                                        <div><span class="text-muted">CANCELED RATEE</span>
                                            <h3 class="font-medium mb-0">{{ $rate_canceled }}%</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- col -->
                                <!-- col -->
                                <div class="col-md-3 col-sm-12 col-lg-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2"><span class="text-primary display-5"><i
                                                    class="mdi mdi-cube-send"></i></span></div>
                                        <div><span class="text-muted">UPSELL RATE</span>
                                            <h3 class="font-medium mb-0">{{ $upsel }}%</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- col -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <!-- Column -->
                <div class="col-sm-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mt-2">
                                <!-- col -->
                                <div class="col-md-6 col-sm-12 col-lg-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2"><span class="text-orange display-5"><i
                                                    class="mdi mdi-basket"></i></span></div>
                                        <div><span class="text-muted">LEADS QUANTITY</span>
                                            <h3 class="font-medium mb-0">{{ $total_lead }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- col -->
                                <!-- col -->
                                <div class="col-md-6 col-sm-12 col-lg-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2"><span class="text-primary display-5"><i
                                                    class="mdi mdi-checkbox-multiple-marked-circle"></i></span></div>
                                        <div><span class="text-muted">CONFIRMED ORDERS</span>
                                            <h3 class="font-medium mb-0">{{ $confirmed }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- col -->
                                <!-- col -->
                                <div class="col-md-6 col-sm-12 col-lg-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2"><span class="display-5"><i
                                                    class="mdi mdi-close-circle"></i></span></div>
                                        <div><span class="text-muted">CANCELED ORDERS</span>
                                            <h3 class="font-medium mb-0">{{ $canceled }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- col -->
                                <div class="col-md-6 col-sm-12 col-lg-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2"><span class="text-orange display-5"><i
                                                    class="mdi mdi-headset-off"></i></span></div>
                                        <div><span class="text-muted">NO ANSWER</span>
                                            <h3 class="font-medium mb-0">{{ $noanswer }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <!-- col -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table-responsive table table-bordered m-t-30 table-hover contact-list "
                                    style="height:300px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>SKU</th>
                                            <th>Image</th>
                                            <th>Link</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 1;
                                        ?>
                                        @foreach ($leadss as $v_product)
                                            <tr>
                                                <td>{{ $counter }}</td>
                                                <td>{{ $v_product->sku }}</td>
                                                <td><img src="{{ $v_product->image }}" alt="user" class="circle"
                                                        width="45" /></td>

                                                <td>{{ $v_product->link }}</td>
                                                <td>{{ $v_product->quantity }}</td>
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

            <div class="page-breadcrumb my-2">
                <div class="row">
                    <div class="col-10 align-self-center">
                        <h4 class="page-title">Leads</h4>
                        <div class="form-group mt-2 text-left">
                            <select id="pagination" class="form-control" style="width: 80px">
                                <option value="10" @if ($items == 10) selected @endif>10</option>
                                <option value="50" @if ($items == 50) selected @endif>50</option>
                                <option value="100" @if ($items == 100) selected @endif>100</option>
                                <option value="250" @if ($items == 250) selected @endif>250</option>
                                <option value="500" @if ($items == 500) selected @endif>500</option>
                                <option value="1000" @if ($items == 1000) selected @endif>1000</option>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="col-2 align-self-center">
                            <div class="form-group mb-0 text-right">
                                <button id="exportss" class="btn btn-primary btn-rounded  ">Export</button>
                            </div>
                        </div> -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
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
                                            <div class="col-md-3 col-sm-12 my-4">
                                                <input type="text" class="form-control" id="search_ref"
                                                    name="ref" placeholder="Ref">
                                            </div>
                                            <div class="col-md-3 col-sm-12 my-4">
                                                <input type="text" class="form-control" name="customer"
                                                    placeholder="Customer Name">
                                            </div>
                                            <div class="col-md-2 col-sm-12 my-4">
                                                <input type="text" class="form-control" name="phone1"
                                                    placeholder="Phone ">
                                            </div>
                                            <div class="col-md-2 col-sm-12 my-4">
                                                <select class="form-control" id="id_cit" name="city">
                                                    <option value="">Select City</option>
                                                    @foreach ($cities as $v_city)
                                                        <option value="{{ $v_city->id }}">{{ $v_city->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12 my-4">
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
                                            <div class="col-md-6 col-sm-12 my-4">
                                                <div class='input-group mb-3'>
                                                    <input type='text' name="date" value=" "
                                                        class="form-control timeseconds" id="timeseconds" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="ti-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row col-12">
                                                <div class="col-6 align-self-center">
                                                    <div class="form-group mb-2 text-right">
                                                        <button type="submit" class="btn btn-primary waves-effect"
                                                            style="width:100%">Search</button>
                                                    </div>
                                                </div>
                                                <div class="col-6 align-self-center">
                                                    <div class="form-group mb-2 text-right">
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
                            <div class="table-responsive">
                                <input type="hidden" id="id_agent" value="{{ $id }}" />
                                <table id="" class="table table-bordered table-striped table-hover contact-list"
                                    data-paging="true" data-paging-size="7">
                                    <thead>
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
                                            <th>Delivery Man</th>
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
                                                <td data-id="{{ $v_lead['id'] }}" id="detaillead" class="detaillead"
                                                    data-bs-toggle="tooltip">
                                                    {{ $v_lead['n_lead'] }}
                                                    <br>
                                                    @if (!empty($v_lead['leadproduct']))
                                                        <span
                                                            class="label label-info">+Upssel{{ $v_lead['leadproduct']->where('isupsell', '1')->count() }}</span>
                                                    @endif
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
                                                            class="label label-warning">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'confirmed')
                                                        <span
                                                            class="label label-success">{{ $v_lead['status_confirmation'] }}</span>
                                                    @elseif($v_lead['status_confirmation'] == 'canceled')
                                                        <span
                                                            class="label label-danger">{{ $v_lead['status_confirmation'] }}</span>
                                                    @else
                                                        <span
                                                            class="label label-process">{{ $v_lead['status_confirmation'] }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $v_lead['status_livrison'] }}</td>
                                                <td>
                                                    @foreach ($v_lead['livreur'] as $v_livreur)
                                                        {{ $v_livreur->name }}<br>{{ $v_livreur->telephone }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if ($v_lead['created_at'])
                                                        {{ $v_lead['created_at']->Format('Y-m-d') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ti ti-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu animated slideInUp"
                                                            x-placement="bottom-start"
                                                            style="position: absolute; will-change: transform; transform: translate3d(0px, 35px, 0px);margin-left: -56px !important;">
                                                            <a class="dropdown-item upsell"
                                                                data-id="{{ $v_lead['id'] }}"><i class="ti ti-plus"></i>
                                                                Add Upsell</a>
                                                            <a class="dropdown-item seehystory" id="seehystory"
                                                                data-id="{{ $v_lead['id'] }}"><i class="ti ti-eye"></i>
                                                                History</a>
                                                            <a class="dropdown-item"
                                                                href="{{ route('leads.edit', $v_lead['id']) }}"><i
                                                                    class="ti ti-eye"></i> Details</a>
                                                            <a class="dropdown-item " href="    "><i
                                                                    class="ti ti-edit"></i> relancer</a>
                                                        </div>
                                                    </div>
                                                </td>
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
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
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
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(function(e) {
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
        });
    </script>
    <script>
        document.getElementById('pagination').onchange = function() {
            var id = $('#id_agent').val();
            if (window.location.href == "https://call-center.FULFILLEMENT.com/Staff/performence/") {
                //alert(window.location.href);
                window.location = window.location.href + "?&items=" + this.value + "&ids=" + id;
            } else {
                window.location = window.location.href + "?&items=" + this.value + "&ids=" + id;
            }

        };
    </script>
@endsection
