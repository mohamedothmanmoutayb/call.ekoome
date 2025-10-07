@extends('backend.layouts.app')
@section('content')
    <style>
         #errorBox {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 450px;
            background-color: #fff; /* Changed to white */
            color: #333; /* Dark text */
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transform: translateY(100px);
            opacity: 0;
            pointer-events: none;
            transition: all 0.5s ease;
            z-index: 9999;
            font-family: Arial, sans-serif;
        }
        .eror{
            background-color: #ffd7d7;
            border-radius :15px ;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
            gap: 5px;
            padding: 15px 15px;
            color: #c55757;
        }
        #errorBox h4 {
            margin-bottom: 12px;
            color: #b91c1c; /* Dark red for header */
            font-size: 18px;
            font-weight: bold;
        }

        .errors {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .error-item {
            background: #ef4444; /* Tailwind red-500 */
            color: #eb4545;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
          
        }

        .error-item .code {
            font-size: 12px;
            opacity: 0.9;
            margin-left: 6px;
        }

        #overlay {
            background: rgba(255, 255, 255, 0.9);
            color: #666666;
            position: fixed;
            height: 100%;
            width: 100%;
            z-index: 5000;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .spinner {
            margin: 0 auto;
            height: 64px;
            width: 64px;
            animation: rotate 0.8s infinite linear;
            border: 5px solid #3f80ea;
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

        .order-status-badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        .selected-orders-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 10px;
            background-color: #f9f9f9;
        }

        .action-buttons .btn {
            min-width: 150px;
            margin-bottom: 8px;
        }

        #apiResponse {
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .action-buttons .btn {
                width: 100%;
            }
        }
    </style>

    <!-- Loading Overlay -->
    <div id="overlay" style="display:none;">
        <div class="spinner"></div>
        <div class="mt-3">Processing your request...</div>
    </div>

    <!-- Page Header -->
    <div class="card card-body py-3">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                    <h4 class="mb-4 mb-sm-0 card-title">Order Packing Station</h4>
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

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Select Seller</label>
                                <select class="form-select" name="seller" id="sellerSelect" required>
                                    <option value="">Loading sellers...</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Last Mile Company</label>
                                <select class="form-select" name="last_mile" id="lastMileSelect" disabled>
                                    <option value="">Select seller first</option>
                                </select>
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <span id="filterButtonText">Filter Orders</span>
                                    <span id="filterSpinner" class="spinner-border spinner-border-sm ms-2 d-none"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <div class="flex-grow-1">
                                <p class="square-after f-w-600  dropdown-toggle show" type="button" id="btnGroupDrop1"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Select Action<i
                                        class="fa fa-circle"></i></p>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" bis_skin_checked="1"
                                    style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(0px, -44px, 0px);"
                                    data-popper-placement="top-start" data-popper-reference-hidden="">
                                    <a type="button" class="dropdown-item" id="exports">Export Data</a>
                                    <!-- <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white" data-toggle="modal" data-target="#delivery">Delivery Man</a> -->
                                    <!-- <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white" data-toggle="modal" data-target="#scans">Scan Orders Return</a> -->
                                    <a type="button" id="multiScan" class="dropdown-item">Multi Scan </a>
                                    <a type="button" class="dropdown-item" data-bs-toggle="modal"
                                        data-bs-target="#scan">Send Order For Delivrey</a>
                                </div>
                            </div>
                            <div class="setting-list">
                            </div>
                        </div>
                    </div>
                    <div id="ordersTableContainer">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary mb-3" role="status" id="initialSpinner">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h5>Please select a seller and last mile company to view orders</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Last Mile Modal -->
    <div id="lastMileModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Process with Last Mile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center my-5" id="modalLoading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="modalContent" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>

    @if($errors->count() > 0)
<div id="errorBox" class="max-w-md mx-auto mt-6 bg-white shadow-lg rounded-xl p-4 border ">
    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
        ⚠️ Errors
    </h4>
    <div class="space-y-2">
        @foreach($errors as $error)
            <div class="eror  rounded-lg shadow-sm mb-2 d-flex justify-content-between" data-id="{{ $error->id }}">
              <span> Lead n° <strong>{{ $error->id_lead }}</strong> : {{ $error->error }}</span> 
             

            <button class="delete-error"
                    data-id="{{ $error->id }}"
                    style="
                        width: 25px;
                        height: 25px;
                        font-size: 20px;
                        text-align: center;
                        line-height: 22px;
                        border-radius: 6px;
                        padding: 0;
                        border: none;
                        background-color: transparent;
                        color: rgb(168, 0, 0);
                        cursor: pointer;">
                    &times;
                </button>
            </div>
        @endforeach

    </div>
</div>
   
