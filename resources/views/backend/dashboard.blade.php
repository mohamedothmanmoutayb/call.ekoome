@extends('backend.layouts.app')
@section('css')
    <style>
        .dashboard-default .our-user .card-body ul {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex !important;
            margin-top: 40px;
        }

        ul {
            padding-left: 0px;
            list-style-type: none;
            margin-bottom: 0;
        }

        .dashboard-default .our-user .card-body ul li {
            display: inline-block !important;
            width: 100% !important;
            text-align: center !important;
            position: relative !important;
        }

        .dashboard-default .our-user .card-body ul li+li::before {
            position: absolute;
            content: "";
            width: 1px;
            height: 25px;
            background-color: #2b2b2b;
            opacity: 0.1;
            top: 8px;
            left: 0;
        }

        @media screen and (max-width: 1770px) and (min-width: 1551px) {
            .dashboard-default .our-earning .card-footer ul.d-block {
                display: block !important;
            }
        }

        .full-page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(2px);
        }

        .loader-content {
            text-align: center;
        }

        .btn-outline-primary.active {
            background-color: var(--bs-primary);
            color: white;
            border-color: var(--bs-primary);
        }
        @media (max-width: 600px) {
            .first{
                width: 100%;
            }
            .filterh {
                transform: scale(0.7);
                margin-left: -14%;
                margin-top: -10px;
                
            }
           /* .chart-scroll-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch; 
            }

            .chart-wrapper {
                width: 100%;
            } */

            .row{
                width: 118%;
                margin-left: -8%;
            }
            .stat {
                   margin-left: 20%;   
             }
           
            
             }

  @media (max-width: 1000px) {
              .chart-scroll-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch; 
            }

            .chart-wrapper {
                width: 100%;
            }

        
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div id="fullPageLoader" class="full-page-loader d-none">
            <div class="loader-content">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mt-3">Loading Dashboard Data...</h5>
            </div>
        </div>
        <div class="col-12 mb-3 ">
            <div class="card first">
                <div class="card-body">
                    <form id="dashboardFilterForm" method="GET">
                        <div class="row align-items-end">
                            <div class="col-lg-2 col-md-4">
                                <div class="form-group">
                                    <label for="date_from">From Date</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from"
                                        value="{{ request('date_from') ?? date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <div class="form-group">
                                    <label for="date_to">To Date</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to"
                                        value="{{ request('date_to') ?? date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <div class="hstack gap-2 mt-2">
                                    <button type="submit" class="btn btn-primary">Apply</button>
                                    <a href="{{ route('home') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 mt-2">
                                <label for="button-grp">Quick Filters</label>
                                <div id="button-grp" class="btn-group w-100 filterh" role="group">
                                    <button type="button" class="btn btn-outline-primary quick-filter"
                                        data-from="{{ date('Y-m-d') }}" data-to="{{ date('Y-m-d') }}">
                                        Today
                                    </button>
                                    <button type="button" class="btn btn-outline-primary quick-filter"
                                        data-from="{{ date('Y-m-d', strtotime('-1 days')) }}" data-to="{{ date('Y-m-d') }}">
                                        Yesterday
                                    </button>
                                    <button type="button" class="btn btn-outline-primary quick-filter"
                                        data-from="{{ date('Y-m-d', strtotime('-7 days')) }}" data-to="{{ date('Y-m-d') }}">
                                        Last 7 Days
                                    </button>
                                    <button type="button" class="btn btn-outline-primary quick-filter"
                                        data-from="{{ date('Y-m-01') }}" data-to="{{ date('Y-m-t') }}">
                                        This Month
                                    </button>
                                    <?php
                                    $lastdate = Carbon\Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                                    $lastdate2 = Carbon\Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                                    ?>
                                    <button type="button" class="btn btn-outline-primary quick-filter"
                                        data-from="{{ $lastdate }}" data-to="{{ $lastdate2 }}">
                                        Last Month
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-4 pb-0" data-simplebar="">
                    <div class="row flex-wrap">
                        <div class="col-12 col-md mb-4">
                            <div class="card primary-gradient">
                                <div class="card-body text-center px-9 pb-4">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded text-bg-primary flex-shrink-0 mb-3 mx-auto">
                                        <iconify-icon icon="solar:dollar-minimalistic-linear"
                                            class="fs-7 text-white"></iconify-icon>
                                    </div>
                                    <h6 class="fw-normal fs-3 mb-1">Total Lead</h6>
                                    <h4 id="totalLeadsValue"
                                        class="mb-3 d-flex align-items-center justify-content-center gap-1">
                                        {{ $total }}</h4>
                                    <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                                        Details</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md mb-4">
                            <div class="card success-gradient">
                                <div class="card-body text-center px-9 pb-4">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded text-bg-success flex-shrink-0 mb-3 mx-auto">
                                        <iconify-icon icon="solar:recive-twice-square-linear"
                                            class="fs-7 text-white"></iconify-icon>
                                    </div>
                                    <h6 class="fw-normal fs-3 mb-1">Total Lead Confirmed</h6>
                                    <h4 id="confirmedLeadsValue"
                                        class="mb-3 d-flex align-items-center justify-content-center gap-1">
                                        {{ $confirmed }}</h4>
                                    <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                                        Details</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md mb-4">
                            <div class="card warning-gradient">
                                <div class="card-body text-center px-9 pb-4">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded text-bg-warning flex-shrink-0 mb-3 mx-auto">
                                        <iconify-icon icon="ic:outline-backpack" class="fs-7 text-white"></iconify-icon>
                                    </div>
                                    <h6 class="fw-normal fs-3 mb-1">Total Lead No Answer</h6>
                                    <h4 id="noAnswerLeadsValue"
                                        class="mb-3 d-flex align-items-center justify-content-center gap-1">
                                        {{ $noanswer }}</h4>
                                    <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                                        Details</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md mb-4">
                            <div class="card danger-gradient">
                                <div class="card-body text-center px-9 pb-4">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded text-bg-danger flex-shrink-0 mb-3 mx-auto">
                                        <iconify-icon icon="ic:baseline-sync-problem"
                                            class="fs-7 text-white"></iconify-icon>
                                    </div>
                                    <h6 class="fw-normal fs-3 mb-1">Total Lead Cancelled</h6>
                                    <h4 id="cancelledLeadsValue"
                                        class="mb-3 d-flex align-items-center justify-content-center gap-1">
                                        {{ $cancelled }}</h4>
                                    <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                                        Details</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md mb-4">
                            <div class="card secondary-gradient">
                                <div class="card-body text-center px-9 pb-4">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded text-bg-secondary flex-shrink-0 mb-3 mx-auto">
                                        <iconify-icon icon="ic:outline-forest" class="fs-7 text-white"></iconify-icon>
                                    </div>
                                    <h6 class="fw-normal fs-3 mb-1">Total Delivered</h6>
                                    <h4 id="deliveredLeadsValue"
                                        class="mb-3 d-flex align-items-center justify-content-center gap-1">
                                        {{ $totdelivered }}</h4>
                                    <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                                        Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h5 class="card-title">Confirmation Report</h5>
                            <p class="card-subtitle mb-0 charty">Total Confirmation by Hour</p>
                        </div>
                        <div class="hstack gap-9 mt-4 mt-md-0 charty">
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-block flex-shrink-0 round-10 bg-primary rounded-circle"></span>
                                <span class="text-nowrap text-muted">Confirmed</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-block flex-shrink-0 round-10 bg-danger rounded-circle"></span>
                                <span class="text-nowrap text-muted">Cancelled</span>
                            </div>
                        </div>
                    </div>
                    <div class="chart-scroll-wrapper">
                        <div class="chart-wrapper charty" style="height: 292px; min-width: 800px;">
                            <div id="area-spaline1"></div>
                        </div>
                    </div>
                    <div class="row mt-4 mb-2 stat">
                        <div class="col-md-4">
                            <div class="hstack gap-6 mb-3 mb-md-0">
                                <span class="d-flex align-items-center justify-content-center round-48 bg-light rounded">
                                    <iconify-icon icon="solar:pie-chart-2-linear" class="fs-7 text-dark"></iconify-icon>
                                </span>
                                <div>
                                    <span>Total</span>
                                    <h5 id="totalConfirmedCancelledValue" class="mt-1 fw-medium mb-0">
                                        {{ $canceledtotal + $confirmedtotal }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="hstack gap-6 mb-3 mb-md-0">
                                <span
                                    class="d-flex align-items-center justify-content-center round-48 bg-primary-subtle rounded">
                                    <iconify-icon icon="solar:dollar-minimalistic-linear"
                                        class="fs-7 text-primary"></iconify-icon>
                                </span>
                                <div>
                                    <span>Confirmed</span>
                                    <h5 id="confirmedTotalValue" class="mt-1 fw-medium mb-0">{{ $confirmedtotal }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="hstack gap-6">
                                <span
                                    class="d-flex align-items-center justify-content-center round-48 bg-danger-subtle rounded">
                                    <iconify-icon icon="solar:database-linear" class="fs-7 text-danger"></iconify-icon>
                                </span>
                                <div>
                                    <span>Cancelled</span>
                                    <h5 id="cancelledTotalValue" class="mt-1 fw-medium mb-0">{{ $canceledtotal }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold">Confirmation Performance</h5>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="text-center mt-sm-n7">
                                <div id="your-confirmation"></div>
                                <h2 id="confirmationRateValue" class="fs-8">
                                    @if ($total != 0)
                                        {{ number_format(($confirmed / $total) * 100, 2, '.', ',') }} %
                                    @else
                                        0 %
                                    @endif
                                </h2>
                                <p class="mb-0">
                                    Learn insights how to manage all aspects of your startup.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3 stat">
                            <div class="vstack gap-9 mt-2">
                                <div class="hstack align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded bg-primary-subtle flex-shrink-0">
                                        <iconify-icon icon="solar:shop-2-linear" class="fs-7 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 id="newLeadsValue" class="mb-0 text-nowrap">{{ $total }} Leads</h6>
                                        <span>New</span>
                                    </div>
                                </div>
                                <div class="hstack align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded bg-success-subtle">
                                        <iconify-icon icon="solar:pills-3-linear"
                                            class="fs-7 text-success"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 id="confirmedLeadsCountValue" class="mb-0">{{ $confirmed }} Leads</h6>
                                        <span>Confirmed</span>
                                    </div>
                                </div>
                                <div class="hstack align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded bg-danger-subtle">
                                        <iconify-icon icon="solar:filters-outline"
                                            class="fs-7 text-danger"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 id="cancelledLeadsCountValue" class="mb-0">{{ $cancelled }} Leads</h6>
                                        <span>Cancelled</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3 stat">
                            <div class="vstack gap-9 mt-2">
                                <div class="hstack align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded bg-secondary-subtle flex-shrink-0">
                                        <iconify-icon icon="solar:shop-2-linear"
                                            class="fs-7 text-secondary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 id="callLaterLeadsValue" class="mb-0 text-nowrap">{{ $totlead }} Leads
                                        </h6>
                                        <span>Call Later</span>
                                    </div>
                                </div>
                                <div class="hstack align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded bg-warning-subtle">
                                        <iconify-icon icon="solar:pills-3-linear"
                                            class="fs-7 text-warning"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 id="noAnswerLeadsCountValue" class="mb-0">{{ $noanswer }} Leads</h6>
                                        <span>No Answer</span>
                                    </div>
                                </div>
                                <div class="hstack align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded bg-danger-subtle">
                                        <iconify-icon icon="solar:filters-outline"
                                            class="fs-7 text-danger"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 id="systemCancelledLeadsValue" class="mb-0">{{ $cancelledbysystem }} Leads
                                        </h6>
                                        <span>Canceled By System</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (Auth::user()->id_role != '3')
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3 mb-9 justify-content-between align-items-center">
                            <h5 class="card-title fw-semibold mb-0">Agent Performance</h5>
                        </div>
                        <div class="table-responsive" style="min-height: 100%;">
                            <ul class="nav nav-tabs theme-tab gap-3 flex-nowrap" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#app" role="tab">
                                        <div class="hstack gap-2">
                                            <iconify-icon icon="solar:widget-linear" class="fs-4"></iconify-icon>
                                            <span>Top 5 Agent</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#mobile" role="tab">
                                        <div class="hstack gap-2">
                                            <iconify-icon icon="solar:smartphone-line-duotone"
                                                class="fs-4"></iconify-icon>
                                            <span>Bad 5 Agent</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content mb-n3">
                            <div class="tab-pane active" id="app" role="tabpanel">
                                <div class="table-responsive" data-simplebar style="min-height: 100%;">
                                    <table class="table text-nowrap align-middle table-custom mb-0 last-items-borderless">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="fw-normal ps-0">Assigned</th>
                                                <th scope="col" class="fw-normal">Total Confirmed</th>
                                                <th scope="col" class="fw-normal">Total Delivered</th>
                                                <th scope="col" class="fw-normal">Rate Delivered</th>
                                                <th scope="col" class="fw-normal">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="topAgentsTableBody">
                                            @foreach ($top_agent as $v_top)
                                                <tr>
                                                    <td class="ps-0">
                                                        <div class="d-flex align-items-center gap-6">
                                                            <img src="{{ asset('public/assets/images/profile/user-1.jpg') }}"
                                                                alt="prd1" width="48" class="rounded" />
                                                            <div>
                                                                <h6 class="mb-0">{{ $v_top->n }}</h6>
                                                                <span>Agent</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span>{{ $v_top->total }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="delivered-count">{{ $v_top->CountDelivered($v_top->id, request('date_from', date('Y-m-d')), request('date_to', date('Y-m-d'))) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="progress-showcase">
                                                            <div class="progress" style="height: 18px;">
                                                                <div class="progress-bar bg-primary" role="progressbar"
                                                                    style="width: {{ number_format((float) (($v_top->CountDelivered($v_top->id, request('date_from', date('Y-m-d')), request('date_to', date('Y-m-d'))) / $v_top->total) * 100), 2) }}%"
                                                                    aria-valuenow="50" aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                </div>
                                                                <span
                                                                    class="text-muted">{{ number_format((float) (($v_top->CountDelivered($v_top->id, request('date_from', date('Y-m-d')), request('date_to', date('Y-m-d'))) / $v_top->total) * 100), 2) }}%</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a class=""
                                                            href="{{ route('leads.index') }}?agent={{ $v_top->id }}">
                                                            <iconify-icon icon="solar:bill-list-line-duotone"
                                                                class="ti"></iconify-icon>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="mobile" role="tabpanel">
                                <div class="table-responsive" data-simplebar style="min-height: 100%;">
                                    <table class="table text-nowrap align-middle table-custom mb-0 last-items-borderless">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="fw-normal ps-0">Assigned</th>
                                                <th scope="col" class="fw-normal">Total Confirmed</th>
                                                <th scope="col" class="fw-normal">Total Delivered</th>
                                                <th scope="col" class="fw-normal">Rate Delivered</th>
                                                <th scope="col" class="fw-normal">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="badAgentsTableBody">
                                            @foreach ($bad_agent as $v_bad)
                                                <tr>
                                                    <td class="ps-0">
                                                        <div class="d-flex align-items-center gap-6">
                                                            <img src="{{ asset('public/assets/images/profile/user-1.jpg') }}"
                                                                alt="prd1" width="48" class="rounded" />
                                                            <div>
                                                                <h6 class="mb-0">{{ $v_bad->n }}</h6>
                                                                <span>Agent</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span>{{ $v_bad->total }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="delivered-count">{{ $v_bad->CountDelivered($v_bad->id, request('date_from', date('Y-m-d')), request('date_to', date('Y-m-d'))) }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="progress" style="height: 18px;">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: {{ number_format((float) (($v_bad->CountDelivered($v_bad->id, request('date_from', date('Y-m-d')), request('date_to', date('Y-m-d'))) / $v_bad->total) * 100), 2) }}%"
                                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                            </div>
                                                            <span
                                                                class="text-muted">{{ number_format((float) (($v_bad->CountDelivered($v_bad->id, request('date_from', date('Y-m-d')), request('date_to', date('Y-m-d'))) / $v_bad->total) * 100), 2) }}%</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a class=""
                                                            href="{{ route('leads.index') }}?agent={{ $v_bad->id }}">
                                                            <iconify-icon icon="solar:bill-list-line-duotone"
                                                                class="ti"></iconify-icon>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Shipping Performance</h5>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="text-center mt-sm-n7">
                                    <div id="your-shipping"></div>
                                    <h2 id="shippingRateValue" class="fs-8">
                                        @if ($confirmed != 0)
                                            {{ number_format(($totdelivered / $confirmed) * 100, 2, '.', ',') }} %
                                        @else
                                            0 %
                                        @endif
                                    </h2>
                                    <p class="mb-0">
                                        Learn insights how to manage all aspects of your startup.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 stat">
                                <div class="vstack gap-9 mt-2">
                                    <div class="hstack align-items-center gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center round-48 rounded bg-primary-subtle flex-shrink-0">
                                            <iconify-icon icon="solar:shop-2-linear"
                                                class="fs-7 text-primary"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 id="totalOrdersValue" class="mb-0 text-nowrap">{{ $confirmed }} orders
                                            </h6>
                                            <span>Total</span>
                                        </div>
                                    </div>
                                    <div class="hstack align-items-center gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center round-48 rounded bg-secondary-subtle">
                                            <iconify-icon icon="solar:pills-3-linear"
                                                class="fs-7 text-secondary"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 id="deliveredOrdersValue" class="mb-0">{{ $totdelivered }} orders</h6>
                                            <span>Delivered</span>
                                        </div>
                                    </div>
                                    <div class="hstack align-items-center gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center round-48 rounded bg-danger-subtle">
                                            <iconify-icon icon="solar:filters-outline"
                                                class="fs-7 text-danger"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 id="rejectedOrdersValue" class="mb-0">{{ $totrejected }} orders</h6>
                                            <span>Rejected</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 stat">
                                <div class="vstack gap-9 mt-2">
                                    <div class="hstack align-items-center gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center round-48 rounded bg-primary-subtle flex-shrink-0">
                                            <iconify-icon icon="solar:shop-2-linear"
                                                class="fs-7 text-primary"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 id="newOrdersValue" class="mb-0 text-nowrap">{{ $totUnpacked }} orders
                                            </h6>
                                            <span>New</span>
                                        </div>
                                    </div>
                                    <div class="hstack align-items-center gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center round-48 rounded bg-warning-subtle">
                                            <iconify-icon icon="solar:filters-outline"
                                                class="fs-7 text-warning"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 id="processingOrdersValue" class="mb-0">{{ $totproccessing }} orders
                                            </h6>
                                            <span>Processing</span>
                                        </div>
                                    </div>
                                    <div class="hstack align-items-center gap-3">
                                        <div
                                            class="d-flex align-items-center justify-content-center round-48 rounded bg-danger-subtle">
                                            <iconify-icon icon="solar:pills-3-linear"
                                                class="fs-7 text-danger"></iconify-icon>
                                        </div>
                                        <div>
                                            <h6 id="returnedOrdersValue" class="mb-0">{{ $totreturned }} orders</h6>
                                            <span>Returned</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('script')
    <script src="{{ asset('public/assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('public/assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script>
        var hourlyChartInstance, confirmationChartInstance, shippingChartInstance;

        const elementIds = {
            loader: 'fullPageLoader',
            dateFrom: 'date_from',
            dateTo: 'date_to',
            filterForm: 'dashboardFilterForm',
            buttonGroup: 'button-grp',
            quickFilter: 'quick-filter',
            hourlyChart: 'area-spaline1',
            confirmationChart: 'your-confirmation',
            shippingChart: 'your-shipping'
        };

        function showLoader() {
            $('#' + elementIds.loader).removeClass('d-none');
        }

        function hideLoader() {
            $('#' + elementIds.loader).addClass('d-none');
        }

        function initializeHourlyChart(confirmedData, canceledData) {
            if (hourlyChartInstance) {
                hourlyChartInstance.destroy();
            }

            hourlyChartInstance = new ApexCharts(
                document.querySelector("#" + elementIds.hourlyChart), {
                    series: [{
                            name: "Cancelled",
                            data: canceledData
                        },
                        {
                            name: "Confirmed",
                            data: confirmedData
                        }
                    ],
                    chart: {
                        height: 265,
                        type: "bar",
                        fontFamily: "inherit",
                        foreColor: "#adb0bb",
                        toolbar: {
                            show: false,
                        },
                        stacked: true,
                    },
                    colors: ["var(--bs-danger)", "var(--bs-secondary)"],
                    plotOptions: {
                        bar: {
                            borderRadius: [6],
                            horizontal: false,
                            barHeight: "60%",
                            columnWidth: "40%",
                            borderRadiusApplication: "end",
                            borderRadiusWhenStacked: "all",
                        },
                    },
                    stroke: {
                        show: false,
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    legend: {
                        show: false,
                    },
                    grid: {
                        show: false,
                        padding: {
                            top: 0,
                            right: 0,
                            bottom: 0,
                            left: 0,
                        },
                    },
                    yaxis: {
                        tickAmount: 4,
                    },
                    xaxis: {
                        categories: Array.from({
                            length: 24
                        }, (_, i) => i),
                        axisTicks: {
                            show: false,
                        },
                    },
                    tooltip: {
                        theme: "dark",
                        fillSeriesColor: false,
                        x: {
                            show: false,
                        },
                    },
                }
            );
            hourlyChartInstance.render();
        }

        function initializeConfirmationChart(confirmed, cancelled) {
            if (confirmationChartInstance) {
                confirmationChartInstance.destroy();
            }

            confirmationChartInstance = new ApexCharts(
                document.querySelector("#" + elementIds.confirmationChart), {
                    series: [confirmed, cancelled],
                    labels: ["Confirmed", "Cancelled"],
                    chart: {
                        height: 205,
                        fontFamily: "inherit",
                        type: "donut",
                    },
                    plotOptions: {
                        pie: {
                            startAngle: -90,
                            endAngle: 90,
                            offsetY: 10,
                            donut: {
                                size: "90%",
                            },
                        },
                    },
                    grid: {
                        padding: {
                            bottom: -80,
                        },
                    },
                    legend: {
                        show: false,
                    },
                    dataLabels: {
                        enabled: false,
                        name: {
                            show: false,
                        },
                    },
                    stroke: {
                        width: 2,
                        colors: "var(--bs-card-bg)",
                    },
                    tooltip: {
                        fillSeriesColor: false,
                    },
                    colors: [
                        "var(--bs-secondary)",
                        "var(--bs-danger)",
                    ],
                    responsive: [{
                        breakpoint: 1400,
                        options: {
                            chart: {
                                height: 170
                            },
                        },
                    }],
                }
            );
            confirmationChartInstance.render();
        }

        function initializeShippingChart(delivered, returned) {
            if (shippingChartInstance) {
                shippingChartInstance.destroy();
            }

            shippingChartInstance = new ApexCharts(
                document.querySelector("#" + elementIds.shippingChart), {
                    series: [delivered, returned],
                    labels: ["Delivered", "Returned"],
                    chart: {
                        height: 205,
                        fontFamily: "inherit",
                        type: "donut",
                    },
                    plotOptions: {
                        pie: {
                            startAngle: -90,
                            endAngle: 90,
                            offsetY: 10,
                            donut: {
                                size: "90%",
                            },
                        },
                    },
                    grid: {
                        padding: {
                            bottom: -80,
                        },
                    },
                    legend: {
                        show: false,
                    },
                    dataLabels: {
                        enabled: false,
                        name: {
                            show: false,
                        },
                    },
                    stroke: {
                        width: 2,
                        colors: "var(--bs-card-bg)",
                    },
                    tooltip: {
                        fillSeriesColor: false,
                    },
                    colors: [
                        "var(--bs-secondary)",
                        "var(--bs-danger)",
                    ],
                    responsive: [{
                        breakpoint: 1400,
                        options: {
                            chart: {
                                height: 170
                            },
                        },
                    }],
                }
            );
            shippingChartInstance.render();
        }

        function updateDashboardElements(data) {
            $('#totalLeadsValue').text(data.total);
            $('#confirmedLeadsValue').text(data.confirmed);
            $('#noAnswerLeadsValue').text(data.noanswer);
            $('#cancelledLeadsValue').text(data.cancelled);
            $('#deliveredLeadsValue').text(data.totdelivered);

            $('#totalConfirmedCancelledValue').text(data.confirmedtotal + data.canceledtotal);
            $('#confirmedTotalValue').text(data.confirmedtotal);
            $('#cancelledTotalValue').text(data.canceledtotal);

            $('#confirmationRateValue').text(data.total != 0 ? ((data.confirmed / data.total) * 100).toFixed(2) + '%' :
                '0%');
            $('#newLeadsValue').text(data.total + ' Leads');
            $('#confirmedLeadsCountValue').text(data.confirmed + ' Leads');
            $('#cancelledLeadsCountValue').text(data.cancelled + ' Leads');
            $('#callLaterLeadsValue').text(data.totlead + ' Leads');
            $('#noAnswerLeadsCountValue').text(data.noanswer + ' Leads');
            $('#systemCancelledLeadsValue').text(data.cancelledbysystem + ' Leads');

            $('#shippingRateValue').text(data.confirmed != 0 ? ((data.totdelivered / data.confirmed) * 100).toFixed(2) +
                '%' : '0%');
            $('#totalOrdersValue').text(data.confirmed + ' orders');
            $('#deliveredOrdersValue').text(data.totdelivered + ' orders');
            $('#rejectedOrdersValue').text(data.totrejected + ' orders');
            $('#newOrdersValue').text(data.totUnpacked + ' orders');
            $('#processingOrdersValue').text(data.totproccessing + ' orders');
            $('#returnedOrdersValue').text(data.totreturned + ' orders');

            updateAgentPerformanceTables(
                data.top_agents || [],
                data.bad_agents || [],
                data.date_from,
                data.date_to
            );
        }

        function updateAgentPerformanceTables(topAgents, badAgents, dateFrom, dateTo) {
            let topAgentsHtml = '';
            topAgents.forEach(agent => {
                const deliveredCount = agent.delivered_count || 0;
                const deliveryRate = agent.total > 0 ? ((deliveredCount / agent.total) * 100).toFixed(2) : 0;

                topAgentsHtml += `
            <tr>
                <td class="ps-0">
                    <div class="d-flex align-items-center gap-6">
                        <img src="{{ asset('public/assets/images/profile/user-1.jpg') }}" alt="agent" width="48" class="rounded" />
                        <div>
                            <h6 class="mb-0">${agent.n}</h6>
                            <span>Agent</span>
                        </div>
                    </div>
                </td>
                <td>${agent.total}</td>
                <td>${deliveredCount}</td>
                <td>
                    <div class="progress-showcase">
                        <div class="progress" style="height: 18px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                style="width: ${deliveryRate}%" 
                                aria-valuenow="${deliveryRate}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                            <span class="text-muted">${deliveryRate}%</span>
                        </div>
                    </div>
                </td>
                <td>
                    <a href="{{ route('leads.index') }}?agent=${agent.id}&date_from=${dateFrom}&date_to=${dateTo}">
                        <iconify-icon icon="solar:bill-list-line-duotone" class="ti"></iconify-icon>
                    </a>
                </td>
            </tr>
        `;
            });
            $('#topAgentsTableBody').html(topAgentsHtml ||
                '<tr><td colspan="5" class="text-center">No top agents found</td></tr>');

            let badAgentsHtml = '';
            badAgents.forEach(agent => {
                const deliveredCount = agent.delivered_count || 0;
                const deliveryRate = agent.total > 0 ? ((deliveredCount / agent.total) * 100).toFixed(2) : 0;

                badAgentsHtml += `
            <tr>
                <td class="ps-0">
                    <div class="d-flex align-items-center gap-6">
                        <img src="{{ asset('public/assets/images/profile/user-1.jpg') }}" alt="agent" width="48" class="rounded" />
                        <div>
                            <h6 class="mb-0">${agent.n}</h6>
                            <span>Agent</span>
                        </div>
                    </div>
                </td>
                <td>${agent.total}</td>
                <td>${deliveredCount}</td>
                <td>
                    <div class="progress-showcase">
                        <div class="progress" style="height: 18px;">
                            <div class="progress-bar bg-danger" role="progressbar" 
                                style="width: ${deliveryRate}%" 
                                aria-valuenow="${deliveryRate}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                            <span class="text-muted">${deliveryRate}%</span>
                        </div>
                    </div>
                </td>
                <td>
                    <a href="{{ route('leads.index') }}?agent=${agent.id}&date_from=${dateFrom}&date_to=${dateTo}">
                        <iconify-icon icon="solar:bill-list-line-duotone" class="ti"></iconify-icon>
                    </a>
                </td>
            </tr>
        `;
            });
            $('#badAgentsTableBody').html(badAgentsHtml ||
                '<tr><td colspan="5" class="text-center">No bad agents found</td></tr>');
        }

        function fetchDashboardData(dateFrom, dateTo) {
            showLoader();
            console.log('Fetching dashboard data for:', dateFrom, 'to', dateTo);

            $.ajax({
                url: "{{ route('dashboard.data') }}",
                type: "GET",
                data: {
                    date_from: dateFrom,
                    date_to: dateTo
                },
                success: function(response) {
                    console.log('Response:', response);
                    if (response.success) {
                        const data = response.data;

                        updateDashboardElements(data);

                        initializeHourlyChart(
                            data.hourlyData.confirmed,
                            data.hourlyData.canceled
                        );
                        initializeConfirmationChart(data.confirmed, data.cancelled);
                        if ($('#your-shipping').length) {
                            initializeShippingChart(data.totdelivered, data.totrejected);
                        }
                    } else {
                        console.error('Error fetching dashboard data:', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                },
                complete: function() {
                    hideLoader();
                }
            });
        }

        $(document).ready(function() {
            const elementIds = {
                dateFrom: 'date_from',
                dateTo: 'date_to',
                filterForm: 'dashboardFilterForm',
                quickFilter: 'quick-filter'
            };

            const today = new Date().toISOString().split('T')[0];
            $('#' + elementIds.dateTo).attr('max', today);
            $('#' + elementIds.dateFrom).attr('max', today);

            if (typeof completeHourlyData !== 'undefined') {
                initializeHourlyChart(
                    completeHourlyData.confirmed,
                    completeHourlyData.canceled
                );
            }
            initializeConfirmationChart({{ $confirmedtotal }}, {{ $canceledtotal }});
            initializeShippingChart({{ $totdelivered }}, {{ $totrejected }});

            $('#' + elementIds.dateFrom).on('change', function() {
                $('#' + elementIds.dateTo).attr('min', $(this).val());
                if (new Date($('#' + elementIds.dateTo).val()) < new Date($(this).val())) {
                    $('#' + elementIds.dateTo).val($(this).val());
                }
            });

            $('#' + elementIds.dateTo).on('change', function() {
                if (new Date($(this).val()) < new Date($('#' + elementIds.dateFrom).val())) {
                    $(this).val($('#' + elementIds.dateFrom).val());
                }
            });

            $('.' + elementIds.quickFilter).on('click', function(e) {
                e.preventDefault();
                const dateFrom = $(this).data('from');
                const dateTo = $(this).data('to');

                $('#' + elementIds.dateFrom).val(dateFrom);
                $('#' + elementIds.dateTo).val(dateTo);

                $('.' + elementIds.quickFilter).removeClass('active');
                $(this).addClass('active');

                fetchDashboardData(dateFrom, dateTo);
            });

            $('#' + elementIds.filterForm).on('submit', function(e) {
                e.preventDefault();
                const dateFrom = $('#' + elementIds.dateFrom).val();
                const dateTo = $('#' + elementIds.dateTo).val();
                fetchDashboardData(dateFrom, dateTo);
            });

            function setInitialActiveButton() {
                const date_from = $('#date_from').val();
                const date_to = $('#date_to').val();

                $('.quick-filter').each(function() {
                    if ($(this).data('from') === date_from && $(this).data('to') === date_to) {
                        $(this).addClass('active');
                        return false;
                    }
                });
            }

            setInitialActiveButton();

            fetchDashboardData(
                $('#' + elementIds.dateFrom).val(),
                $('#' + elementIds.dateTo).val()
            );
        });
    </script>
@endsection
