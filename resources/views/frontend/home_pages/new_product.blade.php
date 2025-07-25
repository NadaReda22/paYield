@php
    $products = App\Models\Product::where('status', 1)
        ->orderBy('id', 'ASC')
        ->limit(10)
        ->get();
@endphp

<section id="new-products" class="product-tabs section-padding position-relative">
    <div class="container">
        <div class="section-title style-2 wow animate__animated animate__fadeIn">
            <h3> New Products </h3>
            <ul class="nav nav-tabs links" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="nav-tab-one" data-bs-toggle="tab" data-bs-target="#tab-one"
                        type="button" role="tab" aria-controls="tab-one" aria-selected="true">All</button>
                </li>

                @php
                $all_category = \App\Models\Category::whereNull('parent_id')->get();

                @endphp

                @foreach ($all_category as $item)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="nav-tab-two" data-bs-toggle="tab"
                            data-bs-target="#category{{ $item->id }}" type="button" role="tab"
                            aria-controls="tab-two" aria-selected="false">{{ $item->category_name }}</button>
                    </li>
                @endforeach


            </ul>
        </div>




        <!--End nav-tabs-->
        <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                <div class="row product-grid-4">

                    @foreach ($products as $product)
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn"
                                data-wow-delay=".1s">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">
                                        <a href="/product/details/{{ $product->id }}/{{ $product->product_slug }}">
                                            <img class="default-img" src="{{ asset($product->product_thumbnail) }}"
                                                alt="" />
                                           
                                        </a>
                                    </div>
                                    <div class="product-action-1">
                                    <a aria-label="Add To Wishlist"
                                class="action-btn small hover-up"
                                id="{{ $product->id }}"
                                onclick="addToWishList(this.id)"
                                href="javascript:void(0)" tabindex="0">
                                <i class="fi-rs-heart"></i>
                                </a>

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
                                            <span class="hot">{{ round($discount) }}%</span>
                                        @endif

                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category">
                                        <a href="/product/category/{{$product->category_id}}/{{$product->category->category_slug}}">{{ $product['category']['category_name'] }}</a>
                                    </div>
                                    <h2><a
                                            href="/product/details/{{ $product->id }}/{{ $product->product_slug }}">{{ \Illuminate\Support\Str::limit($product->product_name, 80) }}</a>
                                    </h2>
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
                                        @if ($product->vendor_id == null)
                                            <span class="font-small text-muted">By <a
                                                    href="vendor-details-1.html">Owner</a></span>
                                        @else
                                            <span class="font-small text-muted">By <a
                                                    href="/vendor/details/{{$product->vendor_id}}">{{ $product['vendor']['name'] }}</a></span>
                                        @endif
                                    </div>
                                    <div class="product-card-bottom">

                                        @if ($product->discount_price == null)
                                            <span>${{ $product->selling_price }}</span>
                                        @else
                                            <div class="product-price">
                                                <span>${{ $product->discount_price }}</span>
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
                                    <div class="product-data" style="display: none;">
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="product_color" value=" ">
    <input type="hidden" name="product_size" value=" ">
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

                       
                    @endforeach

                </div>
                <!--End product-grid-4-->
            </div>
            <!--En tab one-->

            @foreach ($all_category as $item)
                <div class="tab-pane fade" id="category{{ $item->id }}" role="tabpanel"
                    aria-labelledby="tab-two">
                    <div class="row product-grid-4">

                        @php
                            $categoryWiseProduct = App\Models\Product::where('category_id', $item->id)
                                ->limit(10)
                                ->get();
                        @endphp

                        @forelse($categoryWiseProduct as $data)
                            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6 d-none d-xl-block">
                                <div class="product-cart-wrap">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="shop-product-right.html">
                                                <img class="default-img" src="{{ asset($data->product_thumbnail) }}"
                                                    alt="" />
                                               
                                            </a>
                                        </div>
                                        <div class="product-action-1">
                                            <a aria-label="Add To Wishlist"  class="action-btn"
                                                href=""><i class="fi-rs-heart"></i></a>
                                            <a aria-label="Compare" class="action-btn" href="shop-compare.html"><i
                                                    class="fi-rs-shuffle"></i></a>
                                            <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal"
                                                data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                        </div>

                                        @php
                                            $amount = $data->selling_price - $data->discount_price;
                                            $discount = ($amount / $data->selling_price) * 100;
                                            
                                        @endphp

                                        <div class="product-badges product-badges-position product-badges-mrg">

                                            @if ($data->discount_price == null)
                                                <span class="new">New</span>
                                            @else
                                                <span class="hot">{{ round($discount) }}%</span>
                                            @endif

                                        </div>

                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a
                                                href="shop-grid-right.html">{{ $data['category']['category_name'] }}</a>
                                        </div>
                                        <h2><a href="shop-product-right.html">{{ $data->product_name }}
                                                Ketchup</a></h2>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 50%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (2.0)</span>
                                        </div>


                                        <div>
                                            @if ($data->vendor_id == null)
                                                <span class="font-small text-muted">By <a
                                                        href="vendor-details-1.html">Owner</a></span>
                                            @else
                                                <span class="font-small text-muted">By <a
                                                        href="vendor-details-1.html">{{ $data['vendor']['name'] }}</a></span>
                                            @endif
                                        </div>




                                        <div class="product-card-bottom">

                                            @if ($data->discount_price == null)
                                                <span>${{ $product->selling_price }}</span>
                                            @else
                                                <div class="product-price">
                                                    <span>${{ $data->discount_price }}</span>
                                                    <span class="old-price">${{ $data->selling_price }}</span>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <h1 class="text-danger">No Product Found !</h1>
                        @endforelse
                        <!--end product card-->


                    </div>
                    <!--End product-grid-4-->
                </div>
            @endforeach

            <!--En category wise product tab-->

        </div>
        <!--End tab-content-->
    </div>
</section>
<!--Products Tabs-->
