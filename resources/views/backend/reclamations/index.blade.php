@extends('backend.layouts.app')
@section('content')
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">

        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title"> <span class="text-muted fw-light">Dashboard /</span> Reclamations</h4>
                        <div class="d-flex align-items-center">

                        </div>
                    </div>
                </div>
            </div>
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <!-- Add Contact Popup Model -->
            <div id="add-contact" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Add New Reclamation</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <from class="form-horizontal form-material">
                                <div class="form-group">
                                    <div class="col-md-12 my-4">
                                        <input type="text" class="form-control" id="n_lead" placeholder="N Lead"
                                            required>
                                    </div>
                                    <div class="col-md-12 col-sm-12 my-4">
                                        <select class="form-control" id="id_service" name="id_service">
                                            <option value="">Select Service</option>
                                            <option value="1">Admin</option>
                                            <option value="5">Warehouse</option>
                                            <option value="2">Seller</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 my-4">
                                        <textarea class="form-control" id="note" placeholder="Reclamation" required></textarea>
                                    </div>
                                </div>
                            </from>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary waves-effect"
                                id="createReclamations">Save</button>
                            <button type="button" class="btn btn-primary waves-effect"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title">Your Reclamations</h4>
                                <h6 class="card-subtitle"></h6>
                                <div class="form-group mb-4">
                                    <button type="button" class="btn btn-primary btn-rounded waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target="#add-contact">Add New Reclamation</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list"
                                    data-paging="true" data-paging-size="7">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>N Lead</th>
                                            <th>Reclamations Note</th>
                                            <th>Status</th>
                                            <th>Created at</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 1;
                                        ?>
                                        @foreach ($reclamations as $v_sheet)
                                            <tr>
                                                <td>{{ $counter }}</td>
                                                @foreach ($v_sheet['lead'] as $v_shet)
                                                    <td>{{ $v_shet->n_lead }}</td>
                                                @endforeach
                                                <td>{{ $v_sheet->note }}</td>
                                                <td>
                                                    @if ($v_sheet->status == 'done')
                                                        <span class="badge bg-success">{{ $v_sheet->status }}</span>
                                                    @elseif($v_sheet->status == 'canceled')
                                                        <span class="badge bg-danger">{{ $v_sheet->status }}</span>
                                                    @else
                                                        <form class="stats" data-id="{{ $v_sheet->id }}">
                                                            <select class="form-control" id="statu_con{{ $v_sheet->id }}"
                                                                name="status">
                                                                <option value="on hold"
                                                                    {{ $v_sheet->status == 'on hold' ? 'selected' : '' }}>On
                                                                    hold</option>
                                                                <option value="processing"
                                                                    {{ $v_sheet->status == 'processing' ? 'selected' : '' }}>
                                                                    Processing</option>
                                                                <option value="done"
                                                                    {{ $v_sheet->status == 'done' ? 'selected' : '' }}>Done
                                                                </option>
                                                                <option value="canceled"
                                                                    {{ $v_sheet->status == 'canceled' ? 'selected' : '' }}>
                                                                    Cancelled</option>
                                                            </select>
                                                        </form>
                                                    @endif
                                                    <!--<span class="label label-warning">{{ $v_sheet->status }}</span>-->
                                                </td>
                                                <td>{{ $v_sheet->created_at }}</td>
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

            <div class="row my-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reclamations Sent For You</h4>
                            <h6 class="card-subtitle"></h6>
                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list"
                                    data-paging="true" data-paging-size="7">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>N Lead</th>
                                            <th>Reclamations Note</th>
                                            <th>Status</th>
                                            <th>Created at</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 1;
                                        ?>
                                        @foreach ($reclamations2 as $v_sheet)
                                            <tr>
                                                <td>{{ $counter }}</td>
                                                @foreach ($v_sheet['lead'] as $v_shet)
                                                    <td>{{ $v_shet->n_lead }}</td>
                                                @endforeach
                                                <td>{{ $v_sheet->note }}</td>
                                                <td>
                                                    @if ($v_sheet->status == 'done')
                                                        <span class="badge bg-success">{{ $v_sheet->status }}</span>
                                                    @elseif($v_sheet->status == 'canceled')
                                                        <span class="badge bg-danger">{{ $v_sheet->status }}</span>
                                                    @else
                                                        <form class="myform2" data-id="{{ $v_sheet->id }}">
                                                            <select class="form-control" id="statu_con2{{ $v_sheet->id }}"
                                                                name="status">
                                                                <option value="on hold"
                                                                    {{ $v_sheet->status == 'on hold' ? 'selected' : '' }}>
                                                                    On
                                                                    hold</option>
                                                                <option value="processing"
                                                                    {{ $v_sheet->status == 'processing' ? 'selected' : '' }}>
                                                                    Processing</option>
                                                                <option value="done"
                                                                    {{ $v_sheet->status == 'done' ? 'selected' : '' }}>Done
                                                                </option>
                                                                <option value="canceled"
                                                                    {{ $v_sheet->status == 'canceled' ? 'selected' : '' }}>
                                                                    Cancelled</option>
                                                            </select>
                                                        </form>
                                                    @endif
                                                    <!--<span class="label label-warning">{{ $v_sheet->status }}</span>-->
                                                </td>
                                                <td>{{ $v_sheet->created_at }}</td>
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
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
        </div>

        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">
                <div
                    class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
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
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(function(e) {
            $('#createReclamations').click(function(e) {
                var nlead = $('#n_lead').val();
                var note = $('#note').val();
                var service = $('#id_service').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('reclamations.store') }}',
                    cache: false,
                    data: {
                        nlead: nlead,
                        note: note,
                        service: service,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.',
                                'Reclamation Has been Addess Success!', {
                                    "showMethod": "slideDown",
                                    "hideMethod": "slideUp",
                                    timeOut: 2000
                                });
                        }
                        location.reload();
                    }
                });
            });
        });

        $(function(e) {
            $('#deletesheet').click(function(e) {
                var id = $(this).data('id');
                if (confirm("Are you sure, you want to Delete Sheet?")) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('reclamations.delete') }}',
                        cache: false,
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success == true) {
                                toastr.success('Good Job.', 'Sheet Has been Deleted Success!', {
                                    "showMethod": "slideDown",
                                    "hideMethod": "slideUp",
                                    timeOut: 2000
                                });
                            }
                            location.reload();
                        }
                    });
                }
            });
        });
        $(function() {
            $('body').on('click', '.editProduct', function() {
                var sheet_id = $(this).data('id');
                //console.log(product_id);
                $.get("{{ route('reclamations.index') }}" + '/' + sheet_id + '/edit', function(data) {
                    //console.log(product_id);
                    $('#editsheet').modal('show');
                    $('#sheet_id').val(data.id);
                    $('#Reclamations_name').val(data.sheetname);
                    $('#sheetid').val(data.sheetid);
                });
            });
        });
        $('body').on('change', '.myform2', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var statuu = '#statu_con2' + id;
            var status = $(statuu).val();
            //console.log(id);
            $.ajax({
                type: "POST",
                url: '{{ route('reclamations.statuscon') }}',
                cache: false,
                data: {
                    id: id,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        toastr.success('Good Job.', 'Reclamation Has been Update Success!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                }
            });
        });
        $('body').on('change', '.stats', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var statuu = '#statu_con' + id;
            var status = $(statuu).val();
            $.ajax({
                type: "POST",
                url: '{{ route('reclamations.statuscon') }}',
                cache: false,
                data: {
                    id: id,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        toastr.success('Good Job.', 'Reclamation Has been Update Success!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                }
            });
        });
    </script>
@endsection
