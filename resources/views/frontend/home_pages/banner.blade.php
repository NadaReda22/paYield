<section class="banners mb-25">
    @php
        $all_banner = App\Models\Banner::orderBy('id', 'DESC')->get()
    @endphp
    <div class="container">
        <div class="row">
            @foreach($all_banner as $item)
            <div class="col-lg-4 col-md-6">
                <div class="banner-img wow animate__animated animate__fadeInUp" data-wow-delay="0">
                    <img src="{{ asset($item->image) }}" alt=""  style="width:800px; height:600px"/>
                    <div class="banner-text">
                    <h4>{{ $item->banner_title }}</h4>
                    <a href="{{ $item->banner_url }}" class="btn btn-xs">
                        Shop Now <i class="fi-rs-arrow-small-right"></i>
                    </a>
                </div>

                </div>
            </div>
            @endforeach
          
        </div>
    </div>
</section>
<!--End banners-->