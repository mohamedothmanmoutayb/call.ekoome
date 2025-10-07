<div class="row">
    <div class="col-md-5">
        <h6>Selected Orders ({{ count($orders) }})</h6>
        <div class="selected-orders-list mb-3">
            @foreach ($orders->take(5) as $order)
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span>{{ $order->n_lead }}</span>
                    <span class="badge bg-secondary">{{ $order->lastMileCompany->name ?? 'None' }}</span>
                </div>
            @endforeach
            @if (count($orders) > 5)
                <div class="text-center text-muted mt-2">
                    + {{ count($orders) - 5 }} more orders
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-7">
        <div class="mb-3">
            <label class="form-label">Last Mile Company</label>
            <select class="form-select" id="modalLastMileSelect">
                <option value="">Select Last Mile</option>
                @foreach ($lastMileCompanies as $company)
                    <option value="{{ $company->id }}" data-name="{{ $company->name }}">
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3" id="businessContainer" style="display: none;">
            <label class="form-label">Business Account</label>
            <select class="form-select" id="modalBusinessSelect">
                <option value="">Loading businesses...</option>
            </select>
        </div>

        <!-- Dynamic Fields Container -->
        <div id="providerSpecificFields"></div>

        <div class="action-buttons mt-4">
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-primary" id="createOrderBtn">
                    <i class="ti ti-send"></i> Create Order
                </button>
                <button class="btn btn-info" id="checkOrderBtn">
                    <i class="ti ti-check"></i> Check Status
                </button>
                <button class="btn btn-success" id="waybillOrderBtn">
                    <i class="ti ti-printer"></i> Generate Waybill
                </button>
                <button class="btn btn-danger" id="cancelOrderBtn">
                    <i class="ti ti-trash"></i> Cancel Order
                </button>
            </div>
        </div>
    </div>
</div>

<div id="apiResponse" class="mt-3"></div>

