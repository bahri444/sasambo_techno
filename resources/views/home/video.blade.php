@include('layout.header')
<div class="container">
    <div class="text-center mt-3">
        <h2 class="color__green">Video</h2>
        <p class="color__green">Video tutorial</p>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
        @foreach($videos as $vid)
        <div class="col">
            <div class="card">
                <iframe height="210px" src="{{$vid->video_link}}" allowfullscreen></iframe>
                <div class="card-body">
                    <p class="card-text style__font">{{$vid->deskripsi}}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@include('layout.footer')