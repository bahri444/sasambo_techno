@extends('layout.template')
@section('content')
<div class="content">
    <div class="card-header">
        <h4 class="card-title text-center mb-3">Pesanan saya</h4>
    </div>
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

    <div class="row">
        @foreach($data_pesanan as $vals)
        @if(Auth::user()->id == $vals->user_id)

        <!-- view read transaction pakaian custom -->
        <div class="card">
            <div class="card-body shadow-sm">
                <div class="row align-items-center">
                    @if($vals->procus_id !=NULL)
                    <div class="col">
                        <img src="/foto_produk/depan/{{$vals->foto_dep}}" width="100px" height="130px" alt="404">
                    </div>
                    @endif
                    <div class="col">
                        <p>{{$vals->name}}</p>
                    </div>
                    <div class="col">
                        <p>{{$vals->nama_produk ?? "Sablon"}}</p>
                    </div>
                    @if($vals->procus_id !=NULL)
                    <div class="col">
                        <p>Warna. {{$vals->nama_warna}}</p>
                    </div>
                    @endif
                    <div class="col">
                        @if($vals->procus_id !=NULL)
                        <p>Total produk <br> Rp. {{$vals->harga_satuan * $vals->jml_order}}</p>
                        @elseif($vals->procus_id ==NULL)
                        <p>Total produk <br> Rp. {{$vals->harga * $vals->jml_order}}</p>
                        @endif
                    </div>
                    <div class="col">
                        <p>Size. {{$vals->size_orders ?? $vals->ukuran_sablon}}</p>
                    </div>
                    <div class="col">
                        <p>X. {{$vals->jml_order}}</p>
                    </div>
                    <div class="col">
                        <?php if ($vals->pay_status == "pending") : ?>
                            <p class="text text-danger">lakukan pembayaran terlebih dahulu</p>
                        <?php elseif ($vals->pay_status == "verifikasi") : ?>
                            <p class="text text-warning">proses verifikasi</p>
                        <?php elseif ($vals->pay_status != "pending" && $vals->pay_status != "verifikasi") : ?>
                            <p class="text text-success">pesanan {{$vals->status_pesanan}}</p>
                        <?php endif; ?>
                    </div>
                    <div class="col">
                        <p>{{$vals->tgl_order}}</p>
                    </div>

                    <!-- logic check discount dan ekspedisi -->
                    <?php if ($vals->getEkspedisiDanDiskon == NULL) : ?>
                        @if($vals->b_dp != NULL && $vals->b_lunas == NULL)
                        <div class="col">
                            <h6 class="text text-danger">Sisa bayar</h6>
                        </div>
                        <div class="col">
                            <p class="text text-danger">: Rp.
                                @if($vals->harga != NULL)
                                <?= $sisa = ($vals->jml_order * $vals->harga) - $vals->jml_dp ?>
                                @elseif($vals->harga == NULL)
                                <?= $sisa = ($vals->jml_order * $vals->harga_satuan) - $vals->jml_dp ?>
                                @endif
                            </p>
                        </div>
                        <div class="col">
                            <p class="text-danger">lakukan pelunasan dengan nominal yang tertera pada sisa bayar</p>
                        </div>
                        @elseif($vals->b_dp == TRUE && $vals->b_lunas == TRUE)
                        <div class="col">
                            <h6 class="text text-success">Lunas</h6>
                        </div>
                        <div class="col">
                            <p class="text text-success">: Rp. <?= $sisa = ($vals->jml_order * $vals->harga_satuan) - ($vals->jml_dp + $vals->jml_lunas) ?></p>
                        </div>
                        @endif
                    <?php elseif ($vals->getEkspedisiDanDiskon != NULL) : ?>
                        @if($vals->b_dp == NULL && $vals->b_lunas == NULL)
                        <div class="col">
                            <h6 class="text text-danger">Total tagihan</h6>
                        </div>
                        <div class="col">
                            <p class="text text-danger">: Rp. <?= $sisa = $vals->getEkspedisiDanDiskon->total_semua_pesanan ?></p>
                        </div>
                        @elseif($vals->b_dp != NULL && $vals->b_lunas == NULL)
                        <div class="col">
                            <h6 class="text text-danger">Sisa bayar</h6>
                        </div>
                        <div class="col">
                            <p class="text text-danger">: Rp. <?= $sisa = $vals->getEkspedisiDanDiskon->total_semua_pesanan - $vals->jml_dp ?></p>
                        </div>
                        <div class="col">
                            <p class="text-danger">lakukan pelunasan dengan nominal yang tertera pada sisa bayar</p>
                        </div>
                        @elseif(($vals->b_dp != NULL && $vals->b_lunas != NULL) || $vals->b_dp == NULL && $vals->b_lunas != NULL)
                        <div class="col">
                            <h6 class="text text-success">Lunas</h6>
                        </div>
                        <div class="col">
                            <p class="text text-success">: Tagihan Rp. <?= $sisa = $vals->getEkspedisiDanDiskon->total_semua_pesanan - ($vals->jml_dp + $vals->jml_lunas) ?></p>
                        </div>
                        @endif
                    <?php endif; ?>
                    <!-- end-logic check discount dan ekspedisi -->

                    <!-- tombol pembayaran dan info -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalInfoPesananAnda{{$vals->pesanan_id}}">
                            <i class="fas fa-info"></i>
                            Detail
                        </button>
                        @if(($vals->status_pesanan == 'pending')||($vals->status_pesanan == 'diterima'))
                        @if($vals->pay_method == 'Cash' || $vals->b_dp != NULL)
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBayarLunas{{$vals->pesanan_id}}">
                            <i class="fas fa-dollar-sign"></i>
                            Bayar
                        </button>
                        @elseif($vals->pay_method != 'Cash')
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBayar{{$vals->pesanan_id}}">
                            <i class="fas fa-dollar-sign"></i>
                            Bayar dp
                        </button>
                        @endif
                        @elseif($vals->status_pesanan == 'selesai')
                        <a href="/home" class="btn btn-outline-success">Beli lagi</a>
                        @elseif($vals->status_pesanan == 'kirim')
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#KonfirmasiSelesai{{$vals->pesanan_id}}">
                            <i class="fas fa-check"></i>
                            Konfirmasi
                        </button>
                        @endif
                    </div>
                    <!-- end-tombol pembayaran dan info -->
                </div>
            </div>
        </div>
        <!-- end view read transaction pakaian custom dan sablon -->
        @endif
        @endforeach
    </div>

    @foreach($data_pesanan as $data)
    @if (Auth::user()->id == $data->user_id)

    <!-- Modal info pesanan anda -->
    <div class="modal modal-lg" id="modalInfoPesananAnda{{$data->pesanan_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal info pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @csrf
                <div class="modal-body">
                    @if($data->b_dp == TRUE && $data->b_lunas == FALSE)
                    <div class="row row-cols-1 row-cols-md-2 g-2">
                        <div class="col mx-auto">
                            <div class="card">
                                <img src="/pembayaran/bukti_dp/{{$data->b_dp}}" class="card-img-top" alt="404">
                                <div class="card-body">
                                    <p class="text-center">Bukti DP</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- logic bukti pembayaran -->
                    @elseif($data->b_dp == TRUE && $data->b_lunas == TRUE)
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <div class="col">
                            <div class="card">
                                <img src="/pembayaran/bukti_dp/{{$data->b_dp}}" class="card-img-top" alt="404">
                                <div class="card-body">
                                    <p class="text-center">Bukti DP</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <img src="/pembayaran/bukti_lunas/{{$data->b_lunas}}" class="card-img-top" alt="404">
                                <div class="card-body">
                                    <p class="text-center">Bukti Lunas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($data->b_dp == FALSE && $data->b_lunas == TRUE)
                    <div class="row row-cols-1 row-cols-md-2 g-2">
                        <div class="col mx-auto">
                            <div class="card">
                                <img src="/pembayaran/bukti_lunas/{{$data->b_lunas}}" class="card-img-top" alt="404">
                                <div class="card-body">
                                    <p class="text-center">Bukti Lunas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- end-logic bukti pembayaran -->
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <h6>Nama pembeli</h6>
                        </div>
                        <div class="col-md-3 col-lg-8 col-sm-6">
                            <p>: {{$data->name}}</p>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <h6>Telepon</h6>
                        </div>
                        <div class="col-md-3 col-lg-8 col-sm-6">
                            <p>: {{$data->telepon}}</p>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <h6>Alamat pengiriman</h6>
                        </div>
                        <div class="col-md-3 col-lg-8 col-sm-6">
                            <p>: {{$data->desa}}, {{$data->kecamatan}}, {{$data->kabupaten}}, {{$data->provinsi}}</p>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <h6>Jasa kirim</h6>
                        </div>
                        <div class="col-md-3 col-lg-8 col-sm-6">
                            <p>: {{$data->nama_jakir}}</p>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <h6>Metode pembayaran</h6>
                        </div>
                        <div class="col-md-3 col-lg-8 col-sm-6">
                            <p>: {{$data->pay_method}}</p>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <h6>Harga satuan</h6>
                        </div>
                        <!-- logic harga total produk sablon atau pakaian custom -->
                        <div class="col-md-3 col-lg-8 col-sm-6">
                            @if($data->sablon_id == true)
                            <p>: Rp. {{$data->harga}}</p>
                            @elseif($data->sablon_id == NULL)
                            <p>: Rp. {{$data->harga_satuan}}</p>
                            @endif
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <h6>Jumlah order</h6>
                        </div>
                        <div class="col-md-3 col-lg-8 col-sm-6">
                            @if($data->satuan == true)
                            <p>: {{$data->jml_order}}, {{$data->satuan}}</p>
                            @elseif($data->satuan == NULL)
                            <p>: {{$data->jml_order}}, {{"titik"}}</p>
                            @endif
                        </div>
                        <!-- end-logic harga total produk sablon atau pakaian custom -->
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <h6>Tanggal order</h6>
                        </div>
                        <div class="col-md-3 col-lg-8 col-sm-6">
                            <p>: {{$data->tgl_order}}</p>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6">
                            <h6>Total produk</h6>
                        </div>
                        <!-- logic diskon -->
                        <?php if ($data->getEkspedisiDandiskon != NULL) : ?>
                            @if($data->procus_id == NULL)
                            <div class="col-md-3 col-lg-8 col-sm-6">
                                <p>: Rp. <?= $total_harga = ($data->jml_order * $data->harga) ?></p>
                            </div>
                            @elseif($data->procus_id != NULL)
                            <div class="col-md-3 col-lg-8 col-sm-6">
                                <p>: Rp. <?= $total_harga = ($data->jml_order * $data->harga_satuan) ?></p>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <h6>Berat paket</h6>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p>: {{$data->getEkspedisiDandiskon->berat_paket}} - {{$data->getEkspedisiDandiskon->satuan_berat}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <h6>Tarif pengiriman</h6>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p>: Rp. {{$data->getEkspedisiDandiskon->tarif}} / {{$data->getEkspedisiDandiskon->satuan_berat}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <h6>Total Biaya Ekspedisi</h6>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p>: Rp. {{$data->getEkspedisiDandiskon->total_ekspedisi}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <h6>Persentase diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p>: {{$data->getEkspedisiDandiskon->persentase_diskon}} %</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <h6>Perolehan diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p>: Rp. {{$data->getEkspedisiDandiskon->perolehan_diskon}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <h6>Harga setelah diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p>: Rp. {{$data->getEkspedisiDandiskon->total_diskon}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <h6>Total tagihan pesanan</h6>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <h6 class="text text-danger">: Rp. {{$data->getEkspedisiDandiskon->total_semua_pesanan}}</h6>
                                </div>
                            </div>
                            @if($data->b_dp != NULL && $data->b_lunas == NULL)
                            <!-- hitung jumlah dp produk yang didiskon-->
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <h6>Jumlah DP</h6>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p class="text text-success">: Rp. {{$data->jml_dp}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <h6>Sisa bayar</h6>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p class="text text-danger">: Rp. {{($data->getEkspedisiDandiskon->total_semua_pesanan) - ($data->jml_dp)}}</p>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p class="text-danger">lakukan pelunasan dengan nominal yang tertera pada sisa bayar</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-grid gap-1 col-6 mx-auto">
                                    <a href="/pesanan/invoice/{{$data->pesanan_id}}" class="btn btn-outline-success"><i class="fas fa-invoice"></i>Invoice</a>
                                </div>
                            </div>
                            @elseif(($data->b_dp != NULL && $data->b_lunas != NULL) || ($data->b_lunas != NULL))
                            <!-- hitung jumlah lunas produk yang didiskon-->
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-6">
                                    <h6>Lunas</h6>
                                </div>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p class="text text-success">: Rp. {{($data->jml_dp + $data->jml_lunas)}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="d-grid gap-1 col-6 mx-auto">
                                    <a href="/pesanan/invoice/{{$data->pesanan_id}}" class="btn btn-outline-success"><i class="fas fa-invoice"></i>Invoice</a>
                                </div>
                            </div>
                            @endif
                        <?php elseif ($data->getEkspedisiDandiskon == NULL) : ?>
                            <?php if ($data->procus_id == NULL) : ?>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p>: Rp. <?= $total_harga = ($data->jml_order * $data->harga) ?></p>
                                </div>
                                <?php if ($data->b_dp != NULL && $data->b_lunas == NULL) : ?>
                                    <div class="col-md-4 col-lg-4 col-sm-6">
                                        <h6>Jumlah DP</h6>
                                    </div>
                                    <div class="col-md-3 col-lg-8 col-sm-6">
                                        <p class="text text-success">: Rp. <?= $data->jml_dp ?></p>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-6">
                                        <h6 class="text text-danger">Sisa bayar</h6>
                                    </div>
                                    <div class="col-md-3 col-lg-8 col-sm-6">
                                        <p class="text text-danger">: Rp. <?= $sisa = ($data->jml_order * $data->harga) - $data->jml_dp ?></p>
                                    </div>
                                    <div class="col-md-3 col-lg-8 col-sm-6">
                                        <p class="text text-danger">lakukan pelunasan dengan nominal yang tertera pada sisa bayar</p>
                                    </div>
                                    <div class="row">
                                        <div class="d-grid gap-1 col-6 mx-auto">
                                            <a href="/pesanan/invoice/{{$data->pesanan_id}}" class="btn btn-outline-success"><i class="fas fa-invoice"></i>Invoice</a>
                                        </div>
                                    </div>
                                <?php elseif (($data->b_dp != NULL && $data->b_lunas != NULL) || $data->b_lunas != NULL) : ?>
                                    <h6 class="text text-success text-center">Lunas : Rp. <?= $lunas = ($data->jml_dp + $data->jml_lunas) ?></h6>
                                    <div class="row">
                                        <div class="d-grid gap-1 col-6 mx-auto">
                                            <a href="/pesanan/invoice/{{$data->pesanan_id}}" class="btn btn-outline-success"><i class="fas fa-invoice"></i>Invoice</a>
                                        </div>
                                    </div>
                                <?php elseif ($data->b_dp == NULL && $data->b_lunas == NULL) : ?>
                                    <h6 class="text text-danger text-center">Belum Membayar</h6>
                                <?php endif; ?>
                            <?php elseif ($data->procus_id != NULL) : ?>
                                <div class="col-md-3 col-lg-8 col-sm-6">
                                    <p>: Rp. <?= $total_harga = ($data->jml_order * $data->harga_satuan) ?></p>
                                </div>
                                <?php if ($data->b_dp != NULL && $data->b_lunas == NULL) : ?>
                                    <div class="col-md-4 col-lg-4 col-sm-6">
                                        <h6>Jumlah DP</h6>
                                    </div>
                                    <div class="col-md-3 col-lg-8 col-sm-6">
                                        <p class="text text-success">: Rp. <?= $data->jml_dp ?></p>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-6">
                                        <h6 class="text text-danger">Sisa bayar</h6>
                                    </div>
                                    <div class="col-md-3 col-lg-8 col-sm-6">
                                        <p class="text text-danger">: Rp. <?= $sisa = ($data->jml_order * $data->harga_satuan) - $data->jml_dp ?></p>
                                    </div>
                                    <div class="col-md-3 col-lg-8 col-sm-6">
                                        <p class="text text-danger">lakukan pelunasan dengan nominal yang tertera pada sisa bayar</p>
                                    </div>
                                    <div class="row">
                                        <div class="d-grid gap-1 col-6 mx-auto">
                                            <a href="/pesanan/invoice/{{$data->pesanan_id}}" class="btn btn-outline-success"><i class="fas fa-invoice"></i>Invoice</a>
                                        </div>
                                    </div>
                                <?php elseif (($data->b_dp != NULL && $data->b_lunas != NULL) || $data->b_lunas != NULL) : ?>
                                    <h6 class="text text-success text-center">Lunas : Rp. <?= $lunas = ($data->jml_dp + $data->jml_lunas) ?></h6>
                                    <div class="row">
                                        <div class="d-grid gap-1 col-6 mx-auto">
                                            <a href="/pesanan/invoice/{{$data->pesanan_id}}" class="btn btn-outline-success"><i class="fas fa-invoice"></i>Invoice</a>
                                        </div>
                                    </div>
                                <?php elseif ($data->b_dp == NULL && $data->b_lunas == NULL) : ?>
                                    <h6 class="text text-danger text-center">Belum Membayar</h6>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <!-- end-logic diskon -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end-modal info pesanan anda -->

    <!-- Modal bayar dp-->
    <div class="modal fade" id="modalBayar{{$data->pesanan_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pembayaran {{$data->pay_method}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/pesanan/bayar" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <?php if ($data->getEkspedisiDanDiskon == NULL) : ?>
                            <div class="row">
                                <div class="col mb-3">
                                    @if($data->sablon_id == NULL)
                                    <p class="card-text text text-danger">Total tagihan pesanan = Rp. {{$data->jml_order * $data->harga_satuan}}</p>
                                    @elseif($data->sablon_id != NULL)
                                    <p class="card-text text text-danger">Total tagihan pesanan = Rp. {{$data->jml_order * $data->harga}}</p>
                                    @endif
                                </div>
                            </div>
                        <?php elseif ($data->getEkspedisiDanDiskon != NULL) : ?>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Harga total produk</h6>
                                </div>
                                @if($data->sablon_id == NULL)
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. <?= $total_harga = ($data->jml_order * $data->harga_satuan) ?></p>
                                </div>
                                @elseif($data->sablon_id != NULL)
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. <?= $total_harga = ($data->jml_order * $data->harga) ?></p>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Berat paket</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: {{$data->getEkspedisiDandiskon->berat_paket}} - {{$data->getEkspedisiDandiskon->satuan_berat}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Tarif pengiriman</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$data->getEkspedisiDandiskon->tarif}} / {{$data->getEkspedisiDandiskon->satuan_berat}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Total Biaya Ekspedisi</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$data->getEkspedisiDandiskon->total_ekspedisi}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Persentase diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: {{$data->getEkspedisiDandiskon->persentase_diskon}} %</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Perolehan diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$data->getEkspedisiDandiskon->perolehan_diskon}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Harga setelah diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$data->getEkspedisiDandiskon->total_diskon}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Total tagihan pesanan</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <h6 class="text text-danger">: Rp. {{$data->getEkspedisiDandiskon->total_semua_pesanan}}</h6>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="row">
                            <div class="form-group">
                                <input type="hidden" name="pesanan_id" value="{{$data->pesanan_id}}" class="form-control">
                                <p>Jumlah DP</p>
                                <div class="col">
                                    <input type="number" class="form-control" name="jml_dp" placeholder="masukkan jumlah DP">
                                    <input type="hidden" class="form-control" name="pay_status" value="verifikasi">
                                </div>
                            </div>
                            <div class="form-group">
                                <p>Upload bukti DP</p>
                                <div class="col">
                                    <input type="file" class="form-control" name="b_dp">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal bayar dp -->

    <!-- Modal bayar Lunas-->
    <div class="modal fade" id="modalBayarLunas{{$data->pesanan_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pembayaran {{$data->pay_method}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/pesanan/bayarlunas" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group">
                                <input type="hidden" name="pesanan_id" value="{{$data->pesanan_id}}" class="form-control">
                                <?php if ($data->getEkspedisiDanDiskon == NULL) : ?>
                                    <p class="text text-danger">Total tagihan pesanan</p>
                                    <div class="col">
                                        @if($data->sablon_id == NULL)
                                        <input type="number" class="form-control" name="jml_lunas" value="{{($data->jml_order * $data->harga_satuan)-($data->jml_dp)}}">
                                        @elseif($data->sablon_id != NULL)
                                        <input type="number" class="form-control" name="jml_lunas" value="{{($data->jml_order * $data->harga)-($data->jml_dp)}}">
                                        @endif
                                    </div>
                                <?php elseif ($data->getEkspedisiDanDiskon != NULL) : ?>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                            <h6>Harga total produk</h6>
                                        </div>
                                        @if($data->sablon_id == NULL)
                                        <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                            <p>: Rp. <?= $total_harga = ($data->jml_order * $data->harga_satuan) ?></p>
                                        </div>
                                        @elseif($data->sablon_id != NULL)
                                        <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                            <p>: Rp. <?= $total_harga = ($data->jml_order * $data->harga) ?></p>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                            <h6>Berat paket</h6>
                                        </div>
                                        <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                            <p>: {{$data->getEkspedisiDandiskon->berat_paket}} - {{$data->getEkspedisiDandiskon->satuan_berat}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                            <h6>Tarif pengiriman</h6>
                                        </div>
                                        <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                            <p>: Rp. {{$data->getEkspedisiDandiskon->tarif}} / {{$data->getEkspedisiDandiskon->satuan_berat}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                            <h6>Total Biaya Ekspedisi</h6>
                                        </div>
                                        <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                            <p>: Rp. {{$data->getEkspedisiDandiskon->total_ekspedisi}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                            <h6>Persentase diskon</h6>
                                        </div>
                                        <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                            <p>: {{$data->getEkspedisiDandiskon->persentase_diskon}} %</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                            <h6>Perolehan diskon</h6>
                                        </div>
                                        <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                            <p>: Rp. {{$data->getEkspedisiDandiskon->perolehan_diskon}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                            <h6>Harga setelah diskon</h6>
                                        </div>
                                        <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                            <p>: Rp. {{$data->getEkspedisiDandiskon->total_diskon}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                            <h6>Total tagihan pesanan</h6>
                                        </div>
                                        <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                            <h6 class="text text-danger">: Rp. {{$data->getEkspedisiDandiskon->total_semua_pesanan}}</h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <input type="number" class="form-control" name="jml_lunas" value="{{($data->getEkspedisiDandiskon->total_semua_pesanan)-($data->jml_dp)}}">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <h6>Upload bukti lunas</h6>
                                <div class="col">
                                    <input type="file" class="form-control" name="b_lunas">
                                    <input type="hidden" class="form-control" name="pay_status" value="verifikasi">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal bayar lunas -->

    <!-- modal konfirmasi pesanan selesai -->
    <div class="modal fade" id="KonfirmasiSelesai{{$data->pesanan_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi pesanan selesai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="pesanan/pesananselesai" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input type="hidden" value="{{$data->pesanan_id}}" name="pesanan_id" class="form-control">
                                    <h6>Selesaikan pesanan</h6>
                                    <select name="status_pesanan" class="form-select" aria-label="Default select example">
                                        <option value="selesai" selected>Selesai</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>Selesai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal konfirmasi pesanan selesai -->
    @endif
    @endforeach
</div>
@endsection