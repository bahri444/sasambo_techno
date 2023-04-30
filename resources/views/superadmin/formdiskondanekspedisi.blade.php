@extends('layout.template')
@section('content')
@foreach($pesanan as $row)
@if($pesanan_id == $row->pesanan_id)

<!-- form input diskon -->
<div class="content">
    <div class="card mt-5 col-lg-5 col-md-12 mx-auto shadow-lg">
        <div class="card-body">
            <div class="card-header">
                <h4 class="card-title text-center">Diskon dan ekspedisi</h4>
            </div>
            <div class="row">
                <form action="{{route('ekspedisidandiskon')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <h6>Nama pembeli</h6>
                            </div>
                            <div class="col-5">
                                <p>: {{$row->name}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <h6>Telepon</h6>
                            </div>
                            <div class="col-5">
                                <p>: {{$row->telepon}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <h6>Alamat</h6>
                            </div>
                            <div class="col-8">
                                <h6 class="text text-danger">: {{$row->desa}}, {{$row->kecamatan}}, {{$row->kabupaten}}, {{$row->provinsi}}</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <h6>Jumlah oreder</h6>
                            </div>
                            <div class="col-5">
                                <p>: <?= $row->jml_order . ' ' . $row->satuan ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <?php if ($row->sablon_id == NULL) : ?>
                                <div class="col-4">
                                    <h6>Harga {{$row->nama_produk}}</h6>
                                </div>
                                <div class="col-5">
                                    <p>: <?= $row->harga_satuan, ' / ', $row->satuan ?></p>
                                </div>
                            <?php elseif ($row->sablon_id != NULL) : ?>
                                <div class="col-4">
                                    <h6>Harga satuan sablon</h6>
                                </div>
                                <div class="col-5">
                                    <p>: <?= $row->harga, ' / ', "titik" ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <h6>Jasa kirim</h6>
                            </div>
                            <div class="col-5">
                                <h6 class="text text-success">: {{$row->nama_jakir}}</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <h6>Total produk</h6>
                            </div>
                            <?php if ($row->sablon_id == NULL) : ?>
                                <div class="col-5">
                                    <p>: Rp. <?= $row->jml_order * $row->harga_satuan ?></p>
                                    <input type="hidden" id="total_produk" data-totalproduk="<?= $row->jml_order * $row->harga_satuan ?>" value="<?= $row->jml_order * $row->harga_satuan ?>">
                                </div>
                            <?php elseif ($row->sablon_id != NULL) : ?>
                                <div class="col-5">
                                    <p>: Rp. <?= $row->jml_order * $row->harga ?></p>
                                    <input type="hidden" id="total_produk" data-totalproduk="<?= $row->jml_order * $row->harga ?>" value="<?= $row->jml_order * $row->harga ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <h6 class="mt-3">Berat barang</h6>
                                    <input type="hidden" name="pesanan_id" value="{{$row->pesanan_id}}" class="form-control">
                                    <input type="text" name="berat_paket" id="berat_paket" class="form-control" placeholder="masukkan berat pesanan">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <h6 class="mt-3">Tarif pengiriman</h6>
                                    <input type="text" name="tarif" id="tarif" class="form-control" placeholder="masukkan ongkos kirim {{$row->nama_jakir}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <h6 class="mt-3">Total ekspedisi</h6>
                                    <input type="text" name="total_ekspedisi" id="total_ekspedisi" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <h6 class="mt-3">Persentase diskon %</h6>
                                    <input type="text" name="persentase_diskon" id="persentase_diskon" class="form-control" placeholder="masukkan jumlah diskon">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <h6 class="mt-3">Perolehan diskon</h6>
                                    <input type="text" name="perolehan_diskon" id="perolehan_diskon" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <h6 class="mt-3">Harga setelah diskon</h6>
                                    <input type="text" name="total_diskon" id="total_diskon" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <h6 class="mt-3">Total semua pesanan</h6>
                                    <input type="text" name="total_semua_pesanan" id="total_semua_pesanan" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- button save and back -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="/pesanan" class="btn btn-danger me-md-2"><i class="fas fa-undo"></i> Pesanan</a>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                    <!-- end-button save and back -->
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end-form input diskon -->

@endif
@endforeach
<script>
    $(document).ready(function() {
        $('#berat_paket, #tarif, #total_ekspedisi').keyup(function() {
            let BeratPaket = $('#berat_paket').val();
            let Tarif = $('#tarif').val();
            let TotalEkspedisi = BeratPaket * Tarif;
            $('#total_ekspedisi').val(TotalEkspedisi);
        });
        $('#persentase_diskon, #total_produk, #perolehan_diskon, #total_diskon, #total_ekspedisi, #total_semua_pesanan').keyup(function() {
            let PersentaseDiskon = $('#persentase_diskon').val();
            let TotalProduk = $('#total_produk').val();
            let PerolehanDiskon = (PersentaseDiskon / 100) * (TotalProduk);
            $('#perolehan_diskon').val(PerolehanDiskon);

            let TotalDiskon = (TotalProduk - PerolehanDiskon);
            $('#total_diskon').val(TotalDiskon);

            let GetTarifEkspedisi = $('#total_ekspedisi').val();
            let GetTotalDiskon = $('#total_diskon').val();

            let TotalSemuaPesanan = parseInt(GetTotalDiskon) + parseInt(GetTarifEkspedisi);
            console.log(TotalSemuaPesanan);
            $('#total_semua_pesanan').val(TotalSemuaPesanan);
        });
    })
</script>
@endsection