<script>
document.addEventListener("DOMContentLoaded", function() {
    const errorBox = document.getElementById("errorBox");
    if (errorBox) {
        errorBox.style.opacity = "1";
        errorBox.style.transform = "translateY(0)";
        errorBox.style.pointerEvents = "auto";
    }

    document.querySelectorAll(".delete-error").forEach(btn => {
        btn.addEventListener("click", function() {
            const item = this.closest(".eror");
            const errorId = item.getAttribute("data-id");

            console.log("Deleting error with ID:", errorId);
            fetch(`/errors/${errorId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Accept": "application/json"
                }
            })
            .then(res => {
                if (res.ok) {
                    item.style.transition = "opacity 0.3s ease, transform 0.3s ease";
                    item.style.opacity = "0";
                    item.style.transform = "translateX(50px)";
                     setTimeout(() => {
                        item.remove();

                       
                        if (errorBox.querySelectorAll(".eror").length === 0) {
                            errorBox.style.transition = "opacity 0.3s ease";
                            errorBox.style.opacity = "0";
                            setTimeout(() => errorBox.remove(), 300);
                        }
                    }, 300);
                } else {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Failed to delete error!'
                    });
                }
            })
            .catch(err => console.error(err));
        });
    });
});

</script>
@endif

    <!-- JavaScript -->
    
    <script>
        $(document).ready(function() {
            // Initialize toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": 5000
            };

            // Load sellers with orders matching the criteria
            function loadSellers() {
                $.ajax({
                    url: '{{ route('pack.getSellers') }}',
                    type: 'GET',
                    data: {
                        country_id: '{{ Auth::user()->country_id }}',
                        warehouse_id: '{{ Auth::user()->warehouse_id }}'
                    },
                    beforeSend: function() {
                        $('#sellerSelect').html('<option value="">Loading sellers...</option>');
                    },
                    success: function(response) {
                        if (response.sellers && response.sellers.length > 0) {
                            let options = '<option value="">Select Seller</option>';
                            response.sellers.forEach(seller => {
                                options +=
                                    `<option value="${seller.id}">${seller.name} (${seller.seller_leads_count} orders)</option>`;
                            });
                            $('#sellerSelect').html(options);
                        } else {
                            $('#sellerSelect').html(
                                '<option value="">No sellers with matching orders found</option>');
                        }
                    },
                    error: function() {
                        $('#sellerSelect').html('<option value="">Error loading sellers</option>');
                        toastr.error('Failed to load sellers. Please try again.');
                    }
                });
            }

            // When seller changes, load their last mile companies
            $('#sellerSelect').change(function() {
                const sellerId = $(this).val();
                $('#lastMileSelect').prop('disabled', !sellerId);

                if (!sellerId) {
                    $('#lastMileSelect').html('<option value="">Select seller first</option>');
                    $('#ordersTableContainer').html(`
                        <div class="text-center py-5">
                            <h5>Please select a seller to view orders</h5>
                        </div>
                    `);
                    return;
                }

                $.ajax({
                    url: '{{ route('pack.getSellerLastMiles') }}',
                    type: 'GET',
                    data: {
                        seller_id: sellerId
                    },
                    beforeSend: function() {
                        $('#lastMileSelect').html(
                            '<option value="">Loading last mile companies...</option>');
                    },
                    success: function(response) {
                        if (response.lastMiles && response.lastMiles.length > 0) {
                            let options = '<option value="">All Last Mile Companies</option>';
                            response.lastMiles.forEach(company => {
                                // options +=
                                //     `<option value="${company.id}">${company.name}</option>`;

                                options += `<option value="${company.id}">${company.name} (${company.orders_count} orders)</option>`;

                            });
                            $('#lastMileSelect').html(options);
                        } else {
                            $('#lastMileSelect').html(
                                '<option value="">No last mile companies found for this seller</option>'
                            );
                        }
                    },
                    error: function() {
                        $('#lastMileSelect').html(
                            '<option value="">Error loading last mile companies</option>');
                        toastr.error('Failed to load last mile companies. Please try again.');
                    }
                });
            });

            // Filter form submission
            $('#filterForm').submit(function(e) {
                e.preventDefault();

                const sellerId = $('#sellerSelect').val();
                const lastMileId = $('#lastMileSelect').val();

                if (!sellerId) {
                    toastr.error('Please select a seller first');
                    return;
                }

                loadOrders(sellerId, lastMileId);
            });

            // Function to load orders
            function loadOrders(sellerId, lastMileId) {
                $.ajax({
                    url: '{{ route('pack.getOrders') }}',
                    type: 'GET',
                    data: {
                        seller_id: sellerId,
                        last_mile_id: lastMileId
                    },
                    beforeSend: function() {
                        $('#filterButtonText').text('Loading...');
                        $('#filterSpinner').removeClass('d-none');
                        $('#ordersTableContainer').html(`
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary mb-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <h5>Loading orders...</h5>
                            </div>
                        `);
                    },
                    success: function(response) {
                        $('#filterButtonText').text('Filter Orders');
                        $('#filterSpinner').addClass('d-none');

                        if (response.orders && response.orders.data && response.orders.data.length >
                            0) {
                            renderOrdersTable(response.orders);
                        } else {
                            $('#ordersTableContainer').html(`
                                <div class="text-center py-5">
                                    <i class="ti ti-package-off fs-1 text-muted mb-3"></i>
                                    <h5>No orders found for the selected criteria</h5>
                                    <p class="text-muted">Try selecting different filters</p>
                                </div>
                            `);
                        }
                    },
                    error: function() {
                        $('#filterButtonText').text('Filter Orders');
                        $('#filterSpinner').addClass('d-none');
                        $('#ordersTableContainer').html(`
                            <div class="text-center py-5">
                                <i class="ti ti-alert-triangle fs-1 text-danger mb-3"></i>
                                <h5>Error loading orders</h5>
                                <p class="text-muted">Please try again later</p>
                            </div>
                        `);
                        toastr.error('Failed to load orders. Please try again.');
                    }
                });
            }

            // Function to render orders table
            function renderOrdersTable(orders) {
                let tableHtml = `
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="40">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAllOrders">
                                        </div>
                                    </th>
                                    <th>Lead Number</th>
                                    <th>Tracking</th>
                                    <th>Customer</th>
                                    <th>Products</th>
                                    <th>Last Mile</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                orders.data.forEach(order => {
                    tableHtml += `
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input order-checkbox" name="ids" type="checkbox" value="${order.id}">
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">${order.n_lead}</span>
                            </td>
                            <td>
                                ${order.tracking ? `
                                <span class="badge bg-success">${order.tracking}</span>
                                ` : '<span class="badge bg-primary">No Tracking</span>'}
                            </td>
                            <td>${order.name}</td>
                            <td>
                                ${order.leadproduct.map(product =>
                                 `
                                                                                                                <div class="d-flex align-items-center mb-1">
                                                                                                                    <div class="flex-shrink-0 me-2">
                                                                                                                        <img style="width:65px;" src="${product.product[0].image || '/assets/images/default-product.png'}" 
                                                                                                                             alt="${product.product[0].name}" 
                                                                                                                             class="avatar-xs rounded">
                                                                                                                    </div>
                                                                                                                    <div class="flex-grow-1">
                                                                                                                        <h6 class="mb-0">${product.product[0].name}</h6>
                                                                                                                        <small class="text-muted">Qty: ${product.quantity}</small>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            `).join('')}
                            </td>
                            <td>
                                ${order.shippingcompany ? `
                                                                                                                <span class="badge bg-info">${order.shippingcompany.name}</span>
                                                                                                            ` : '<span class="badge bg-secondary">Not assigned</span>'}
                            </td>
                            <td>
                                <span class="order-status-badge badge bg-${getStatusColor(order.status_livrison)}">
                                    ${formatStatus(order.status_livrison)}
                                </span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" 
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="/leads/edit/${order.id}">
                                                <i class="ti ti-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/leads/${order.id}/rollback">
                                                <i class="ti ti-arrow-back me-2"></i> Roll Back
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="/leads/${order.id}/print" target="_blank">
                                                <i class="ti ti-printer me-2"></i> Print
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                tableHtml += `
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing ${orders.from} to ${orders.to} of ${orders.total} entries
                            </div>
                            <ul class="pagination pagination-rounded mb-0">
                                ${generatePaginationLinks(orders)}
                            </ul>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-3 action-buttons">
                            <div>
                                <button class="btn btn-outline-primary" id="printSelected">
                                    <i class="ti ti-printer me-1"></i> Print Selected
                                </button>
                                <button class="btn btn-outline-secondary ms-2" id="exportSelected">
                                    <i class="ti ti-download me-1"></i> Export Selected
                                </button>
                            </div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#lastMileModal" id="processWithLastMileBtn">
                                <i class="ti ti-truck me-1"></i> Process with Last Mile
                            </button>
                        </div>
                    </div>
                `;

                $('#ordersTableContainer').html(tableHtml);

                // Initialize select all checkbox
                $('#selectAllOrders').change(function() {
                    $('.order-checkbox').prop('checked', $(this).prop('checked'));
                    toggleProcessButton();
                });

                // Initialize individual checkboxes
                $('.order-checkbox').change(function() {
                    if (!$(this).prop('checked')) {
                        $('#selectAllOrders').prop('checked', false);
                    } else if ($('.order-checkbox:checked').length === $('.order-checkbox').length) {
                        $('#selectAllOrders').prop('checked', true);
                    }
                    toggleProcessButton();
                });

                // Initialize process button state
                toggleProcessButton();
            }

            // Helper function to generate pagination links
            function generatePaginationLinks(paginator) {
                let links = '';

                // Previous page link
                links += paginator.prev_page_url ? `
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${paginator.current_page - 1}">
                            <i class="ti ti-chevron-left"></i>
                        </a>
                    </li>
                ` : `
                    <li class="page-item disabled">
                        <a class="page-link" href="#">
                            <i class="ti ti-chevron-left"></i>
                        </a>
                    </li>
                `;

                // Page links
                for (let i = 1; i <= paginator.last_page; i++) {
                    links += `
                        <li class="page-item ${i === paginator.current_page ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                }

                // Next page link
                links += paginator.next_page_url ? `
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="${paginator.current_page + 1}">
                            <i class="ti ti-chevron-right"></i>
                        </a>
                    </li>
                ` : `
                    <li class="page-item disabled">
                        <a class="page-link" href="#">
                            <i class="ti ti-chevron-right"></i>
                        </a>
                    </li>
                `;

                return links;
            }

            // Helper function for status colors
            function getStatusColor(status) {
                const statusColors = {
                    'picking process': 'warning',
                    'processing': 'info',
                    'picking proccess': 'primary',
                    'confirmed': 'success',
                    'cancelled': 'danger'
                };
                return statusColors[status.toLowerCase()] || 'secondary';
            }

            // Helper function to format status text
            function formatStatus(status) {
                const statusMap = {
                    'picking process': 'Picking',
                    'processing': 'Processing',
                    'picking proccess': 'Picking',
                    'confirmed': 'Confirmed',
                    'cancelled': 'Cancelled'
                };
                return statusMap[status.toLowerCase()] || status;
            }

            // Toggle process button based on selection
            function toggleProcessButton() {
                const selectedCount = $('.order-checkbox:checked').length;
                $('#processWithLastMileBtn').prop('disabled', selectedCount === 0);
                $('#processWithLastMileBtn').html(`
                    <i class="ti ti-truck me-1"></i> 
                    ${selectedCount > 0 ? `Process (${selectedCount})` : 'Process with Last Mile'}
                `);
            }

            // Last Mile Modal handling
            $('#lastMileModal').on('show.bs.modal', function() {
                const selectedOrders = $('.order-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedOrders.length === 0) {
                    toastr.error('Please select at least one order');
                    return false;
                }

                $.ajax({
                    url: '{{ route('pack.lastMileModal') }}',
                    type: 'GET',
                    data: {
                        order_ids: selectedOrders
                    },
                    beforeSend: function() {
                        $('#modalLoading').show();
                        $('#modalContent').hide().empty();
                    },
                    success: function(response) {
                        document.getElementById('modalLoading').style.setProperty('display',
                            'none', 'important');
                        $('#modalContent').html(response).fadeIn();
                    },
                    error: function() {
                        $('#modalLoading').hide();
                        $('#modalContent').html(`
                            <div class="alert alert-danger">
                                Error loading last mile options. Please try again.
                            </div>
                        `).fadeIn();
                    }
                });
            });

            // Pagination click handler
            $(document).on('click', '.page-link[data-page]', function(e) {
                e.preventDefault();
                const page = $(this).data('page');
                const sellerId = $('#sellerSelect').val();
                const lastMileId = $('#lastMileSelect').val();

                if (sellerId) {
                    loadOrders(sellerId, lastMileId, page);
                }
            });

            $('#multiScan').click(function(e) {
                e.preventDefault();
                var list_ids = [];


                $("input:checkbox[name=ids]:checked").each(function() {
                    list_ids.push($(this).val());
                });

                $('#overlay').fadeIn();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('products.multisendforshipped') }}',
                    data: {
                        ids: list_ids,
                    },
                    success: function(response) {
                        $('#overlay').hide();
                        if (response.success) {
                            toastr.success('Good Job.',
                                'Orders Has been Send For Delivrey Success!', {
                                    "showMethod": "slideDown",
                                    "hideMethod": "slideUp",
                                    timeOut: 2000
                                });
                        }
                        location.reload();
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
            });

            // Print selected orders
            $(document).on('click', '#printSelected', function() {
                const selectedOrders = $('.order-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedOrders.length === 0) {
                    toastr.error('Please select at least one order to print');
                    return;
                }

                window.open('/leads/print-selected?ids=' + selectedOrders.join(','), '_blank');
            });

            // Export selected orders
            $(document).on('click', '#exportSelected', function() {
                const selectedOrders = $('.order-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedOrders.length === 0) {
                    toastr.error('Please select at least one order to export');
                    return;
                }

                $('#overlay').fadeIn();
                window.location = '/leads/export-selected?ids=' + selectedOrders.join(',');
            });

            // Initial load
            loadSellers();
        });
    </script>
@endsection
