@extends('backend.layouts.app')
@section('content')
    <style>
        .chartjs {
            height: 366px !important;
        }
        body{
          overflow-x: hidden;
        }
    </style>
        <div class="card card-body py-3">
            <div class="row align-items-center">
              <div class="col-12">
                <div class="d-sm-flex align-items-center justify-space-between">
                  <h4 class="mb-4 mb-sm-0 card-title">Analytics</h4>
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

            <div class="row">
                <div class="col-12 p-0 ">
                    <div class="card">
                        <div class="row m-4 d-flex align-items-center justify-content-between">
                            <h4>Confirmation Data</h4>
                            <form class=" align-items-center">
                                <div class="row">
                                    <div class="col-lg-4 col-md-10 mt-2">
                                        <div class="dl">
                                            <div class="col-12 align-self-center">
                                                <div class='input-group '>
                                                    <input type='text' class="form-control dated"
                                                        name="date_call" value="{{ $date_1_call }} - {{ $date_2_call }}" id="flatpickr-range"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" col-lg-4 col-md-10 mt-2">
                                        <div class="dl w-100">
                                            <select class="form-control select2" name="call_product" id="call_product_select">
                                                <option value="" selected>Select Product</option>
                                                @foreach ($products as $v_product)
                                                    <option value="{{ $v_product->id }}"
                                                        {{ $v_product->id == request()->input('call_product') ? 'selected' : '' }}>
                                                        {{ $v_product->name }} / {{ $v_product->sku }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class=" col-lg-3 col-md-10 mt-2">
                                        <div class="align-items-center">
                                            <div class="dl">
                                                <button class="btn btn-primary input-group-append w-100"
                                                    type="submit">APPLY</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Cards with few info -->
                <div class="card table-responsive rounded-1 mb-4" >
                    <table class="table text-nowrap customize-table  align-middle">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th>Quantity Confirmed</th>
                                <th>Confirmed Price</th>
                                <th>Quantity Delivered</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leads as $v_lead)
                            <?php $sum = 0; ?>
                            <tr>
                                <td>
                                    <span class="badge bg-success mt-1">Quantity : {{$v_lead['quantity']. '- Count :' .$v_lead['count']}}</span>
                                </td>
                                <td> 
                                    @if($v_lead->PriceConfirmed($date_1_call , $date_2_call , $agent , $v_lead['quantity'] , $call_product))
                                    <?php $check = $v_lead->PriceConfirmed($date_1_call , $date_2_call , $agent , $v_lead['quantity'] , $call_product); ?>
                                        @foreach($check as $v_check)
                                            <span class="badge bg-success mt-1">Price : {{$v_check['lead_value'] .' '. $country->currency .'- Count :' .$v_check['count']}}</span> <span class="badge bg-warning"> Revenue : {{ $v_check['lead_value'] * $v_check['count'] .' '. $country->currency}}</span><br>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($v_lead->QuanityDelivered($date_1_call , $date_2_call , $agent , $v_lead['quantity'] , $call_product))
                                    <?php $check = $v_lead->QuanityDelivered($date_1_call , $date_2_call , $agent , $v_lead['quantity'] , $call_product); ?>
                                        @foreach($check as $key => $v_check)
                                            @if($v_check)
                                            <?php
                                                $sum = $sum + ($v_check['lead_value'] * $v_check['count']);
                                            ?>
                                            <span class="badge bg-success mt-1">Price : {{$v_check['lead_value'] .' '. $country->currency .'- Count :' .$v_check['count']}}</span> <span class="badge bg-warning"> Revenue : {{ $v_check['lead_value'] * $v_check['count'] .' '. $country->currency}}</span><br>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success mt-1"> {{ $sum .'-'. $country->currency}}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

@endsection
