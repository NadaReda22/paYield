@extends('layout.main')
@section('main')


@section('title')
    {{ $product->product_name }} 
@endsection



    <main class="main">
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="/" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                    <span></span> <a href="/product/category/{{$product->category->id}}/{{$product->category->category_slug}}">{{$product->category->category_name}}</a> <span></span> {{$product->subcategory->category_name}}
                </div>
            </div>
        </div>
        <div class="container mb-30">
            <div class="row">
                <div class="col-xl-10 col-lg-12 m-auto">
                    <div class="product-detail accordion-detail">
                        <div class="row mb-50 mt-30">
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-md-0 mb-sm-5">
                                <div class="detail-gallery">
                                    <span class="zoom-icon"><i class="fi-rs-search"></i></span>
                                    <!-- MAIN SLIDES -->
                                  @if(isset($multiImage) && count($multiImage))
                                        <div class="product-image-slider">
                                            @foreach ($multiImage as $item)
                                                <figure class="border-radius-10">
                                                    <img src="{{ asset($item->image) }}" alt="product image" />
                                                </figure>
                                            @endforeach
                                        </div>
                                    @else
                                        <div>
                                            <img src="{{ asset($product->product_thumbnail) }}" alt="product image" />
                                        </div>
                                    @endif

                                    <!-- THUMBNAILS -->
                                    
                                    <div class="slider-nav-thumbnails">
                                        @foreach ($multiImage as $item)
                                            <div><img src="{{ asset($item->image) }}" alt="product image" /></div>
                                        @endforeach
                                    </div>
                                  
                                </div>
                                <!-- End Gallery -->
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="detail-info pr-30 pl-30">

                                    @if ($product->product_quantity > 0)
                                        <span class="stock-status in-stock">In Stock </span>
                                    @else
                                        <span class="stock-status out-stock">Stock Out </span>
                                    @endif

                                    <h4 class="title-detail">{{ $product->product_name }}</h4>
                                    <div class="product-detail-rating">
                                        <div class="product-rate-cover text-end">

                                            @php
                                                $reviewcount = App\Models\Review::where('product_id', $product->id)
                                                    ->where('status', 1)
                                                    ->latest()
                                                    ->get();
                                                $avarage = App\Models\Review::where('product_id', $product->id)
                                                    ->where('status', 1)
                                                    ->avg('rating');
                                            @endphp


                                            <div class="product-rate d-inline-block">
                                                
                                                @if($avarage == 0)

                                                @elseif($avarage >= 1 && $avarage < 2)                     
                                             <div class="product-rating" style="width: 20%"></div>
                                                @elseif($avarage >= 2 && $avarage < 3)                     
                                             <div class="product-rating" style="width: 40%"></div>
                                                @elseif($avarage >= 3 && $avarage < 4)                     
                                             <div class="product-rating" style="width: 60%"></div>
                                                @elseif($avarage >= 4 && $avarage < 5)                     
                                             <div class="product-rating" style="width: 80%"></div>
                                                @elseif($avarage == 5 )                     
                                             <div class="product-rating" style="width: 100%"></div>
                                             @endif


                                            </div>
                                            
                                            <span class="font-small ml-5 text-muted"> ({{ count($reviewcount)}} reviews)</span>


                                        </div>
                                    </div>


                                    @php
                                        $amount = $product->selling_price - $product->discount_price;
                                        $discount = ($amount / $product->selling_price) * 100;
                                    @endphp


                                    @if ($product->discount_price == null)
                                        <div class="product-price primary-color float-left">
                                            <span class="current-price text-brand">EGP {{ $product->selling_price }}</span>

                                        </div>
                                    @else
                                        <div class="product-price primary-color float-left">
                                            <span class="current-price text-brand">EGP {{ $product->discount_price }}</span>
                                            <span>
                                                <span class="save-price font-md color3 ml-15">{{ round($discount) }}%
                                                    Off</span>
                                                <span
                                                    class="old-price font-md ml-15">EGP {{ $product->selling_price }}</span>
                                            </span>
                                        </div>
                                    @endif





                                    <div class="short-desc mb-30">
                                        <p class="font-lg">{{ $product->short_descp }}</p>
                                    </div>

                                    <form class="productCart">
                                        {{ @csrf_field() }}

                                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                        <input type="hidden" name="vendor_id" value="{{ $product->vendor_id }}" />

                                        @if ($product->product_size == null)
                                        @else
                                            <div class="attr-detail attr-size mb-30">
                                                <strong class="mr-10" style="width:50px;">Size : </strong>
                                                <select class="form-control unicase-form-control" name="product_size"
                                                    id="size">
                                                    <option selected="" disabled="">--Choose Size--</option>
                                                    @foreach ($product_size as $size)
                                                        <option value="{{ $size }}">{{ ucwords($size) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif


                                        @if ($product->product_color == null)
                                        @else
                                            <div class="attr-detail attr-size mb-30">
                                                <strong class="mr-10" style="width:50px;">Color : </strong>
                                                <select class="form-control unicase-form-control" name="product_color"
                                                    id="size">
                                                    <option selected="" disabled="">--Choose Color--</option>
                                                    @foreach ($product_color as $color)
                                                        <option value="{{ $color }}">{{ ucwords($color) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="detail-extralink mb-50">
    <div class="detail-qty border radius">
        <a href="javascript:void(0);" class="qty-down"><i class="fi-rs-angle-small-down"></i></a>
        <input type="number" name="product_quantity" class="qty-val" value="1" min="1" max="{{ $product->product_quantity }}" step="1">
        <a href="javascript:void(0);" class="qty-up"><i class="fi-rs-angle-small-up"></i></a>
    </div>
    <div class="product-extra-link2">
        <div class="product-card-bottom">
            <div class="product-data" style="display: none;">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="product_color" value="">
                <input type="hidden" name="product_size" value="">
                <input type="hidden" name="vendor_id" value="{{ $product->vendor_id }}">
            </div>
            <button type="button" onclick="addToCart(this)" class="button button-add-to-cart add">
                <i class="fi-rs-shopping-cart"></i>Add to cart
            </button>



                                    <a aria-label="Add To Wishlist"
                                    class="action-btn small hover-up"
                                    id="{{ $product->id }}"
                                    onclick="addToWishList(this.id)"
                                    href="javascript:void(0)" tabindex="0">
                                        <i class="fi-rs-heart"></i>
                                    </a>

                                    <a aria-label="Compare" class="action-btn hover-up" href="shop-compare.html">
                                        <i class="fi-rs-shuffle"></i>
                                    </a>
                                </dvi>

                                        </div>

                                    </form>

                                </div>



                                    <div class="font-xs">
                                        <ul class="mr-50 float-start">
                                        <li class="mb-5">
                                            Brand:
                                            @foreach ($product->brands as $brand)
                                                <span class="text-brand">{{ $brand->brand_name }}</span>@if(!$loop->last), @endif
                                            @endforeach
                                        </li>
                                            <li class="mb-5">Category:<span class="text-brand">
                                                    {{ $product['category']['category_name'] }}</span></li>
                                            <li>SubCategory: <span
                                                    class="text-brand">{{ $product['subcategory']['category_name'] }}</span>
                                            </li>
                                        </ul>
                                        <ul class="float-start">
                                            <li class="mb-5">Product Code: <a
                                                    href="#">{{ $product->product_code }}</a></li>

                                            <li class="mb-5">Tags: <a href="#" rel="tag">
                                                    {{ $product->product_tags }}</a></li>

                                            <li>Stock:<span class="in-stock text-brand ml-5">({{ $product->product_quantity }})
                                                    Items In Stock</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Detail Info -->
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="tab-style3">
                                <ul class="nav nav-tabs text-uppercase">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="Description-tab" data-bs-toggle="tab"
                                            href="#Description">Description</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="Additional-info-tab" data-bs-toggle="tab"
                                            href="#Additional-info">Additional info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="Vendor-info-tab" data-bs-toggle="tab"
                                            href="#Vendor-info">Vendor</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="Reviews-tab" data-bs-toggle="tab" href="#Reviews">Reviews ({{ count($reviewcount) }})</a>
                                    </li>
                                </ul>
                                <div class="tab-content shop_info_tab entry-main-content">
                                    <div class="tab-pane fade show active" id="Description">
                                        <div>
                                            <p> {!! $product->long_descp !!} </p>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="Additional-info">
                                        <table class="font-md">
                                            <tbody>
                                                <tr class="stand-up">
                                                    <th>Stand Up</th>
                                                    <td>
                                                        <p>35″L x 24″W x 37-45″H(front to back wheel)</p>
                                                    </td>
                                                </tr>
                                                <tr class="folded-wo-wheels">
                                                    <th>Folded (w/o wheels)</th>
                                                    <td>
                                                        <p>32.5″L x 18.5″W x 16.5″H</p>
                                                    </td>
                                                </tr>
                                                <tr class="folded-w-wheels">
                                                    <th>Folded (w/ wheels)</th>
                                                    <td>
                                                        <p>32.5″L x 24″W x 18.5″H</p>
                                                    </td>
                                                </tr>
                                                <tr class="door-pass-through">
                                                    <th>Door Pass Through</th>
                                                    <td>
                                                        <p>24</p>
                                                    </td>
                                                </tr>
                                                <tr class="frame">
                                                    <th>Frame</th>
                                                    <td>
                                                        <p>Aluminum</p>
                                                    </td>
                                                </tr>
                                                <tr class="weight-wo-wheels">
                                                    <th>Weight (w/o wheels)</th>
                                                    <td>
                                                        <p>20 LBS</p>
                                                    </td>
                                                </tr>
                                                <tr class="weight-capacity">
                                                    <th>Weight Capacity</th>
                                                    <td>
                                                        <p>60 LBS</p>
                                                    </td>
                                                </tr>
                                                <tr class="width">
                                                    <th>Width</th>
                                                    <td>
                                                        <p>24″</p>
                                                    </td>
                                                </tr>
                                                <tr class="handle-height-ground-to-handle">
                                                    <th>Handle height (ground to handle)</th>
                                                    <td>
                                                        <p>37-45″</p>
                                                    </td>
                                                </tr>
                                                <tr class="wheels">
                                                    <th>Wheels</th>
                                                    <td>
                                                        <p>12″ air / wide track slick tread</p>
                                                    </td>
                                                </tr>
                                                <tr class="seat-back-height">
                                                    <th>Seat back height</th>
                                                    <td>
                                                        <p>21.5″</p>
                                                    </td>
                                                </tr>
                                                <tr class="head-room-inside-canopy">
                                                    <th>Head room (inside canopy)</th>
                                                    <td>
                                                        <p>25″</p>
                                                    </td>
                                                </tr>
                                                <tr class="pa_color">
                                                    <th>Color</th>
                                                    <td>
                                                        <p>Black, Blue, Red, White</p>
                                                    </td>
                                                </tr>
                                                <tr class="pa_size">
                                                    <th>Size</th>
                                                    <td>
                                                        <p>M, S</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="Vendor-info">
                                        <div class="vendor-logo d-flex mb-30">
                                            <img src="{{ asset( $product->vendor->photo) }}"
                                                style="border-radius : 10px; width:100px; height:100px" alt="" />
                                            <div class="vendor-name ml-15">
                                                <h6>
                                                    @if ($product->vendor_id == null)
                                                        <h6>
                                                            <a href="vendor-details-2.html">Owner</a>
                                                        </h6>
                                                    @else
                                                        <h6>
                                                            <a
                                                                href="vendor-details-2.html">{{ $product['vendor']['name'] }}</a>
                                                        </h6>
                                                    @endif
                                                </h6>
                                                <div class="product-rate-cover text-end">
                                                    <div class="product-rate d-inline-block">
                                                        <div class="product-rating" style="width: 90%"></div>
                                                    </div>
                                                    <span class="font-small ml-5 text-muted"> (32 reviews)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="contact-infor mb-50">
                                            <li><img src="{{ asset('Frontend/assets/imgs/theme/icons/icon-location.svg') }}"
                                                    alt="" /><strong>Address: </strong> <span>5171 W Campbell Ave
                                                    undefined Kent, Utah 53127 United States</span></li>
                                            <li><img src="{{ asset('Frontend/assets/imgs/theme/icons/icon-contact.svg') }}"
                                                    alt="" /><strong>Contact
                                                    Seller:</strong><span>(+91) -
                                                    540-025-553</span></li>
                                        </ul>
                                        <div class="d-flex mb-55">
                                            <div class="mr-30">
                                                <p class="text-brand font-xs">Rating</p>
                                                <h4 class="mb-0">92%</h4>
                                            </div>
                                            <div class="mr-30">
                                                <p class="text-brand font-xs">Ship on time</p>
                                                <h4 class="mb-0">100%</h4>
                                            </div>
                                            <div>
                                                <p class="text-brand font-xs">Chat response</p>
                                                <h4 class="mb-0">89%</h4>
                                            </div>
                                        </div>

                                        <p>{{ $product->vendor->vendor_info }}</p>
                                    </div>
                                    <div class="tab-pane fade" id="Reviews">
                                        <!--Comments-->
                                        <div class="comments-area">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <h4 class="mb-30">Customer questions & answers</h4>
                                                    <div class="comment-list">

                                                        @php

                                                            $reviews = App\Models\Review::where('product_id', $product->id)
                                                                ->latest()
                                                                ->limit(5)
                                                                ->get();

                                                        @endphp

                                                        @foreach ($reviews as $item)
                                                            @if ($item->status == 0)
                                                            @else
                                                                <div
                                                                    class="single-comment justify-content-between d-flex mb-30">
                                                                    <div class="user justify-content-between d-flex">
                                                                        <div class="thumb text-center">
                                                                            <img src="{{ !empty($item->user->photo) ? asset( $item->user->photo) : url('AdminBackend/upload/no_image.jpg') }}"
                                                                                alt=""
                                                                                style="width: 100px; height : 100px; border-radius : 50%" />
                                                                            <a href="#"
                                                                                class="font-heading text-brand">{{ $item->user->name }}</a>
                                                                        </div>
                                                                        <div class="desc">
                                                                            <div
                                                                                class="d-flex justify-content-between mb-10">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="font-xs text-muted">
                                                                                        {{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                                                                    </span>
                                                                                </div>
                                                                                <div class="product-rate d-inline-block">

                                                                                    @if ($item->rating == null)
                                                                                    @elseif($item->rating >= 1 && $item->rating < 2)
                                                                                        <div class="product-rating"
                                                                                            style="width: 20%"></div>
                                                                                     @elseif($item->rating >= 2 && $item->rating < 3)
                                                                                        <div class="product-rating"
                                                                                            style="width: 40%"></div>
                                                                                    @elseif($item->rating >= 3 && $item->rating < 4)
                                                                                        <div class="product-rating"
                                                                                            style="width: 60%"></div>
                                                                                     @elseif($item->rating >= 4 && $item->rating < 5)
                                                                                        <div class="product-rating"
                                                                                            style="width: 80%"></div>
                                                                                    @elseif($item->rating == 5)
                                                                                        <div class="product-rating"
                                                                                            style="width: 100%"></div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <p class="mb-10">{{ $item->comment }} <a
                                                                                    href="#"
                                                                                    class="reply">Reply</a></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach




                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <h4 class="mb-30">Customer reviews</h4>
                                                    <div class="d-flex mb-30">

                                                    </div>
                                                    <div class="progress">
                                                        <span>5 star</span>
                                                        <div class="progress-bar" role="progressbar" style="width: 50%"
                                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%
                                                        </div>
                                                    </div>
                                                    <div class="progress">
                                                        <span>4 star</span>
                                                        <div class="progress-bar" role="progressbar" style="width: 25%"
                                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%
                                                        </div>
                                                    </div>
                                                    <div class="progress">
                                                        <span>3 star</span>
                                                        <div class="progress-bar" role="progressbar" style="width: 45%"
                                                            aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">45%
                                                        </div>
                                                    </div>
                                                    <div class="progress">
                                                        <span>2 star</span>
                                                        <div class="progress-bar" role="progressbar" style="width: 65%"
                                                            aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65%
                                                        </div>
                                                    </div>
                                                    <div class="progress mb-30">
                                                        <span>1 star</span>
                                                        <div class="progress-bar" role="progressbar" style="width: 85%"
                                                            aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">85%
                                                        </div>
                                                    </div>
                                                    <a href="#" class="font-xs text-muted">How are ratings
                                                        calculated?</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!--comment form-->
                                        <div class="comment-form">
                                            <h4 class="mb-15">Add a review</h4>
                                            <div class="product-rate d-inline-block mb-30"></div>

                                            @guest
                                                <p> <b>For Add Product Review. You Need To Login First <a
                                                            href="{{ route('login') }}">Login Here </a> </b></p>
                                            @else
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-12">

                                                        <form class="form-contact comment_form"
                                                            action="{{ route('review.store') }}" method="post"
                                                            id="commentForm">
                                                            @csrf

                                                            <div class="row">

                                                                <input type="hidden" name="product_id"
                                                                    value="{{ $product->id }}">

                                                                @if ($product->vendor_id == null)
                                                                    <input type="hidden" name="hvendor_id" value="">
                                                                @else
                                                                    <input type="hidden" name="hvendor_id"
                                                                        value="{{ $product->vendor_id }}">
                                                                @endif

                                                                <table class="table" style=" width: 60%; margin-left : 17px">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="cell-level">&nbsp;</th>
                                                                            <th>1 star</th>
                                                                            <th>2 star</th>
                                                                            <th>3 star</th>
                                                                            <th>4 star</th>
                                                                            <th>5 star</th>
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="cell-level">Quality</td>
                                                                            <td><input type="radio" name="rating"
                                                                                    class="radio-sm" value="1"></td>
                                                                            <td><input type="radio" name="rating"
                                                                                    class="radio-sm" value="2"></td>
                                                                            <td><input type="radio" name="rating"
                                                                                    class="radio-sm" value="3"></td>
                                                                            <td><input type="radio" name="rating"
                                                                                    class="radio-sm" value="4"></td>
                                                                            <td><input type="radio" name="rating"
                                                                                    class="radio-sm" value="5"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>


                                                                <div class="col-12">
                                                                    <div class="form-group">
                                                                        <textarea class="form-control w-100" name="comment" id="comment" cols="30" rows="9"
                                                                            placeholder="Write Comment"></textarea>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                            <div class="form-group">
                                                                <button type="submit"
                                                                    class="button button-contactForm">Submit
                                                                    Review</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                            @endguest

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-60">
                            <div class="col-12">
                                <h2 class="section-title style-1 mb-30">Related products</h2>
                            </div>
                            <div class="col-12">
                                <div class="row related-products">

                                    @foreach ($relatedProduct as $item)
                                        <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                                            <div class="product-cart-wrap hover-up">
                                                <div class="product-img-action-wrap">
                                                    <div class="product-img product-img-zoom">
                                                        <a href="/product/details/{{$item->id}}/{{$item->product_slug}}" tabindex="0">
                                                            <img class="default-img"
                                                                src="{{ asset($item->product_thumbnail) }}"
                                                                alt="" />
                                                            
                                                        </a>
                                                    </div>
                                                    <div class="product-action-1">
                                                        <a aria-label="Quick view" class="action-btn small hover-up"
                                                            data-bs-toggle="modal" data-bs-target="#quickViewModal"><i
                                                                class="fi-rs-search"></i></a>
                                                                <a aria-label="Add To Wishlist"
                                                                    class="action-btn small hover-up"
                                                                    id="{{ $product->id }}"
                                                                    onclick="addToWishList(this.id)"
                                                                    href="javascript:void(0)" tabindex="0">
                                                                    <i class="fi-rs-heart"></i>
                                                                    </a>
                                                                    <i
                                                                class="fi-rs-heart"></i></a>
                                                        <a aria-label="Compare" class="action-btn small hover-up"
                                                            href="shop-compare.html" tabindex="0"><i
                                                                class="fi-rs-shuffle"></i></a>
                                                    </div>

                                                    @php
                                                        $amount = $product->selling_price - $product->discount_price;
                                                        $discount = ($amount / $product->selling_price) * 100;
                                                    @endphp




                                                    <div class="product-badges product-badges-position product-badges-mrg">
                                                        @if ($product->discount_price == null)
                                                            <span class="new">New</span>
                                                        @else
                                                            <span class="hot"> {{ round($discount) }} %</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="product-content-wrap">
                                                    <h2><a href="/product/details/{{$item->id}}/{{$item->product_slug}}"
                                                            tabindex="0">{{ $item->product_name }}</a></h2>
                                                    <div class="rating-result" title="90%">
                                                        <span> </span>
                                                    </div>
                                                    <div class="product-price">
                                                        @if ($product->discount_price == null)
                                                            <div class="product-price">
                                                                <span>${{ $product->selling_price }}</span>

                                                            </div>
                                                        @else
                                                            <div class="product-price">
                                                                <span>${{ $product->discount_price }}</span>
                                                                <span
                                                                    class="old-price">${{ $product->selling_price }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
