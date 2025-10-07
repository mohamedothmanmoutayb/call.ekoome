@extends('backend.layouts.app')
@section('content')
    <div class="card card-body py-3">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">Orders</h4>
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
    <ul class="nav nav-pills p-3 mb-3 rounded align-items-center card flex-row">
        <li class="nav-item">
            <a href="javascript:void(0)" onclick="toggleText()"
                class="nav-link gap-6 note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 fs-11 active"
                id="all-category">
                <i class="ti ti-list fill-white"></i>
                <span class="d-none d-md-block fw-medium">Filter</span>
            </a>
        </li>
        <li class="nav-item ms-auto">
            <!-- <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white" data-toggle="modal" data-target="#delivery">Delivery Man</a> -->
            <a type="button" data-bs-toggle="modal" data-bs-target="#delivery" class="btn btn-primary nav-link gap-6 note-link d-flex align-items-center justify-content-center px-3 px-md-3 me-0 me-md-2 fs-11 active">
                <span class="d-none d-md-block fw-medium">Delivery Man</span>
            </a>
        </li>

        <div class="col-12 row form-group multi mt-2" id="multi">
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
                        <input type="text" class="form-control" name="phone1" value="{{ request()->input('phone1') }}"
                            placeholder="Phone ">
                    </div>
                    <div class="col-md-3 col-sm-12 m-b-20">
                        <select class="select2 form-control" id="id_cit" name="city">
                            <option value=" ">Select City</option>
                            @foreach ($cities as $v_city)
                                <option value="{{ $v_city->id }}">{{ $v_city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
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
                            @foreach ($products as $v_product)
                                <option value="{{ $v_product->id }}"
                                    {{ $v_product->id == request()->input('id_prod') ? 'selected' : '' }}>
                                    {{ $v_product->name }} / {{ $v_product->sku }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-4 align-self-center">
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary waves-effect btn-rounded m-t-10 mb-2"
                                style="width:100%">Search</button>
                        </div>
                    </div>
                    <div class="col-4 align-self-center">
                        <div class="form-group mb-0">
                            <a href="{{ route('leads.index') }}"
                                class="btn btn-primary waves-effect btn-rounded m-t-10 mb-2 " style="width:100%">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </ul>

    <div id="delivery" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Assigned Orders</h4>
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
                                <a type="button"  class="btn btn-primary" id="qrcodes">Send</a>
                            </div>
                        </div>
                    </from>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- List History Popup Model -->
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
    <!-- List change status Popup Model -->
    <div class="modal fade" id="OrderChangeStatus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leadNameTitle">Change Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="timeline" >
                        <input type="hidden" id="order-id" />
                        <select class="form-control" id="shipping-statu">
                            <option value="">Status Shipping</option>
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
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-primary" id="changestatu">Save</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- start Default Size Light Table -->
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <div class="flex-grow-1">
                    <select id="pagination" class="form-control" style="width:80px">
                        <option value="10" @if ($items == 10) selected @endif>10</option>
                        <option value="50" @if ($items == 50) selected @endif>50</option>
                        <option value="100" @if ($items == 100) selected @endif>100</option>
                        <option value="250" @if ($items == 250) selected @endif>250</option>
                        <option value="500" @if ($items == 500) selected @endif>500</option>
                        <option value="1000" @if ($items == 1000) selected @endif>1000</option>
                    </select>
                </div>
                <div class="setting-list">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex mb-2 align-items-center">
                <div class="ms-auto flex-shrink-0">
                </div>
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
                            <th>Tracking N°</th>
                            <th>Products</th>
                            <th>Name</th>
                            <th>City</th>
                            <th>Phone</th>
                            <th>Lead Value</th>
                            <th>Shipping</th>
                            <th>Payment</th>
                            <th>Created At</th>
                            {{-- <th>Action</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        ?>
                        @if (!$orders->isempty())
                            @foreach ($orders as $key => $v_lead)
                                <tr class="accordion-toggle data-item" data-id="{{ $v_lead['id'] }}">
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="ids"
                                                class="custom-control-input checkBoxClass" value="{{ $v_lead['id'] }}"
                                                id="pid-{{ $counter }}">
                                            <label class="custom-control-label" for="pid-{{ $counter }}"></label>
                                            {{-- {{ $v_lead['id_order'] }} --}}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $v_lead['n_lead'] }}</span>
                                    </td>
                                    <td>
                                        @if ($v_lead['tracking'] != null)
                                            <a href="javascript:void(0)" id="tracking">
                                                {{ Str::limit($v_lead['tracking'], 20) }}</a>
                                        @elseif ($v_lead['tracking'] == null)
                                            <a href="javascript:void(0)">No Tracking</a>
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
                                        @if ($v_lead['status_livrison'] == 'paid')
                                            <span class="badge bg-success">{{ $v_lead['status_livrison'] }}</span>
                                        @else
                                            <span class="badge bg-info">In Paid</span>
                                        @endif

                                    </td>
                                    <td>{{ $v_lead['created_at'] }}</td>
                                    {{-- <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle show" type="button"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i
                                                    class="icon-settings"></i></button>
                                            <div class="dropdown-menu" bis_skin_checked="1"
                                                style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(184px, -325.203px, 0px);"
                                                data-popper-placement="top-start">

                                                <a class="dropdown-item seehystory" id="seehystory" data-id="{{ $v_lead['id'] }}"> History</a>
                                                @if($v_lead['status_livrison'] != "delivered" && $v_lead['status_livrison'] != "returned")
                                                    <a class="dropdown-item shippingchange" data-id="{{ $v_lead['id']}}">Change Statu</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td> --}}
                                </tr>
                                <?php $counter = $counter + 1; ?>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">
                                    <div class="col-12">
                                        <img src="{{ asset('public/Empty-amico.svg') }}"
                                            style="margin-left: auto ; margin-right: auto; display: block;"
                                            width="500" />
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <span></span>
            {{ $orders->withQueryString()->links('vendor.pagination.courier') }}
        </div>

    </div>
    <!-- end Default Size Light Table -->
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="{{ asset('public/assets/libs/prismjs/prism.js') }}"></script>
    <!-- Page JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        function formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
            });
        }
    </script>
    <script type="text/javascript">


        //Export
        $(function(e) {
            $("#chkCheckAll").click(function() {
                $(".checkBoxClass").prop('checked', $(this).prop('checked'));
            });
            $('#qrcodes').click(function(e) {
                e.preventDefault();
                var allids = [];
                $("input:checkbox[name=ids]:checked").each(function() {
                    allids.push($(this).val());
                });
                var delivery = $("#livreur_id").val();
                if (allids != ''){
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('leads.assignedorder') }}',
                        cache: false,
                        data: {
                            ids: allids,
                            delivery: delivery,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success('Good Job.',
                                    'Orders Has been Send To Deliver Man Success!', {
                                        "showMethod": "slideDown",
                                        "hideMethod": "slideUp",
                                        timeOut: 2000
                                    });
                            }
                        }
                    });
                } else {
                    toastr.warning('Opss.', 'Please Selected Orders!', {
                        "showMethod": "slideDown",
                        "hideMethod": "slideUp",
                        timeOut: 2000
                    });
                }
            });

        });



        $('#searchdetai').click(function(e) {
            e.preventDefault();
            var n_lead = $('#search_2').val();
            $('#listlead').modal('show');
            //console.log(agent);
            $.ajax({
                type: "get",
                url: "{{ route('leads.leadsearch') }}",
                data: {
                    n_lead: n_lead,
                },
                success: function(data) {
                    $('#listleadss').html(data);
                }
            });
        });



        function toggleText() {
            var x = document.getElementById("multi");
            $('#timeseconds').val('');
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
    @verbatim
    <script type="text/javascript">
        $(function(e) {
            $('.shippingchange').click(function(e) {
                $('#order-id').val($(this).data('id'));
                $('#OrderChangeStatus').modal('show');
            });
        });
    </script>


    <script>
        $("#chkCheckAll").click(function() {
                $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });
        document.getElementById('pagination').onchange = function() {
            window.location = window.location.href + "?&items=" + this.value;

        };
        $('#sendBulkOffersBtn').click(function() {
            const selectedLeads = $('input[name="ids"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedLeads.length === 0) {
                toastr.warning('Please select at least one lead');
                return;
            }

            $('#selectedLeadsCount').text(selectedLeads.length);
            $('#bulkOffersModal').modal('show');
        });

        $('#bulkOfferTemplate').change(function() {
            const templateId = $(this).val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            
            if (!templateId) {
                $('#bulkVariablesContainer').empty();
                $('#bulkMessagePreview').text('Select a template to preview');
                return;
            }

            $.ajax({
                url: '/whatsapp-offers/get-template-details',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    id: templateId,
                },
                success: function(response) {
                    if (response.success) {
                        $('#bulkMessagePreview').html(response.template);
                        const variables = extractVariables(response.template);
                        generateBulkVariableInputs(variables);
                    }
                }
            });
        });

        function generateBulkVariableInputs(variables) {
            const container = $('#bulkVariablesContainer');
            container.empty();

            variables.forEach(variable => {
                const label = variable.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                const inputId = `bulk-var-${variable}`;
                
                const inputGroup = `
                    <div class="mb-3">
                        <label for="${inputId}" class="form-label">${label}</label>
                        <input type="text" class="form-control" 
                            id="${inputId}" name="${variable}" 
                            oninput="updateBulkPreview()">
                    </div>
                `;
                
                container.append(inputGroup);
            });
        }

        window.updateBulkPreview = function() {
            let template = $('#bulkMessagePreview').html();
            const variables = extractVariables(template);
            
            variables.forEach(variable => {
                const value = $(`#bulkOffersModal input[name="${variable}"]`).val() || '';
                const regex = new RegExp(`{{\\s*${variable}\\s*}}`, 'g');
                template = template.replace(regex, value);
            });
            
            $('#bulkMessagePreview').html(template);
        };

        $('#bulkOffersForm').submit(function(e) {
            e.preventDefault();
            
            const selectedLeads = $('input[name="ids"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedLeads.length === 0) {
                toastr.warning('Please select at least one lead');
                return;
            }

            const formData = $(this).serializeArray();
            formData.push({name: 'lead_ids', value: selectedLeads.join(',')});

            $.ajax({
                url: '/whatsapp-offers/send-bulk-offers',
                method: 'POST',
                data: formData,
                beforeSend: function() {
                    $('#bulkOffersForm button[type="submit"]').prop('disabled', true)
                        .prepend('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(`Offers sent successfully to ${response.sent_count} leads`);
                        $('#bulkOffersModal').modal('hide');
                    } else {
                        toastr.error(response.message || 'Failed to send offers');
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred while sending offers');
                },
                complete: function() {
                    $('#bulkOffersForm button[type="submit"]').prop('disabled', false)
                        .find('.spinner-border').remove();
                }
            });
        });

        $('#offerProduct').change(function() {
                const product = $(this).find(':selected');
                console.log()
                if (product.val()) {
                    $('input[name="product-name"]').val(product.text().split('(')[0].trim());
                    $('input[name="special-price"]').val(product.data('price'));
                    updatePreview();
                }
        });

        function extractVariables(template) {
            const regex = /{{\s*([^}\s]+)\s*}}/g;
            const variables = [];
            let match;
            
            while ((match = regex.exec(template)) !== null) {
                variables.push(match[1]);
            }
            
            return [...new Set(variables)];
        }
    </script>
    @endverbatim

@endsection
@endsection
