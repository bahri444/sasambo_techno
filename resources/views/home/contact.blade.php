@include('layout.header')
<div class="container">
    <div class="text-center mt-3 mb-2">
        <h2 class="color__green">Contact Us</h2>
        <p class=" color__green">Jika ada kritik dan saran</p>
    </div>
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
    <div class="card-group">
        @foreach($instansi as $inst)
        <div class="card">
            <div class="card-body">
                <iframe src="{{$inst->alamat}}" width="600" height="450" style="border:10;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
        <div class="card">
            <form action="/contactus/add" method="post">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label color__green">Nama lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap" placeholder="masukkan nama" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label color__green">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="masuklkan email" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label color__green">WhatsApp</label>
                        <input type="text" class="form-control" name="telepon" placeholder="mamsukkan telepon / whatsapp" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label color__green">Pesan / saran</label>
                        <textarea class="form-control" name="saran" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <div class="d-flex flex-row-reverse bd-highlight size__font">
                        <button type="submit" class="btn btn-success">
                            <i class="uil uil-message"></i> Send
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endforeach
    </div>
</div>
@include('layout.footer')