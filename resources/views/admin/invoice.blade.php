<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <style>
        .invoice-box {
            max-width: 700px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 4px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 10px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #0FAA5D;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }

        .all__tombol {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        a {
            text-decoration: none;
            color: black;
            font-size: 18px;
        }

        a:hover {
            color: #0FAA5D;
        }

        .tombol {
            max-width: 160px;
        }
    </style>
    <link rel="stylesheet" href="{{asset('assets')}}/css/pages/fontawesome.css">
    <title>{{$title}}</title>
</head>

<body>
    <div class="invoice-box">
        @foreach($pesanan as $val)
        @if($id == $val->pesanan_id)
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('logo/1681150246.png')))}}" style="width: 100%; max-width: 150px" />
                            </td>
                            <td>
                                Invoice : STCustom000{{$val->pesanan_id}}<br />
                                Tanggal pemesanan : {{$val->tgl_order}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Nama produk : {{$val->nama_produk ?? "sablon"}} {{$val->size_orders ?? $val->ukuran_sablon}}<br />
                                Nama customer : {{$val->name}}<br />
                                Alamat : {{$val->desa}}, {{$val->kecamatan}}, {{$val->kabupaten}}, {{$val->provinsi}}
                            </td>

                            <td>
                                Kasir sasambotechno<br />
                                {{$val->name}}<br />
                                {{$val->email}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- produk -->
            <tr class="heading">
                <td>Produk</td>
                <td></td>
            </tr>
            <tr class="item">
                <td>Jumlah order</td>
                <td>X.{{$val->jml_order}}</td>
            </tr>
            <tr class="item">
                <td>Size order</td>
                <td>{{$val->size_orders ?? $val->ukuran_sablon}}</td>
            </tr>
            <tr class="item">
                <td>Harga satuan</td>
                <td>Rp. {{$val->harga_satuan ?? $val->harga}}</td>
            </tr>
            <tr class="item">
                <td>Total produk</td>
                <td>
                    @if($val->procus_id != true)
                    Rp. {{$val->jml_order * $val->harga}}
                    @elseif($val->procus_id == true)
                    Rp. {{$val->jml_order * $val->harga_satuan}}
                    @endif
                </td>
            </tr>
            <!-- end-produk -->

            <tr class="heading">
                <td>Metode pembayaran</td>
                <td></td>
            </tr>
            <tr class="item">
                <td>Metode pembayaran dipilih</td>
                <td>{{$val->pay_method}}</td>
            </tr>

            <?php if ($val->getEkspedisiDanDiskon != NULL) : ?>
                <tr class="heading">
                    <td>Nama jasa kirim</td>
                    <td>{{$val->nama_jakir}}</td>
                </tr>
                <tr class="item">
                    <td>Berat paket</td>
                    <td>{{$val->getEkspedisiDanDiskon->berat_paket}} {{$val->getEkspedisiDanDiskon->satuan_berat}}</td>
                </tr>
                <tr class="item">
                    <td>Tarif pengiriman</td>
                    <td>Rp. {{$val->getEkspedisiDanDiskon->tarif}} / {{$val->getEkspedisiDanDiskon->satuan_berat}}</td>
                </tr>
                <tr class="item">
                    <td>Total Tarif pengiriman</td>
                    <td>Rp. {{$val->getEkspedisiDanDiskon->total_ekspedisi}}</td>
                </tr>
                <tr class="heading">
                    <td>Diskon</td>
                    <td>%</td>
                </tr>
                <tr class="item">
                    <td>Persentase diskon</td>
                    <td>{{$val->getEkspedisiDanDiskon->persentase_diskon}} %</td>
                </tr>
                <tr class="item">
                    <td>Total produk</td>
                    <td>Rp. {{$val->harga_total}}</td>
                </tr>
                <tr class="item">
                    <td>Diskon harga</td>
                    <td>Rp. {{$val->getEkspedisiDanDiskon->perolehan_diskon}}</td>
                </tr>
                <tr class="item last">
                    <td>Produk setelah diskon</td>
                    <td>Rp. {{$val->getEkspedisiDanDiskon->total_diskon}}</td>
                </tr>
                <tr class="total">
                    <td></td>
                    <td>Total tagihan: Rp. {{$val->getEkspedisiDanDiskon->total_semua_pesanan}}</td>
                </tr>
                <?php if (($val->b_dp != NULL && $val->b_lunas != NULL) || ($val->b_dp == NULL && $val->b_lunas != NULL)) : ?>
                    <tr style="color:#0FAA5D;">
                        <td>Lunas</td>
                        <td>Rp. {{$val->jml_dp + $val->jml_lunas}}</td>
                    </tr>
                <?php elseif ($val->b_dp != NULL && $val->b_lunas == NULL) : ?>
                    <tr style="color:#0FAA5D;">
                        <td>Jumlah DP</td>
                        <td>Rp. {{$val->jml_dp}}</td>
                    </tr>
                    <tr style="color: brown">
                        <td>Sisa bayar</td>
                        <td>Rp. {{$val->getEkspedisiDanDiskon->total_semua_pesanan - $val->jml_dp}}</td>
                    </tr>
                <?php endif; ?>
            <?php elseif ($val->getEkspedisiDanDiskon == NULL) : ?>
                <?php if (($val->b_dp != NULL && $val->b_lunas != NULL) || ($val->b_dp == NULL && $val->b_lunas != NULL)) : ?>
                    <tr style="color:#0FAA5D;">
                        <td>Lunas</td>
                        <td>Rp. {{$val->jml_dp + $val->jml_lunas}}</td>
                    </tr>
                <?php elseif ($val->b_dp != NULL && $val->b_lunas == NULL) : ?>
                    <tr style="color:#0FAA5D;">
                        <td>Jumlah DP</td>
                        <td>Rp. {{$val->jml_dp}}</td>
                    </tr>
                    <tr style="color: brown">
                        <td>Sisa bayar</td>
                        <td>
                            @if($val->procus_id != NULL)
                            <?= (($val->harga_satuan * $val->jml_order) - $val->jml_dp) ?>
                            @elseif($val->procus_id == NULL)
                            Rp. <?= (($val->harga * $val->jml_order) - $val->jml_dp) ?>
                            @endif
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
        </table>

        <!-- logic tombol -->
        <?php if (($val->b_dp != NULL && $val->b_lunas != NULL) || ($val->b_dp == NULL && $val->b_lunas != NULL) || ($val->b_dp != NULL && $val->b_lunas == NULL)) : ?>
            <div class="all__tombol">
                @if (Auth::user()->role == "kasir")
                <div class="tombol">
                    <a href="{{url('/pesanan')}}"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                <div class="tombol">
                    <a href="{{url('/pesanan')}}" onclick="window.print()"><i class="fas fa-print"></i> Cetak invoice</a>
                </div>
                <div class="tombol">
                    <a href="{{ url()->current() . '?output=pdf'}}"><i class="fas fa-file-pdf"></i> Print PDF</a>
                </div>
                @elseif (Auth::user()->role == "pelanggan")
                <div class="tombol">
                    <a href="{{url('/pesanananda')}}"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
                <div class="tombol">
                    <a href="{{ url()->current() . '?output=pdf'}}"><i class="fas fa-file-pdf"></i> Print PDF</a>
                </div>
                @endif
            </div>
        <?php elseif ($val->b_dp == NULL && $val->b_lunas == NULL) : ?>
            <div>
                <p style="color:brown;">belum membayar</p>
            </div>
            <div class="all__tombol">
                <div class="tombol">
                    <a href="{{url('/pesanan')}}"><i class="fas fa-arrow-left"></i> Ke pesanan</a>
                </div>
            </div>
        <?php endif; ?>
        @endif
        @endforeach
    </div>
</body>

</html>