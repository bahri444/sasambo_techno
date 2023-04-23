@include('layout.header')
<div class="container">
    <!-- view kategori produk custom -->
    <h5 class="text-center mt-3 color__green">
        Kategori produk custom
    </h5>
    <div class="row row-cols-1 row-cols-md-6 g-4 mt-2">
        @foreach($kategoricustom as $ktgr)
        <div class="col">
            <div class="card h-100">
                <a href="detailcustom/{{$ktgr->ktgr_procus_id}}">
                    <div class="card-body p-1">
                        <a href="detailcustom/{{$ktgr->ktgr_procus_id}}" class="text__nodecoration color__green">
                            <img src="/foto_ktgr/{{$ktgr->foto_procus}}" class="card-img-top" alt="404">
                        </a>
                    </div>
                </a>
                <div class="card-footer latar__belakang">
                    <h6 class="color-teks text text-center">{{$ktgr->jenis_procus}}</h6>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- end-view kategori produk custom -->

    <!-- view produk custom -->
    <h5 class="text-center mt-3 mb-2 color__green">
        Produk custom
    </h5>
    <div class="row row-cols-1 row-cols-md-6 g-4">
        @foreach($procus as $pro)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <a id="{{$pro->procus_id}}" href="/detailcustom/{{$pro->procus_id}}">
                    <div class="card-body p-1">
                        <img src="/foto_produk/depan/{{$pro->foto_dep}}" class="card-img-top" alt="404">
                        <a href="/detailcustom/{{$pro->procus_id}}" class="text__nodecoration color__green">{{$pro->nama_produk}}</a>
                        <br>
                        <del style="font-size: 12px;" class="text-danger">Rp. <?= $pro->harga_satuan + 5000 ?></del>
                        <h6 style="color: #0FAA5D;">Rp. {{$pro->harga_satuan}}</h6>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    <!-- end-view kategori produk custom -->

    <!-- view data sablon -->
    <div class="row mt-1">
        <h5 class="text-center mt-3 mb-2 color__green">
            Jasa sablon
        </h5>
        @foreach($sablon as $val)
        @if($val->harga !=0)
        <div class="col">
            <div class="card h-100 card-body">
                <div class="row">
                    <div class="col-5">
                        <p class="color__green">Ukuran</p>
                    </div>
                    <div class="col-7">
                        <p class="card-text">{{$val->ukuran_sablon}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <p class="color__green">Harga</p>
                    </div>
                    <div class="col-7">
                        <p class="card-text">Rp. {{$val->harga}}
                            <input type="hidden" value="{{$val->harga}}">
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    <!-- end-view sablon -->
</div>
@include('layout.footer')