@include('layout.header')
<div class="container mt-5">
    <div class="row row-cols-1 row-cols-md-3 g-4 d-flex justify-content-between">
        <div class="col-md-4 col-lg-4 col-sm-4">
            @foreach($instansi as $inst)
            <div class="mt-5">
                <img src="/logo/{{$inst->logo}}" class="card-img-bottom" alt="404">
            </div>
            @endforeach
        </div>
        <div class="col-md-5 col-lg-5 col-sm-5">
            <div class="card shadow-lg" style="border-color: #0FAA5D;">
                <div class="card-body">
                    <div class="text text-center">
                        <h4 class="color__green">Login</h4>
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
                    <form action="{{route('login-post')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group mt-3">
                                <div class="col">
                                    <label class="color__green">Email <span class="text text-danger">*</span></label>
                                    <input class="form-control form-control-sm" type="email" name="email" placeholder="email@gmail.com" value="{{ old('email') }}" aria-label="default input example" autofocus>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <div class="col">
                                    <label class="color__green">Password <span class="text text-danger">*</span></label>
                                    <input class="form-control form-control-sm" type="password" name="password" placeholder="input password" aria-label="default input example">
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-block mt-5">
                            <button class="btn btn-sm col-12" style="background-color: #0FAA5D;" type="submit">Login</button>
                            <p class="text-center mt-3">Belum memiliki akun ? ayo,
                                <a class="text-decoration-none text-center" href="/register" style="color: #0FAA5D;">Register</a>
                            </p>
                            <div class="col-12 text-center mb-2">
                                <a class="text-decoration-none" href="/requestReset" style="color: #0FAA5D;">Forgot Password</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.footer')