<script>
    $(document).ready(function() {
        const orderIds = {{ json_encode($orders->pluck('id')) }};
        let selectedLastMile = null;
        let selectedBusiness = null;
        let providerFields = {};

        $('#modalLastMileSelect').change(function() {
            const lastMileId = $(this).val();
            const lastMileName = $(this).find('option:selected').data('name');

            selectedLastMile = {
                id: lastMileId,
                name: lastMileName,
            };

            if (!lastMileId) {
                $('#businessContainer').hide();
                $('#providerSpecificFields').empty();
                return;
            }

            loadBusinesses(lastMileId);
            loadProviderSpecificFields(lastMileName);
        });

        function loadBusinesses(lastMileId) {
            const sellerId = $('#sellerSelect').val();
            $('#businessContainer').show();
            $('#modalBusinessSelect').html('<option value="">Loading businesses...</option>');

            $.get("{{ route('lastmile.businesses') }}", {
                lastMileId: lastMileId,
                sellerId: sellerId

            }, function(response) {
                if (response.data && response.data.length > 0) {
                    let options = '<option value="">Select Business</option>';
                    response.data.forEach(business => {
                        options += `
                        <option value="${business.id}" 
                                data-auth-key="${business.auth_key}">
                            ${business.name}
                        </option>`;
                    });
                    $('#modalBusinessSelect').html(options);
                } else {
                    $('#modalBusinessSelect').html('<option value="">No businesses found</option>');
                }
            }).fail(function() {
                $('#modalBusinessSelect').html('<option value="">Error loading businesses</option>');
                toastr.error('Failed to load businesses');
            });
        }

        function loadProviderSpecificFields(providerName) {
            $('#providerSpecificFields').empty();

            if (providerName === 'DIGYLOG') {
                loadDigylogFields();
            }
        }

        // Digylog specific fields
        function loadDigylogFields() {
            $('#providerSpecificFields').html(`
            <div class="digylog-fields">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Network</label>
                        <select class="form-select" id="digylogNetwork">
                            <option value="">Loading networks...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Store</label>
                        <select class="form-select" id="digylogStore">
                            <option value="">Loading stores...</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Order Mode</label>
                        <select class="form-select" id="digylogMode">
                            <option value="1">Standard Order</option>
                            <option value="2">Fulfillment Order</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Order Status</label>
                        <select class="form-select" id="digylogStatus">
                            <option value="0">Add but don't send</option>
                            <option value="1" selected>Add & send immediately</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Port</label>
                        <select class="form-select" id="digylogPort">
                            <option value="1">Fees paid by seller</option>
                            <option value="2">Fees paid by customer</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Open Product</label>
                        <select class="form-select" id="digylogOpenProduct">
                            <option value="1">Allow open product</option>
                            <option value="2">Don't allow open product</option>
                        </select>
                    </div>
                </div>
            </div>
        `);

            $('#modalBusinessSelect').change(function() {
                const businessId = $(this).val();
                const authKey = $(this).find('option:selected').data('auth-key');
                selectedBusiness = businessId;

                if (businessId && authKey && selectedLastMile.name === 'DIGYLOG') {
                    loadDigylogNetworks(authKey);
                    loadDigylogStores(authKey);
                }
            });
        }

        function loadDigylogNetworks(authKey) {
            $('#digylogNetwork').html('<option value="">Loading networks...</option>');

            $.get("{{ route('digylog.networks') }}", {
                auth_key: authKey
            }, function(response) {
                if (response.success && response.data) {
                    let options = '<option value="">Select Network</option>';
                    if (Array.isArray(response.data)) {
                        response.data.forEach(network => {
                            options += `<option value="${network.id}">${network.name}</option>`;
                        });
                    } else if (typeof response.data === 'object' && response.data !== null) {
                        options += `<option value="${response.data.id}">${response.data.name}</option>`;
                    }
                    $('#digylogNetwork').html(options);
                } else {
                    $('#digylogNetwork').html('<option value="">No networks found</option>');
                }
            }).fail(function() {
                $('#digylogNetwork').html('<option value="">Error loading networks</option>');
                toastr.error('Failed to load Digylog networks');
            });
        }

        function loadDigylogStores(authKey) {
            $('#digylogStore').html('<option value="">Loading stores...</option>');

            $.get("{{ route('digylog.stores') }}", {
                auth_key: authKey
            }, function(response) {
                if (response.success && response.data) {
                    let options = '<option value="">Select Store</option>';
                    if (Array.isArray(response.data)) {
                        response.data.forEach(store => {
                            options += `<option value="${store.name}">${store.name}</option>`;
                        });
                    } else if (typeof response.data === 'object' && response.data !== null) {
                        options +=
                            `<option value="${response.data.name}">${response.data.name}</option>`;
                    }
                    $('#digylogStore').html(options);
                } else {
                    $('#digylogStore').html('<option value="">No stores found</option>');
                }
            }).fail(function() {
                $('#digylogStore').html('<option value="">Error loading stores</option>');
                toastr.error('Failed to load Digylog stores');
            });
        }

        $('#createOrderBtn').click(function() {
            if (!validateSelections()) return;

            if (selectedLastMile.name === 'DIGYLOG') {
                createDigylogOrder();
            } else {
                createGenericOrder();
            }
        });

        function validateSelections() {
            if (!selectedLastMile || !selectedLastMile.id) {
                toastr.error('Please select a last mile company');
                return false;
            }

            if (!selectedBusiness) {
                toastr.error('Please select a business account');
                return false;
            }

            if (selectedLastMile.name === 'DIGYLOG') {
                if (!$('#digylogNetwork').val() || !$('#digylogStore').val()) {
                    toastr.error('Please select network and store for Digylog');
                    return false;
                }
            }

            return true;
        }

        function createDigylogOrder() {
            const btn = $('#createOrderBtn');
            const originalText = btn.html();
            const authKey = $('#modalBusinessSelect').find('option:selected').data('auth-key');

            btn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            Creating Digylog Order...
        `);

            $('#apiResponse').empty().hide();

            const orderData = {
                network: $('#digylogNetwork').val(),
                store: $('#digylogStore').val(),
                mode: $('#digylogMode').val(),
                status: $('#digylogStatus').val(),
                orders: []
            };

            $.get("{{ route('digylog.details') }}", {
                order_ids: orderIds.join(',')
            }, function(response) {
                if (response.success && response.data) {
                    response.data.forEach(order => {
                        const products = order.products.map(product => {
                            return {
                                designation: product.name,
                                quantity: product.quantity
                            };
                        });

                        orderData.orders.push({
                            num: order.n_lead,
                            type: 1,
                            name: order.customer_name,
                            phone: order.customer_phone,
                            address: order.customer_address,
                            city: order.customer_city,
                            price: order.total_price,
                            refs: products,
                            note: order.notes || '',
                            port: $('#digylogPort').val(),
                            openproduct: $('#digylogOpenProduct').val(),
                        });
                    });

                    $.post("{{ route('digylog.createOrder') }}", {
                        order_data: orderData,
                        business_id: selectedBusiness,
                        auth_key: authKey,
                        _token: '{{ csrf_token() }}'
                    }, function(response) {
                        btn.html(originalText).prop('disabled', false);

                        if (response.success) {
                            showSuccessResponse('Orders created successfully with Digylog',
                                response.tracking_numbers);
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showErrorResponse(response.message ||
                                'Failed to create Digylog order');
                        }
                    }).fail(function(xhr) {
                        btn.html(originalText).prop('disabled', false);
                        showErrorResponse(xhr.responseJSON?.message ||
                            'An error occurred while creating Digylog order');
                    });
                } else {
                    btn.html(originalText).prop('disabled', false);
                    showErrorResponse('Failed to prepare order data');
                }
            }).fail(function() {
                btn.html(originalText).prop('disabled', false);
                showErrorResponse('Error fetching order details');
            });
        }

        function createGenericOrder() {
            const btn = $('#createOrderBtn');
            const originalText = btn.html();

            btn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            Creating Order...
        `);

            $('#apiResponse').empty().hide();

            $.post("{{ route('products.apiorder') }}", {
                type: 'CreateOrder',
                list_ids: orderIds.join(','),
                lastmille: selectedLastMile.id,
                bussines: selectedBusiness,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                btn.html(originalText).prop('disabled', false);

                if (response.success) {
                    showSuccessResponse(`Orders created successfully with ${selectedLastMile.name}`);
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showErrorResponse(response.message || 'Operation failed');
                }
            }).fail(function(xhr) {
                btn.html(originalText).prop('disabled', false);
                showErrorResponse(xhr.responseJSON?.message ||
                    'An error occurred while processing your request');
            });
        }

        function showSuccessResponse(message, trackingNumbers = null) {
            let html = `<div class="alert alert-success"><i class="ti ti-check"></i> ${message}`;

            if (trackingNumbers && trackingNumbers.length > 0) {
                html += `<br>Tracking: ${trackingNumbers.join(', ')}`;
            }

            html += `</div>`;

            $('#apiResponse').html(html).fadeIn();
            toastr.success(message);
        }

        function showErrorResponse(message) {
            $('#apiResponse').html(`
            <div class="alert alert-danger">
                <i class="ti ti-alert-triangle"></i> ${message}
            </div>
        `).fadeIn();
            toastr.error(message);
        }

        $('#checkOrderBtn, #waybillOrderBtn, #cancelOrderBtn').click(function() {
            const action = $(this).attr('id').replace('Btn', '');
            const actionMap = {
                'checkOrder': 'CheckeOrder',
                'waybillOrder': 'WaybillOrder',
                'cancelOrder': 'CancelOrder'
            };

            if (!validateSelections()) return;

            const btn = $(this);
            const originalText = btn.html();

            btn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            Processing...
        `);

            $('#apiResponse').empty().hide();

            $.post("{{ route('products.apiorder') }}", {
                type: actionMap[action],
                list_ids: orderIds.join(','),
                lastmille: selectedLastMile.id,
                bussines: selectedBusiness,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                btn.html(originalText).prop('disabled', false);

                if (response.success) {
                    let successMessage = '';

                    switch (actionMap[action]) {
                        case 'CheckeOrder':
                            successMessage = 'Order status checked successfully';
                            break;
                        case 'WaybillOrder':
                            successMessage = 'Waybills generated successfully';
                            if (response.download_url) {
                                window.open(response.download_url, '_blank');
                            }
                            break;
                        case 'CancelOrder':
                            successMessage = 'Orders cancelled successfully';
                            setTimeout(() => location.reload(), 2000);
                            break;
                    }

                    showSuccessResponse(successMessage);
                } else {
                    showErrorResponse(response.message || 'Operation failed');
                }
            }).fail(function(xhr) {
                btn.html(originalText).prop('disabled', false);
                showErrorResponse(xhr.responseJSON?.message ||
                    'An error occurred while processing your request');
            });
        });
    });
</script>
