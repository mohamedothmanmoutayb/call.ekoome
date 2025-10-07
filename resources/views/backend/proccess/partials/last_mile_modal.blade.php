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
                    <option value="{{ $company->id }}" data-name="{{ $company->name }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3" id="businessContainer" style="display: none;">
            <label class="form-label">Business Account</label>
            <select class="form-select" id="modalBusinessSelect">
                <option value="">Loading businesses...</option>
            </select>
        </div>

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
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x"></i> Close
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

        $('#modalLastMileSelect').change(function() {
            const lastMileId = $(this).val();
            const lastMileName = $(this).find('option:selected').data('name');
            selectedLastMile = {
                id: lastMileId,
                name: lastMileName
            };

            if (!lastMileId) {
                $('#businessContainer').hide();
                return;
            }

            loadBusinesses(lastMileId);
        });

        $('#modalBusinessSelect').change(function() {
            selectedBusiness = $(this).val();
        });

        function loadBusinesses(lastMileId) {
            const sellerId = $('#sellerSelect').val();

            $('#businessContainer').show();
            $('#modalBusinessSelect').html('<option value="">Loading businesses...</option>');

            $.ajax({
                url: '{{ route('lastmile.businesses') }}',
                type: 'GET',
                data: {
                    lastMileId: lastMileId,
                    sellerId: sellerId
                },
                success: function(response) {
                    if (response.data && response.data.length > 0) {
                        let options = '<option value="">Select Business</option>';
                        response.data.forEach(business => {
                            options +=
                                `<option value="${business.id}">${business.name}</option>`;
                        });
                        $('#modalBusinessSelect').html(options);
                    } else {
                        $('#modalBusinessSelect').html(
                            '<option value="">No businesses found</option>');
                    }
                },
                error: function() {
                    $('#modalBusinessSelect').html(
                        '<option value="">Error loading businesses</option>');
                    toastr.error('Failed to load businesses for this seller and last mile');
                }
            });
        }

        $('#createOrderBtn').click(function() {
            $('#overlay').fadeIn();
            if (!validateSelections()) return;
            processOrder('CreateOrder');
        });

        $('#checkOrderBtn').click(function() {
            if (!validateSelections()) return;
            processOrder('CheckeOrder');
        });

        $('#waybillOrderBtn').click(function() {
            if (!validateSelections()) return;
            processOrder('WaybillOrder');
        });

        $('#cancelOrderBtn').click(function() {
            if (!validateSelections()) return;
            processOrder('CancelOrder');
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

            return true;
        }

        function processOrder(actionType) {
            const btn = $('#' + actionType.toLowerCase() + 'Btn');
            const originalText = btn.html();
            const sellerId = $('#sellerSelect').val();

            btn.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Processing...
            `);

            $('#apiResponse').empty().hide();

            $.ajax({
                url: '{{ route('products.apiorder') }}',
                type: 'POST',
                data: {
                    type: actionType,
                    list_ids: orderIds.join(','),
                    lastmille: selectedLastMile.id,
                    bussines: selectedBusiness,
                    seller_id: sellerId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    btn.html(originalText).prop('disabled', false);

                    if (response.success) {
                        let message = '';

                        switch (actionType) {
                            case 'CreateOrder':
                                message = 'Orders created successfully with ' + selectedLastMile
                                    .name;
                                break;
                            case 'CheckeOrder':
                                message = 'Order status checked successfully';
                                break;
                            case 'WaybillOrder':
                                message = 'Waybills generated successfully';
                                if (response.download_url) {
                                    window.open(response.download_url, '_blank');
                                }
                                break;
                            case 'CancelOrder':
                                message = 'Orders cancelled successfully';
                                break;
                        }

                        $('#apiResponse').html(`
                            <div class="alert alert-success">
                                <i class="ti ti-check"></i> ${message}
                            </div>
                        `).fadeIn();

                        toastr.success(message);

                        if (actionType === 'CreateOrder' || actionType === 'CancelOrder') {
                            setTimeout(() => location.reload(), 2000);
                        }
                    } else {
                        $('#apiResponse').html(`
                            <div class="alert alert-danger">
                                <i class="ti ti-alert-triangle"></i> ${response.message || 'Operation failed'}
                            </div>
                        `).fadeIn();

                        toastr.error(response.message || 'Operation failed');
                    }
                },
                error: function(xhr) {
                    btn.html(originalText).prop('disabled', false);

                    let errorMessage = 'An error occurred while processing your request';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    $('#apiResponse').html(`
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-triangle"></i> ${errorMessage}
                        </div>
                    `).fadeIn();

                    toastr.error(errorMessage);
                }
            });
        }
    });
</script>
