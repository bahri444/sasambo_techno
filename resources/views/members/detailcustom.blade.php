@extends('layout.template')
@section('content')
<div class="content" id="datamains" data-loading="true">
    <div class="mt-5 col-lg-10 col-md-11 col-sm-12 mx-auto">
        <div class="card-body">
            <div class="row">
                <!-- alert error or success-->
                <div>
                    @if(session('success'))
                    <p class="alert alert-success">{{ session('success') }}</p>
                    @endif
                    @if($errors->any())
                    @foreach($errors->all() as $err)
                    <p class="alert alert-danger">{{ $err }}</p>
                    @endforeach
                    @endif
                </div>
                <!-- end-alert -->
                @foreach($data_produk_custom as $val)
                @if($val->procus_id == $data_produk_custom_id)
                <!-- tambahkan produk custom ke keranjang -->
                <div class="col-md-12 mx-auto">
                    <!-- <div class="form"> -->
                    <form data-url="{{route('checkoutNow')}}" id="FormBeliLangsung">
                        <div class="modal-body">
                            <div class="row row-cols-1 row-cols-md-2 g-4">
                                <!-- code read image -->
                                <div class="col-lg-3 col-md-4 col-sm-4">
                                    <!-- menampilkan foto depan produk -->
                                    <img id="main-image" class="d-block w-100" src="/foto_produk/depan/{{$val->foto_dep}}" alt="404" height="400px" alt="404">
                                    <div class="scrollmenu d-flex mt-1">
                                        <img id="d{{$val->foto_dep}}" src="/foto_produk/depan/{{$val->foto_dep}}" class="d-block w-100" height="100px" onclick="return tampil('/foto_produk/depan/<?= $val->foto_dep; ?>')">
                                        <img id="b{{$val->foto_bel}}" src="/foto_produk/belakang/{{$val->foto_bel}}" class="d-block w-100" height="100px" onclick="return tampil('/foto_produk/belakang/<?= $val->foto_bel; ?>')">
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-8 col-sm-8">
                                    <div class="shadow-none">
                                        <div class="card-body">
                                            <!-- input data id prdk custom dan get harga produk-->
                                            <div class="col">
                                                <h5 class="card-title color__green">{{$val->nama_produk}}</h5>
                                                <h6 class="card-title color__green">Rp. {{$val->harga_satuan}}</h6>
                                                <input type="hidden" id="procus_id" value="{{$val->procus_id}}"> <!-- ambil data id produk custom-->
                                                <input type="hidden" name="harga_satuan" value="{{$val->harga_satuan}}" id="harga_satuan"> <!--mengambil harga barang menngunakan input-->
                                            </div>

                                            <!-- menampilkan jenis kain produk -->
                                            <div class="input-group mt-2">
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-lg-2 col-sm-5 col-md-4">
                                                    <h6>Jenis kain</h6>
                                                </div>
                                                <div class="col-lg-4 col-sm-7 col-md-8">
                                                    <p class="card-text">{{$val->jenis_kain}}</p>
                                                </div>
                                            </div>

                                            <!-- menampilkan warna pakaian custom-->
                                            <div class="row mt-3">
                                                <div class="col-lg-2 col-sm-5 col-md-4">
                                                    <h6>Warna</h6>
                                                </div>
                                                <div class="col-lg-4 col-sm-7 col-md-8">
                                                    <p class="card-text">{{$val->nama_warna}}</p>
                                                </div>
                                            </div>
                                            <!-- menampilkan warna pakaian custom-->
                                            <div class="row mt-3">
                                                <div class="col-lg-2 col-sm-5 col-md-4">
                                                    <h6>Jumlah stok</h6>
                                                </div>
                                                <div class="col-lg-4 col-sm-7 col-md-8">
                                                    <p class="card-text">{{$val->jml_stok}} {{$val->satuan}}</p>
                                                </div>
                                            </div>

                                            <!-- input size -->
                                            <div class="row mt-3">
                                                <div class="col-lg-2 col-sm-5 col-md-5">
                                                    <h6>Size</h6>
                                                </div>
                                                <div class="col-lg-4 col-sm-5 col-md-5 justify-content-between">
                                                    <?php $pecahkan = explode(',', $val->size); ?>
                                                    @foreach ($pecahkan as $key)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="size_order" value="{{$key}}" id="size_order">
                                                        <label class="form-check-label" for="inlineRadio1">{{$key}}</label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <!-- input jumlah order -->
                                            <div class="row mt-3">
                                                <div class="col-lg-2 col-sm-5 col-md-4">
                                                    <h6>Jumlah order</h6>
                                                </div>
                                                <div class="col-lg-4 col-sm-7 col-md-8">
                                                    <input type="number" id="jml" name="jumlah_order" class="form-control form-control-sm">
                                                    <input type="hidden" name="harga_satuan" value="{{$val->harga_satuan}}" id="harga_satuan">
                                                </div>
                                            </div>

                                            <!-- total produk -->
                                            <div class="row mt-3">
                                                <div class="col-lg-2 col-sm-5 col-md-4">
                                                    <h6>Total produk</h6>
                                                </div>
                                                <div class="col-lg-4 col-sm-7 col-md-8">
                                                    <div class="form-group mb-0">
                                                        <input type="text" name="harga_totals" id="total" class="form-control form-control-sm" placeholder="Total" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mt-3">
                                        <h6>Deskripsi : </h6>
                                        <p class="card-text style__font">{{$val->deskripsi_kategori_produk_custom}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- tombol beli dan tambahkan ke keranjang -->
                        <div class="d-grid gap-2 col-6 mx-auto mt-1">
                            <button class="btn btn-outline-success" type="button" id="BeliLangsung" data-id="{{$val->procus_id}}"><span><i class="fas fa-dollar-sign"></i></span>Beli</button>
                            <button class="btn btn-outline-warning" type="button" id="ToCart" data-url="{{url('keranjang')}}"><span><i class="fas fa-shopping-cart"></i></span>Add cart</button>
                        </div>
                    </form>
                </div>
                <!-- end modal tambah barang ke keranjang -->
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- modal beli langsung -->
<div class="modal fade" id="pesanNow" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Checkout pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Jasa kirim</label>
                    <select id="kurir_id" class="form-select" aria-label="Default select example">
                        <option selected disabled>pilih jasa kirim</option>
                        @foreach($jasaKirim as $valJakir)
                        <option value="{{$valJakir->kurir_id}}">{{$valJakir->nama_jakir}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Metode pembayaran</label>
                    <select id="payment_id" class="form-select" aria-label="Default select example">
                        <option selected disabled>pilih metode pembayaran</option>
                        @foreach($payment as $payVal)
                        <option value="{{$payVal->payment_id}}">{{$payVal->pay_method}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Leave a comment here" id="t_pesan" style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Comments</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submit-jasa_kirim" class="btn btn-success">Checkout</button>
            </div>
        </div>
    </div>
</div>
<!-- end-modal beli langsung -->

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // function count total harga
    $(document).ready(function() {
        $("#jml, #harga_satuan").keyup(function() {
            var harga_juals = $("#harga_satuan").val(); //get value from input id harga jual
            var jmls = $("#jml").val(); //get jumlah order from input order
            var total = harga_juals * jmls; //count harga jual and jumlah harga
            $("#total").val(total); //return value total to input total
        });
    });

    // ajax tambah ke keranjang
    $('#FormBeliLangsung').on('click', '#ToCart', function() {
        var data = {
            'procus_id': $('#procus_id').val(),
            'size_order': $("input[type='radio'][name='size_order']:checked").val(),
            'jumlah_order': $('#jml').val(),
            'harga_satuan': $('#harga_satuan').val(),
            'harga_totals': $('#total').val(),
        }
        $.ajax({
            url: $(this).attr('data-url'),
            type: "POST",
            data: data,
            success: function(response) {
                if (response.success) {
                    const url = '/cart';
                    pesanSuccess(response.success, url);
                } else if (response.errors) {
                    if (response.errors['data.size_order']) {
                        pesanError(response.errors['data.size_order']);
                    } else if (response.errors['data.jumlah_order']) {
                        pesanError(response.errors['data.jumlah_order']);
                    } else if (response.errors['data.harga_totals']) {
                        pesanError(response.errors['data.harga_totals']);
                    }
                }
            }
        })
    });

    // ajax beli langsung
    $('#FormBeliLangsung').on('click', '#BeliLangsung', function() {
        var data = {
            'procus_id': $('#procus_id').val(),
            'user_id': $('#user_id').val(),
            'size_order': $("input[type='radio'][name='size_order']:checked").val(),
            'jumlah_order': $('#jml').val(),
            'harga_totals': $('#total').val(),
        }
        $('#pesanNow').modal('show');
        $('#pesanNow').on('click', '#submit-jasa_kirim', function() {
            checkOutAll(data);
        });
    });

    // alert pesan success
    function pesanSuccess(pesanSuccess, url) {
        Swal.fire({
            title: 'Berhasil!',
            text: pesanSuccess,
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ya',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                if (url) {
                    window.location.href = url;
                }
            }
        })
    }

    // alert pesan error
    function pesanError(pesanError) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'error',
            title: pesanError
        })
    }

    function checkOutAll(data) {
        let data_jasa = {
            'kurir_id': $('#kurir_id').val(),
            'payment_id': $('#payment_id').val(),
            't_pesan': $('#t_pesan').val(),
        }


        $.ajax({
            url: $('#FormBeliLangsung').attr('data-url'),
            type: "POST",
            data: {
                data,
                data_jasa
            },
            success: function(response) {
                if (response.success) {
                    const url = "/pesanananda";
                    pesanSuccess(response.success, url);

                } else if (response.errors) {
                    if (response.errors['data.size_order']) {
                        pesanError(response.errors['data.size_order']);
                    } else if (response.errors['data.jumlah_order']) {
                        pesanError(response.errors['data.jumlah_order']);
                    } else if (response.errors['data.harga_totals']) {
                        pesanError(response.errors['data.harga_totals']);
                    } else if (response.errors['data_jasa.kurir_id']) {
                        pesanError(response.errors['data_jasa.kurir_id']);
                    } else if (response.errors['data_jasa.payment_id']) {
                        pesanError(response.errors['data_jasa.payment_id']);
                    } else if (response.errors['data_jasa.t_pesan']) {
                        pesanError(response.errors['data_jasa.t_pesan']);
                    }

                }
            }
        })
    }
    // ganti foto produk berdasarkan gambar yang di klik
    function tampil(a) {
        document.getElementById('main-image').removeAttribute('src');
        document.getElementById('main-image').setAttribute('src', a);
    }
</script>
@endsection