@extends('backend.layouts.app')
@section('content')

    <style>
        .dropdown-menu.show {
            display: block;
        }
    </style>
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
     
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
            <!-- ============================================================== -->
               <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-10 align-self-center">
                    <h4 class="page-title"><span class="text-muted fw-light">Dashboard /</span> Statistics</h4>
                   
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
            <!-- Start Page Content -->
            <div class="row my-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group-1">

                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'callcenter.filter', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'callcenter']) }}
                                @php
                                    $agent_id = isset($agent_id) ? $agent_id : '';
                                @endphp
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="input-group">
                                            <select class="select2 form-control custom-select" name="agent">
                                                <option value='all'>Select All Agents</option>
                                                @foreach ($agents_all->get() as $agent)
                                                    @if ($agent->id == $agent_id)
                                                        <option value="{{ $agent->id }}" selected>{{ $agent->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                                    @endif
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <div class='input-group mb-3'>
                                            @php
                                                $date1 = isset($date1) ? $date1 : old(date1);
                                                $date2 = isset($date2) ? $date2 : old(date2);
                                            @endphp
                                            <input type='date' name="date1" value="{{ $date1 }}"
                                                class="form-control " />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <div class='input-group mb-3'>
                                            <input type='date' name="date2" value="{{ $date2 }}"
                                                class="form-control " />
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-12">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary"> Submit</button>
                                        </div>
                                    </div>

                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row"
                                style="justify-content: space-between; align-content: space-around;align-items: center;    margin-bottom: 19px;">
                                <h4 class="page-title" style="font-size: 27px;">Analyses Call Center</h4>
                            </div>

                            <div class="table-responsive" style="text-align:center">
                                <table id="demo-foo-addrow"
                                    class="table table-bordered table-striped table-hover contact-list table-fixed"
                                    data-paging="true" data-paging-size="10">
                                    <thead>
                                        <tr>
                                            <th>No (id) </th>
                                            <th>Name</th>
                                            <th>NB.Order Traits</th>
                                            <th>NB. calls</th>
                                            <th>confirmed</th>
                                            <th>canceled</th>
                                            <th>NO answer</th>
                                            <th>call later</th>
                                            <th>Wrong</th>
                                            <th>Duplicated</th>
                                            <th>Out Of Area</th>
                                            <th>Canceled By System</th>
                                            <th>Action</th>
                                            {{--   <th>Action</th>  --}}
                                        </tr>
                                    </thead>
                                    <tbody>


                                        @if (isset($agents_details))
                                            @foreach ($agents_details->get() as $item)
                                                <tr>
                                                    {{-- @if ($item->VerifyLeads($item->id, $date1, $date2)->count() > 1) --}}
                                                        <td>{{ $item->id }}</td>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->CountLeads($item->id, $date1, $date2) }}</td>
                                                        <td>{{ $item->CountCalls($item->id, $date1, $date2) }}</td>
                                                        <td>
                                                            {{ count($item->CountTypeCall($item->id, $date1, $date2, 'confirmed')) }}
                                                        </td>
                                                        <td>
                                                            {{ count($item->CountTypeCall($item->id, $date1, $date2, 'canceled')) }}
                                                        </td>
                                                        <td>
                                                            {{ count($item->CountTypeCall($item->id, $date1, $date2, 'NO answer')) }}
                                                        </td>
                                                        <td>
                                                            {{ count($item->CountTypeCall($item->id, $date1, $date2, 'call later')) }}
                                                        </td>
                                                        <td>{{ count($item->CountTypeCall($item->id, $date1, $date2, 'Wrong')) }}
                                                        </td>
                                                        <td>{{ count($item->CountTypeCall($item->id, $date1, $date2, 'Duplicated')) }}
                                                        </td>
                                                        <td>{{ count($item->CountTypeCall($item->id, $date1, $date2, 'out of area')) }}
                                                        </td>
                                                        <td>{{ count($item->CountTypeCall($item->id, $date1, $date2, 'canceled by system')) }}
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-primary rounded"
                                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    <i class="ti ti-settings"></i>
                                                                </button>
                                                                <div class="dropdown-menu animated slideInUp"
                                                                    x-placement="bottom-start"
                                                                    style="position: absolute; will-change: transform; transform: translate3d(0px, 35px, 0px);margin-left: -60px!important;">
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('callcenter.details', $item->id . '&' . $date1 . '&' . $date2) }}"><i
                                                                            class="mdi mdi-cards-variant"></i> Details</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    {{-- @else
                                                        @if (Request::segment(2) == 'filter' && $agent_id != 'all')
                                                            <td colspan="12">
                                                                No Record
                                                            </td>
                                                        @endif
                                                    @endif --}}
                                                </tr>
                                            @endforeach
                                        @endif



                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Right sidebar -->
            <!-- ============================================================== -->
            <!-- .right-sidebar -->
            <!-- ============================================================== -->
            <!-- End Right sidebar -->
            <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
@endsection
