@php
    $feature_product = \App\Models\Product::where('featured', 1)->get();

@endphp


<section class="section-padding pb-5">

    <div class="container">
        <div class="section-title wow animate__animated animate__fadeIn">
            <h3 class=""> Featured Products </h3>

        </div>
        <div class="row">
            <div class="col-lg-3 d-none d-lg-flex wow animate__animated animate__fadeIn">
                <div class="banner-img style-2">
                   
                </div>
            </div>
            <div class="col-lg-9 col-md-12 wow animate__animated animate__fadeIn" data-wow-delay=".4s">

                <div class="tab-content" id="myTabContent-1">
                    <div class="tab-pane fade show active" id="tab-one-1" role="tabpanel" aria-labelledby="tab-one-1">
                        <div class="carausel-4-columns-cover arrow-center position-relative">
                            <div class="slider-arrow slider-arrow-2 carausel-4-columns-arrow"
                                id="carausel-4-columns-arrows"></div>
                            <div class="carausel-4-columns carausel-arrow-center" id="carausel-4-columns">

                                @foreach ($feature_product as $product)
                                    <div class="product-cart-wrap">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img product-img-zoom">
                                                <a href="/product/details/{{ $product->product_slug }}">
                                                    <img class="default-img"
                                                        src="{{ asset($product->product_thumbnail) }}" alt="" />

                                                </a>
                                            </div>
                                            
                                            <div class="product-action-1">
                                                <a aria-label="Add To Wishlist" class="action-btn"  id="{{ $product->id }}" onclick="addToWishList(this.id)"><i
                                                        class="fi-rs-heart"></i></a>
                                                <a aria-label="Compare" class="action-btn"   id="{{ $product->id }}" onclick="addToCompare(this.id)"><i
                                                        class="fi-rs-shuffle"></i></a>
                                                <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal"
                                                    data-bs-target="#quickViewModal{{$product->id}}"><i class="fi-rs-eye"></i></a>
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
                                            <div class="product-category">
                                                <a href="/product/category/{{$product->category_id}}/{{$product->category->category_slug}}">{{$product->category->category_name}}</a>
                                            </div>
                                            <h2><a
                                                    href="/product/details/{{ $product->id }}/{{ $product->product_slug }}">   {{ \Illuminate\Support\Str::limit($product->product_name, 50) }}      </a>
                                            </h2>
                                            <div class="product-rate d-inline-block">


                                                @php
                                                    $reviewcount = App\Models\Review::where('product_id', $product->id)
                                                        ->where('status', 1)
                                                        ->latest()
                                                        ->get();
                                                    $avarage = App\Models\Review::where('product_id', $product->id)
                                                        ->where('status', 1)
                                                        ->avg('rating');
                                                @endphp


                                     @if ($avarage == 0)
                                    {{-- No rating --}}
                                @elseif ($avarage > 0 && $avarage < 2)
                                    <div class="product-rating" style="width: 20%"></div>
                                @elseif ($avarage >= 2 && $avarage < 3)
                                    <div class="product-rating" style="width: 40%"></div>
                                @elseif ($avarage >= 3 && $avarage < 4)
                                    <div class="product-rating" style="width: 60%"></div>
                                @elseif ($avarage >= 4 && $avarage < 5)
                                    <div class="product-rating" style="width: 80%"></div>
                                @elseif ($avarage >= 5)
                                    <div class="product-rating" style="width: 100%"></div>
                                @endif



                                            </div>

                                            @if ($product->discount_price == null)
                                                <div class="product-price mt-10">
                                                    <span>${{ $product->selling_price }} </span>

                                                </div>
                                            @else
                                                <div class="product-price mt-10">
                                                    <span>${{ $product->discount_price }} </span>
                                                    <span class="old-price">${{ $product->selling_price }}</span>
                                                </div>
                                            @endif




                                            <div class="sold mt-15 mb-15">
                                                <div class="progress mb-5">
                                                    <div class="progress-bar" role="progressbar" style="width: 50%"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="font-xs text-heading"> Sold: 90/120</span>
                                            </div>
                                            <div class="product-card-bottom">
                                            <div class="product-data" style="display: none;">
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="product_color" value="Red">
                                            <input type="hidden" name="product_size" value="M">
                                            <input type="hidden" name="vendor_id" value="{{ $product->vendor_id }}">
                                            <input type="number" name="product_quantity" value="1">
                                        </div>

                                        <div class="add-cart">
                                            <a class="add" href="#" onclick="handleAddToCart(this); return false;">
                                                <i class="fi-rs-shopping-cart mr-5"></i>Add
                                            </a>
                                        </div>
                                        </div>

                                        </div>
                                    </div>
                                    <!--End product Wrap-->
                                @endforeach


                            </div>
                        </div>
                    </div>
                    <!--End tab-pane-->


                </div>
                <!--End tab-content-->
            </div>
            <!--End Col-lg-9-->
        </div>
    </div>
</section>
<!--End Best Sales-->
