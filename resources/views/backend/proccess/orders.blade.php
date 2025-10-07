@extends('backend.layouts.app')
@section('content')
    <div class="page-wrapper">
        
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-lg-1 col-md-12 align-self-center">
                        <h3 class="page-title">Pack</h3>
                    </div>
                    <div class="col-lg-11 col-md-12 align-self-center">
                        <div class="form-group mb-0 text-right">
                            <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white"
                                id="printall">Print Label</a>
                            <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white"
                                data-bs-toggle="modal" data-bs-target="#scans">Scan Orders Return</a>
                            <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white"
                                data-bs-toggle="modal" data-bs-target="#scan">Send Order For Delivred</a>
                            <a type="button" class="btn btn-primary btn-rounded waves-effect waves-light text-white"
                                onclick="toggleText()">2 Send Order For Delivred</a>
                        </div>
                    </div>
                </div>
                <div class="row 2cam" id="2cam" style="display:none">
                    <div class="col-lg-12 col-md-12 align-self-center">
                        <video id="preview" style="width:100%"></video>
                        <button id="btn-front">Front</button>
                        <button id="btn-back">Back</button>
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"></h4>

                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list"
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
                                            <th class="nk-tb-col"><span class="sub-text">N Lead </span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Quantity Product</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Details Product</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 1;
                                        ?>
                                        @foreach ($leads as $v_lead)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="ids"
                                                            class="custom-control-input checkBoxClass"
                                                            value="{{ $v_lead->id }}" id="pid-{{ $counter }}">
                                                        <label class="custom-control-label"
                                                            for="pid-{{ $counter }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $v_lead->n_lead }}</td>
                                                <td>
                                                    {{ $v_lead['leadproduct']->sum('quantity') }}
                                                </td>
                                                <td>
                                                    <a data-id="{{ $v_lead->id }}" id="transfer"
                                                        class="text-inverse pr-2 transfer" data-bs-toggle="tooltip"
                                                        title="Details Product"><i class="ti ti-marker-alt"></i></a>
                                                </td>

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

        </div>

        <!-- Footer -->
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl">
                <div
                    class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                    <div>
                        ©
                        <script>
                            document.write(new Date().getFullYear());
                        </script>
                        , made with ❤️ by <a href="https://PALACE AGENCY.eu" target="_blank" class="fw-semibold">PALACE AGENCY</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- / Footer -->

    </div>
    <div id="scan" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Scan Order</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <from class="form-horizontal form-material">
                        <div class="form-group">
                            <div style="width: auto" id="reader"></div>
                            <div class="col-md-12 col-sm-12 m-b-20 m-t-20">
                                <input type="text" placeholder="qrcode" required class="form-control" id="qrcode">
                            </div>
                        </div>
                    </from>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Scan Order Popup Model -->
    <div id="scans" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Scan Order</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <from class="form-horizontal form-material">
                        <div class="form-group">
                            <div style="width: auto" id="reader"></div>
                            <div class="col-md-12 col-sm-12 m-b-20 m-t-20">
                                <input type="text" placeholder="qrcode" required class="form-control" id="qrcodes">
                            </div>
                        </div>
                    </from>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Add Contact Popup Model -->
    <div id="listproduct" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" style="max-width:500px">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">List Products</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="">
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="table-responsive">
                                    <table id="demo-foo-addrow"
                                        class="table table-bordered m-t-30 table-hover contact-list" data-paging="true"
                                        data-paging-size="7">
                                        <thead>
                                            <tr>
                                                <th class="nk-tb-col"><span class="sub-text">Image </span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Product</span></th>
                                                <th class="nk-tb-col"><span class="sub-text">Quantity</span></th>
                                            </tr>
                                        </thead>
                                        <tbody id="listpro">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!---->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script>
        $("#qrcodes").keyup(function() {
            $value = $(this).val();
            if ($(this).val().length >= 8) {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('products.returninpack') }}',
                    data: {
                        'search': $value,
                    },
                    success: function(response) {
                        if (response.error == false) {
                            toastr.error('Opss.', 'Check Another Order!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                        }
                        if (response.success == true) {
                            toastr.success('Opss.', 'Good Order Has ben send for delivered!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                            $('#editsheets').prop('disabled', false);
                        }
                        $('#qrcode').val('');
                    }
                });
            }

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

        $("#qrcode").keyup(function() {
            $value = $(this).val();
            if ($(this).val().length > 6) {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('products.sendforshippeds') }}',
                    data: {
                        'search': $value,
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.', 'Order is Packed Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                            $('#editsheets').prop('disabled', false);
                            $('#qrcode').val('');
                        }
                        if (response.error == false) {
                            toastr.error('Opss.', 'Chack Another Order!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                        }
                    }
                });
            }


        });
        $("#editsheets").click(function() {
            $stock = $('#barc_cod_stock').val();
            $quantity = $('#quantity').val();
            $tagier = $('#scan_tagier').val();

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
                    if (response.success == true) {
                        toastr.success('Good Job.', 'Upsell Has been Addess Success!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                    location.reload();
                }
            });
        });

        $(".transfer").click(function() {
            $value = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: '{{ route('products.listpro') }}',
                data: {
                    'value': $value,
                },
                success: function(data) {
                    $('#listproduct').modal('show');
                    $('#listpro').html(data);
                }
            });
        });

        function toggleText() {

            var x = document.getElementById("2cam");
            if (x.style.display === "none") {
                x.style.display = "block";
                (() => {
                    const videoElm = document.getElementById('preview');
                    const btnFront = document.querySelector('#btn-front');
                    const btnBack = document.querySelector('#btn-back');

                    const supports = navigator.mediaDevices.getSupportedConstraints();
                    if (!supports['facingMode']) {
                        alert('Browser Not supported!');
                        return;
                    }

                    let stream;

                    const capture = async facingMode => {
                        const options = {
                            audio: false,
                            video: {
                                facingMode,
                            },
                        };

                        try {
                            if (stream) {
                                const tracks = stream.getTracks();
                                tracks.forEach(track => track.stop());
                            }
                            stream = await navigator.mediaDevices.getUserMedia(options);
                        } catch (e) {
                            alert(e);
                            return;
                        }
                        videoElm.srcObject = null;
                        videoElm.srcObject = stream;
                        videoElm.play();
                    }

                    btnBack.addEventListener('click', () => {
                        capture('environment');
                    });

                    btnFront.addEventListener('click', () => {
                        capture('user');
                    });
                })();
                let scanner = new Instascan.Scanner({
                    video: document.getElementById('preview')
                });
                scanner.addListener('scan', function(content) {
                    //console.log(content);
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('products.sendordertoship') }}',
                        cache: false,
                        data: {
                            bar: content,
                            _token: '{{ csrf_token() }}',

                        },
                        success: function(response) {
                            if (response.success == true) {
                                toastr.success('Good Job.', 'Lead Updated Success!', {
                                    "showMethod": "slideDown",
                                    "hideMethod": "slideUp",
                                    timeOut: 2000
                                });
                            }
                            if (response.error == false) {
                                toastr.error('Oppps.', 'Not find this Order!', {
                                    "showMethod": "slideDown",
                                    "hideMethod": "slideUp",
                                    timeOut: 2000
                                });
                            }
                        }
                    });
                });
                Instascan.Camera.getCameras().then(function(cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]);
                    } else {
                        console.error('No cameras found.');
                    }
                }).catch(function(e) {
                    console.error(e);
                });
            } else {
                x.style.display = "none";
            }
        }
    </Script>
    <script type="text/javascript">
        $(function(e) {
            $("#chkCheckAll").click(function() {
                $(".checkBoxClass").prop('checked', $(this).prop('checked'));
            });
            $('#send').click(function(e) {
                e.preventDefault();
                var allids = [];
                $("input:checkbox[name=ids]:checked").each(function() {
                    allids.push($(this).val());
                });
                $.ajax({
                    type: 'POST',
                    url: '{{ route('products.sendfordelovred') }}',
                    cache: false,
                    data: {
                        ids: allids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.',
                                'Orders Has been Send For Delivred Success!', {
                                    "showMethod": "slideDown",
                                    "hideMethod": "slideUp",
                                    timeOut: 2000
                                });
                        }
                    }
                });
            });
            $('#print').click(function(e) {
                e.preventDefault();
                var allids = [];
                $("input:checkbox[name=ids]:checked").each(function() {
                    allids.push($(this).val());
                });
                $.ajax({
                    type: 'POST',
                    url: '{{ route('products.printlable') }}',
                    cache: false,
                    data: {
                        ids: allids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success == true) {
                            toastr.success('Good Job.', 'Print Lables Has been Success!', {
                                "showMethod": "slideDown",
                                "hideMethod": "slideUp",
                                timeOut: 2000
                            });
                        }
                    }
                });
            });

            $('#printall').click(function(e) {
                e.preventDefault();
                var allids = [];
                $("input:checkbox[name=ids]:checked").each(function() {
                    allids.push($(this).val());
                });
                if (confirm("Are you sure, you want to Print List Products?")) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('products.printlable') }}',
                        cache: false,
                        data: {
                            ids: allids,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response, leads) {
                            $.each(allids, function(key, val, leads) {
                                var a = JSON.stringify(allids);
                                window.location = ('/products/print-product-list/' + a);
                            });
                        }
                    });
                } else {
                    alert('Whoops Something went wrong!!');
                }
            });
        });
    </script>
    <script type="text/javascript">
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview')
        });
        scanner.addListener('scan', function(content) {
            console.log(content);
            $.ajax({
                type: 'POST',
                url: '{{ route('products.sendordertoship') }}',
                cache: false,
                data: {
                    bar: content,
                    _token: '{{ csrf_token() }}',

                },
                success: function(response) {
                    if (response.success == true) {
                        toastr.success('Good Job.', 'Lead Updated Success!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                    if (response.error == false) {
                        toastr.error('Oppps.', 'Not find this Order!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                }
            });
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
        });
    </script>
    <script src="{{ asset('./public/html5-qrcode.min.js') }}"></script>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            var bar = `${decodedText}`;
            $.ajax({
                type: 'POST',
                url: '{{ route('products.sendordertoship') }}',
                cache: false,
                data: {
                    bar: bar,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.success == true) {
                        toastr.success('Good Job.', 'Lead Updated Success!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                    if (response.error == false) {
                        toastr.error('Oppps.', 'Not find this Order!', {
                            "showMethod": "slideDown",
                            "hideMethod": "slideUp",
                            timeOut: 2000
                        });
                    }
                }
            });
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "preview", {
                fps: 50,
                qrbox: 150
            });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
@endsection
