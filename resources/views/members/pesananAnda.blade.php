@extends('layout.template')
@section('content')
<div class="content">
    <div class="card-header">
        <h4 class="card-title text-center mb-3">Pesanan saya</h4>
    </div>
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

        @foreach($data_pesanan as $pes)
        @if(Auth::user()->id == $pes->user_id && $pes->sablon_id == TRUE)
        <!-- view read transaction sablon -->
        <div class="card">
            <div class="card-body shadow-sm">
                <div class="row align-items-center">
                    <div class="col">
                        <p>{{$pes->name}}</p>
                    </div>
                    <div class="col">
                        <p>Alamat {{$pes->desa}}</p>
                    </div>
                    <div class="col">
                        <p>Sablon. {{$pes->ukuran_sablon}}</p>
                    </div>
                    <div class="col">
                        <p>X. {{$pes->jml_order}}</p>
                    </div>
                    <div class="col">
                        <p>Rp. {{$pes->harga}} / titik</p>
                    </div>
                    @if($pes->pay_status == "pending")
                    <div class="col">
                        <p class="text text-danger">lakukan pembayaran terlebih dahulu</p>
                    </div>
                    @elseif($pes->pay_status == "verifikasi")
                    <div class="col">
                        <p class="text text-success">proses verifikasi</p>
                    </div>
                    @elseif($pes->pay_status != "pending" && $pes->pay_status != "verifikasi")
                    <div class="col">
                        <p class="text text-success">pesanan {{$pes->status_pesanan}}</p>
                    </div>
                    @endif
                    <div class="col">
                        <p>{{$pes->tgl_order}}</p>
                    </div>
                    <?php if ($pes->getEkspedisiDanDiskon == NULL) : ?>
                        @if($pes->b_dp != NULL && $pes->b_lunas == NULL)
                        <div class="col">
                            <h6 class="text text-danger">Sisa bayar</h6>
                        </div>
                        <div class="col">
                            <p class="text text-danger">: Rp. <?= $sisa = ($pes->jml_order * $pes->harga) - $pes->jml_dp ?></p>
                        </div>
                        @elseif($pes->b_dp == TRUE && $pes->b_lunas == TRUE)
                        <div class="col">
                            <h6 class="text text-success">Lunas</h6>
                        </div>
                        <div class="col">
                            <p class="text text-success">: Rp. <?= $sisa = ($pes->jml_order * $pes->harga) - ($pes->jml_dp + $pes->jml_lunas) ?></p>
                        </div>
                        @endif
                    <?php elseif ($pes->getEkspedisiDanDiskon != NULL) : ?>
                        @if($pes->b_dp != NULL && $pes->b_lunas == NULL)
                        <div class="col">
                            <h6 class="text text-danger">Sisa bayar</h6>
                        </div>
                        <div class="col">
                            <p class="text text-danger">: Rp. <?= $sisa = $pes->getEkspedisiDanDiskon->total_semua_pesanan - $pes->jml_dp ?></p>
                        </div>
                        @elseif($pes->b_dp == TRUE && $pes->b_lunas == TRUE)
                        <div class="col">
                            <h6 class="text text-success">Lunas</h6>
                        </div>
                        <div class="col">
                            <p class="text text-success">: Rp. <?= $sisa = $pes->getEkspedisiDanDiskon->total_semua_pesanan - ($pes->jml_dp + $pes->jml_lunas) ?></p>
                        </div>
                        @endif
                    <?php endif; ?>
                </div>

                <!-- tombol bayar dan detail -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalInfoSablon{{$pes->pesanan_id}}">
                        <i class="fas fa-info"></i>
                        Detail
                    </button>
                    @if(($pes->status_pesanan == 'pending')||($pes->status_pesanan == 'diterima'))
                    @if($pes->pay_method == 'Cash' || $pes->b_dp != NULL)
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBayarLunas{{$pes->pesanan_id}}">
                        <i class="fas fa-dollar-sign"></i>
                        Bayar
                    </button>
                    @elseif($pes->pay_method != 'Cash')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalBayar{{$pes->pesanan_id}}">
                        <i class="fas fa-dollar-sign"></i>
                        Bayar dp
                    </button>
                    @endif
                    @elseif($pes->status_pesanan == 'kirim')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#KonfirmasiSelesai{{$pes->pesanan_id}}">
                        <i class="fas fa-check"></i>
                        Konfirmasi
                    </button>
                    @elseif($pes->status_pesanan == 'selesai')
                    <a href="/home" class="btn btn-outline-success">Beli lagi</a>
                    @endif
                </div>
            </div>
        </div>
        <!-- end view read transaction sablon -->

        <!-- modal info transaction sablon -->
        <div class="modal fade" id="modalInfoSablon{{$pes->pesanan_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail pesanan sablon {{$pes->ukuran_sablon}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Nama pembeli</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$pes->name}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Ukuran sablon</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$pes->ukuran_sablon}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Jasa kirim</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$pes->nama_jakir}} {{$pes->jenis_jakir}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Metode pembayaran</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$pes->pay_method}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Jumlah order</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$pes->jml_order}} / titik</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Harga satuan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$pes->harga}}</p>
                            </div>
                        </div>
                        @foreach($instansi as $perusahaan)
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Rekening sasambo techno</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$perusahaan->billing}}</p>
                            </div>
                        </div>
                        @endforeach

                        <!-- logic diskon -->
                        <?php if ($pes->getEkspedisiDandiskon != NULL) : ?>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Harga total</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. <?= $total_harga = ($pes->jml_order * $pes->harga) ?> </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Berat paket</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: {{$pes->getEkspedisiDandiskon->berat_paket}} - {{$pes->getEkspedisiDandiskon->satuan_berat}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Tarif pengiriman</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$pes->getEkspedisiDandiskon->tarif}} / {{$pes->getEkspedisiDandiskon->satuan_berat}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Total Biaya Ekspedisi</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$pes->getEkspedisiDandiskon->total_ekspedisi}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Persentase diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: {{$pes->getEkspedisiDandiskon->persentase_diskon}} %</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Perolehan diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$pes->getEkspedisiDandiskon->perolehan_diskon}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Harga setelah diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$pes->getEkspedisiDandiskon->total_diskon}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Total tagihan pesanan</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <h6 class="text text-danger">: Rp. {{$pes->getEkspedisiDandiskon->total_semua_pesanan}}</h6>
                                </div>
                            </div>
                        <?php elseif ($pes->getEkspedisiDandiskon == NULL) : ?>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Harga total</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. <?= $total_harga = ($pes->jml_order * $pes->harga) ?> </p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- end-logic diskon -->

                        <!-- status pesanan -->
                        <div class="row">
                            @if($pes->status_pesanan == 'diterima')
                            @if($pes->stts_produksi == 'pending')
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pesanan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"menunggu pengerjaan"}}</p>
                            </div>
                            @elseif($pes->stts_produksi == 'produksi')
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pesanan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"sedang di kerjakan"}}</p>
                            </div>
                            @elseif($pes->stts_produksi == 'packing')
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pesanan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"sedang di packing"}}</p>
                            </div>
                            @elseif($pes->stts_produksi == 'selesai')
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pesanan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"menunggu jadwal pengiriman"}}</p>
                            </div>
                            @endif
                            @elseif($pes->status_pesanan == 'kirim')
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pesanan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"di kirim oleh, "}}{{$pes->nama_jakir}}</p>
                            </div>
                            @endif
                        </div>
                        <!-- end-status pesanan -->

                        <!-- kondisi untuk mengecek apakah sudah ada pembayaran atau tidak -->
                        <div class="row">
                            @if($pes->pay_status == "pending")
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pembayaran</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-danger">: {{"lakukan pembayaran terlebih dahulu"}}</p>
                            </div>
                            @elseif($pes->pay_status == "verifikasi")
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pembayaran</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"proses verifikasi"}}</p>
                            </div>
                            @elseif($pes->pay_status == "belum lunas")
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pembayaran</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"lakukan pembayaran kedua untuk melunasi pesanan anda"}}</p>
                            </div>
                            @endif
                        </div>
                        <!-- end-kondisi untuk mengecek apakah sudah ada pembayaran atau tidak -->

                        <!-- kondisi untuk cek jumlah dp dan lunas -->
                        <?php if ($pes->getEkspedisiDanDiskon == NULL) : ?>
                            @if($pes->b_dp != NULL && $pes->b_lunas == NULL)
                            <div class="row">
                                <div class="col">
                                    <h6 class="text text-danger">Sisa bayar</h6>
                                </div>
                                <div class="col">
                                    <p class="text text-danger">: Rp. <?= $sisa = ($pes->jml_order * $pes->harga) - $pes->jml_dp ?></p>
                                </div>
                            </div>
                            <div class="d-grid gap-1 col-6 mx-auto">
                                <button type="button" class="btn btn-outline-success"><i class="fas fa-file-pdf"></i>Cetak Invoice</button>
                            </div>
                            @elseif($pes->b_dp == TRUE && $pes->b_lunas == TRUE)
                            <div class="row">
                                <div class="col">
                                    <h6 class="text text-success">Lunas</h6>
                                </div>
                                <div class="col">
                                    <p class="text text-success">: Rp. <?= $sisa = ($pes->jml_order * $pes->harga) - ($pes->jml_dp + $pes->jml_lunas) ?></p>
                                </div>
                            </div>
                            <div class="d-grid gap-1 col-6 mx-auto">
                                <button type="button" class="btn btn-outline-success"><i class="fas fa-file-pdf"></i>Cetak Invoice</button>
                            </div>
                            @endif
                        <?php elseif ($pes->getEkspedisiDanDiskon != NULL) : ?>
                            @if($pes->b_dp != NULL && $pes->b_lunas == NULL)
                            <div class="row">
                                <div class="col">
                                    <h6 class="text text-danger">Sisa bayar</h6>
                                </div>
                                <div class="col">
                                    <p class="text text-danger">: Rp. <?= $sisa = $pes->getEkspedisiDanDiskon->total_semua_pesanan - $pes->jml_dp ?></p>
                                </div>
                            </div>
                            <div class="d-grid gap-1 col-6 mx-auto">
                                <button type="button" class="btn btn-outline-success"><i class="fas fa-file-pdf"></i>Cetak Invoice</button>
                            </div>
                            @elseif($pes->b_dp == TRUE && $pes->b_lunas == TRUE)
                            <div class="row">
                                <div class="col">
                                    <h6 class="text text-success">Lunas</h6>
                                </div>
                                <div class="col">
                                    <p class="text text-success">: Rp. <?= $sisa = $pes->getEkspedisiDanDiskon->total_semua_pesanan - ($pes->jml_dp + $pes->jml_lunas) ?></p>
                                </div>
                            </div>
                            <div class="d-grid gap-1 col-6 mx-auto">
                                <button type="button" class="btn btn-outline-success"><i class="fas fa-file-pdf"></i>Cetak Invoice</button>
                            </div>
                            @endif
                        <?php endif; ?>
                        <!-- end-kondisi untuk cek jumlah dp dan lunas -->
                    </div>
                </div>
            </div>
        </div>
        <!-- end modal info transaction sablon -->
        @endif
        @endforeach
    </div>

    <div class="row">
        @foreach($data_pesanan as $vals)
        @if(Auth::user()->id == $vals->user_id && $vals->procus_id == TRUE)

        <!-- view read transaction pakaian custom -->
        <div class="card">
            <div class="card-body shadow-sm">
                <div class="row align-items-center">
                    <div class="col">
                        <img src="/foto_produk/depan/{{$vals->foto_dep}}" width="100px" height="130px" alt="404">
                    </div>
                    <div class="col">
                        <p>{{$vals->name}}</p>
                    </div>
                    <div class="col">
                        <p>{{$vals->nama_produk}}</p>
                    </div>
                    <div class="col">
                        <p>Warna. {{$vals->nama_warna}}</p>
                    </div>
                    <div class="col">
                        <p>Size. {{$vals->size_orders}}</p>
                    </div>
                    <div class="col">
                        <p>X. {{$vals->jml_order}}</p>
                    </div>
                    @if($vals->pay_status == "pending")
                    <div class="col">
                        <p class="text text-danger">lakukan pembayaran terlebih dahulu</p>
                    </div>
                    @elseif($vals->pay_status == "verifikasi")
                    <div class="col">
                        <p class="text text-success">proses verifikasi</p>
                    </div>
                    @elseif($vals->pay_status != "pending" && $vals->pay_status != "verifikasi")
                    <div class="col">
                        <p class="text text-success">pesanan {{$vals->status_pesanan}}</p>
                    </div>
                    @endif
                    <div class="col">
                        <p>{{$vals->tgl_order}}</p>
                    </div>

                    <!-- logic chreck discount dan ekspedisi -->
                    <?php if ($vals->getEkspedisiDanDiskon == NULL) : ?>
                        @if($vals->b_dp != NULL && $vals->b_lunas == NULL)
                        <div class="col">
                            <h6 class="text text-danger">Sisa bayar</h6>
                        </div>
                        <div class="col">
                            <p class="text text-danger">: Rp. <?= $sisa = ($vals->jml_order * $vals->harga_satuan) - $vals->jml_dp ?></p>
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
                        @if($vals->b_dp != NULL && $vals->b_lunas == NULL)
                        <div class="col">
                            <h6 class="text text-danger">Sisa bayar</h6>
                        </div>
                        <div class="col">
                            <p class="text text-danger">: Rp. <?= $sisa = $vals->getEkspedisiDanDiskon->total_semua_pesanan - $vals->jml_dp ?></p>
                        </div>
                        @elseif($vals->b_dp == TRUE && $vals->b_lunas == TRUE)
                        <div class="col">
                            <h6 class="text text-success">Lunas</h6>
                        </div>
                        <div class="col">
                            <p class="text text-success">: Rp. <?= $sisa = $vals->getEkspedisiDanDiskon->total_semua_pesanan - ($vals->jml_dp + $vals->jml_lunas) ?></p>
                        </div>
                        @endif
                    <?php endif; ?>
                    <!-- end-logic chreck discount dan ekspedisi -->

                    <!-- tombol pembayaran dan info -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalInfoProduks{{$vals->pesanan_id}}">
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

        <!-- modal info transaction pesanan pakaian custom -->
        <div class="modal fade" id="modalInfoProduks{{$vals->pesanan_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail pesanan {{$vals->nama_produk}}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Nama pembeli</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$vals->name}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Jasa kirim</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$vals->nama_jakir}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Metode pembayaran</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$vals->pay_method}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Harga satuan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$vals->harga_satuan}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Jumlah order</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$vals->jml_order}}/{{$vals->satuan}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Tanggal order</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$vals->tgl_order}}</p>
                            </div>
                        </div>
                        @foreach($instansi as $perusahaan)
                        <div class="row">
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                <h6>Rekening sasambo techno</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                <p>: {{$perusahaan->billing}}</p>
                            </div>
                        </div>
                        @endforeach
                        <!-- logic diskon -->
                        <?php if ($vals->getEkspedisiDandiskon != NULL) : ?>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Harga total</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. <?= $total_harga = ($vals->jml_order * $vals->harga_satuan) ?></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Berat paket</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: {{$vals->getEkspedisiDandiskon->berat_paket}} - {{$vals->getEkspedisiDandiskon->satuan_berat}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Tarif pengiriman</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$vals->getEkspedisiDandiskon->tarif}} / {{$vals->getEkspedisiDandiskon->satuan_berat}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Total Biaya Ekspedisi</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$vals->getEkspedisiDandiskon->total_ekspedisi}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Persentase diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: {{$vals->getEkspedisiDandiskon->persentase_diskon}} %</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Perolehan diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$vals->getEkspedisiDandiskon->perolehan_diskon}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Harga setelah diskon</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. {{$vals->getEkspedisiDandiskon->total_diskon}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Total tagihan pesanan</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <h6 class="text text-danger">: Rp. {{$vals->getEkspedisiDandiskon->total_semua_pesanan}}</h6>
                                </div>
                            </div>
                        <?php elseif ($vals->getEkspedisiDandiskon == NULL) : ?>
                            <div class="row">
                                <div class="col-md-4 col-lg-6 col-sm-6 mb-1">
                                    <h6>Harga total</h6>
                                </div>
                                <div class="col-md-3 col-lg-5 col-sm-6 mb-1">
                                    <p>: Rp. <?= $total_harga = ($vals->jml_order * $vals->harga_satuan) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!-- end-logic diskon -->

                        <!-- status pesanan -->
                        <div class="row">
                            @if($vals->status_pesanan == 'diterima')
                            @if($vals->stts_produksi == 'pending')
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pesanan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"menunggu pengerjaan"}}</p>
                            </div>
                            @elseif($vals->stts_produksi == 'produksi')
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pesanan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"sedang di kerjakan"}}</p>
                            </div>
                            @elseif($vals->stts_produksi == 'packing')
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pesanan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"sedang di packing"}}</p>
                            </div>
                            @elseif($vals->stts_produksi == 'selesai')
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pesanan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"menunggu jadwal pengiriman"}}</p>
                            </div>
                            @endif
                            @elseif($vals->status_pesanan == 'kirim')
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pesanan</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"di kirim oleh, "}}{{$vals->nama_jakir}}</p>
                            </div>
                            @endif
                        </div>
                        <!-- end-status pesanan -->

                        <!-- kondisi untuk mengecek apakah sudah ada pembayaran atau tidak -->
                        <div class="row">
                            @if($vals->pay_status == "pending")
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pembayaran</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-danger">: {{"lakukan pembayaran terlebih dahulu"}}</p>
                            </div>
                            @elseif($vals->pay_status == "verifikasi")
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pembayaran</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"proses verifikasi"}}</p>
                            </div>
                            @elseif($vals->pay_status == "belum lunas")
                            <div class="col-md-4 col-lg-6 col-sm-6 mb-3">
                                <h6>Status pembayaran</h6>
                            </div>
                            <div class="col-md-3 col-lg-5 col-sm-6 mb-3">
                                <p class="text text-success">: {{"lakukan pembayaran kedua untuk melunasi pesanan anda"}}</p>
                            </div>
                            @endif
                        </div>
                        <!-- end-kondisi untuk mengecek apakah sudah ada pembayaran atau tidak -->

                        <!-- kondisi untuk cek jumlah dp dan lunas -->
                        <!-- logic chreck discount dan ekspedisi -->
                        <?php if ($vals->getEkspedisiDanDiskon == NULL) : ?>
                            @if($vals->b_dp != NULL && $vals->b_lunas == NULL)
                            <div class="row">
                                <div class="col">
                                    <h6 class="text text-danger">Sisa bayar</h6>
                                </div>
                                <div class="col">
                                    <p class="text text-danger">: Rp. <?= $sisa = ($vals->jml_order * $vals->harga_satuan) - $vals->jml_dp ?></p>
                                </div>
                            </div>
                            <div class="d-grid gap-1 col-6 mx-auto">
                                <button type="button" class="btn btn-outline-success"><i class="fas fa-file-pdf"></i>Cetak Invoice</button>
                            </div>
                            @elseif($vals->b_dp == TRUE && $vals->b_lunas == TRUE)
                            <div class="row">
                                <div class="col">
                                    <h6 class="text text-success">Lunas</h6>
                                </div>
                                <div class="col">
                                    <p class="text text-success">: Rp. <?= $lunas = ($vals->jml_order * $vals->harga_satuan) - ($vals->jml_dp + $vals->jml_lunas) ?></p>
                                </div>
                            </div>
                            <div class="d-grid gap-1 col-6 mx-auto">
                                <button type="button" class="btn btn-outline-success"><i class="fas fa-file-pdf"></i>Cetak Invoice</button>
                            </div>
                            @endif
                        <?php elseif ($vals->getEkspedisiDanDiskon != NULL) : ?>
                            @if($vals->b_dp != NULL && $vals->b_lunas == NULL)
                            <div class="row">
                                <div class="col">
                                    <h6 class="text text-danger">Sisa bayar</h6>
                                </div>
                                <div class="col">
                                    <p class="text text-danger">: Rp. <?= $sisa = $vals->getEkspedisiDanDiskon->total_semua_pesanan - $vals->jml_dp ?></p>
                                </div>
                            </div>
                            <div class="d-grid gap-1 col-6 mx-auto">
                                <button type="button" class="btn btn-outline-success"><i class="fas fa-file-pdf"></i>Cetak Invoice</button>
                            </div>
                            @elseif($vals->b_dp == TRUE && $vals->b_lunas == TRUE)
                            <div class="row">
                                <div class="col">
                                    <h6 class="text text-success">Lunas</h6>
                                </div>
                                <div class="col">
                                    <p class="text text-success">: Rp. <?= $sisa = $vals->getEkspedisiDanDiskon->total_semua_pesanan - ($vals->jml_dp + $vals->jml_lunas) ?></p>
                                </div>
                            </div>
                            <div class="d-grid gap-1 col-6 mx-auto">
                                <button type="button" class="btn btn-outline-success"><i class="fas fa-file-pdf"></i>Cetak Invoice</button>
                            </div>
                            @endif
                        <?php endif; ?>
                        <!-- end-logic chreck discount dan ekspedisi -->
                        <!-- end-kondisi untuk cek jumlah dp dan lunas -->

                    </div>
                </div>
            </div>
        </div>
        <!-- end modal info transaction pesanan pakaian custom -->

        @endif
        @endforeach
    </div>

    @foreach($data_pesanan as $data)
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
    @endforeach
</div>
@endsection