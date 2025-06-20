@php

    $all_category = \App\Models\Category::all(); 
   // <!--where('front_view', 1)->get();--> 

@endphp


<!-- Electronics Category -->
@foreach ($all_category as $item)
   @if($item->id===4)
    <section id="skincare" class="product-tabs section-padding position-relative">
        @else
         <section class="product-tabs section-padding position-relative">
            @endif
        <div class="container">
            <div class="section-title style-2 wow animate__animated animate__fadeIn">
                <h3>{{ $item->category_name }}</h3>

            </div>
            <!--End nav-tabs-->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                    <div class="row product-grid-4">

                      
                        @php
                            $all_product = \App\Models\Product::with('category', 'subcategory')
                                ->where('category_id', $item->id)
                                ->orWhere('subcategory_id', $item->id)
                                ->inRandomOrder()
                                ->limit(30)
                                ->get();
                        @endphp




                        @forelse($all_product as $product)
                            @if ($product->category_id == $item->id || $product->subcategory_id == $item->id)
                                <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                                    <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn"
                                        data-wow-delay=".1s">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img product-img-zoom">
                                                <a href="/product/details/{{ $product->id }}/{{ $product->product_slug }}">
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
                                                <a href="/product/category/{{$product->category_id}}/{{$product->category->category_slug}}">{{ $item->category_name }}</a>
                                            </div>
                                            
                                                
                                                <h2><a href="/product/details/{{ $product->id }}/{{ $product->product_slug }}">{{ \Illuminate\Support\Str::limit($product->product_name, 50) }}</a></h2>

                                            
                                            
                                            <div class="product-rate-cover">
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
                                                <span class="font-small ml-5 text-muted"> {{$avarage}}</span>
                                            </div>
                                            <div>
                                                <span class="font-small text-muted">By <a
                                                        href="/vendor/details/{{$product->user_id}}">{{ $product->vendor->name }}</a></span>
                                            </div>
                                            <div class="product-card-bottom">

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
                                </div>
                            @endif
                            <!--end product card-->

                        @empty

                            <h2 class="text-danger">Ooops!! No Product Found.</h2>
                        @endforelse

                    </div>
                    <!--End product-grid-4-->
                </div>


            </div>
            <!--End tab-content-->
        </div>


    </section>
    <!--End TV Category -->
@endforeach
