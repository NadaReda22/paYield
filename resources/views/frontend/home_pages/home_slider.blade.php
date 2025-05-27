<section class="home-slider position-relative mb-30">
    <div class="container">
        <div class="home-slide-cover mt-30" style="color: white;">
            @php
                $all_slider = App\Models\Slider::orderBy('id', 'ASC')->get();
            @endphp
            <div class="hero-slider-1 style-4 dot-style-1 dot-style-1-position-1">

                @foreach ($all_slider as $item)
                    <a href="#{{$item->slider_url}}">

                        <div class="single-hero-slider single-animation-wrap"
                        style="background-image: url('{{ asset($item->image) }}'); width: 100%; height:100%">
                   
                            <div class="slider-content" style="color: white;">

                                {{--    --}}
                                    
                                      <h1 class="display-2 mb-40">

                                    {{ implode(' ', array_slice(str_word_count($item->slider_title, 1), 0, 3)) }}<br>{{ implode(' ', array_slice(str_word_count($item->slider_title, 1), 3)) }}



                                </h1 style="color: white;">
                               
                                <form class="form-subcriber d-flex">
                                    <input type="email" placeholder="Your emaill address" />
                                    <button class="btn" type="submit">Subscribe</button>
                                </form>
                                    
                                   <p class="mb-65" style="color: white;">{{ $item->short_title }}</p>
                              
                            </div>
                        </div>

                    </a>
                @endforeach

            </div>
            <div class="slider-arrow hero-slider-1-arrow"></div>
        </div>
    </div>
</section>
<!--End hero slider-->
