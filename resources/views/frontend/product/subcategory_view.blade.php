@extends('layout.main')

@section('main')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">

@section('title')
    {{ $breadsubcat->parent->category_name }} > {{ $breadsubcat->category_name }} Subcategory
@endsection



<main class="main">
    <div class="page-header mt-30 mb-50">
        <div class="container">
            <div class="archive-header">
                <div class="row align-items-center">
                    <div class="col-xl-3">
                        <h1 class="mb-15">{{ $breadsubcat->category_name }}</h1>
                        <div class="breadcrumb">
                            <a href="/" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>

                            <span style="color : red"> {{ $breadsubcat->parent->category_name }} </span> <span>
                                {{ $breadsubcat->category_name }} </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="container mb-30">
        <div class="row flex-row-reverse">
            <div class="col-lg-4-5">
                <div class="shop-product-fillter">
                    <div class="totall-product" id="targetElement">
                        <p id="product-found">We found <strong class="text-brand">{{ count($products) }}</strong> items
                            for you!</p>
                    </div>


                    <div class="sort-by-product-area">
                        <div class="sort-by-cover mr-10">

                        </div>
                        <div class="sort-by-cover">
                            <div class="sort-by-product-wrap">
                                <div class="sort-by">
                                    <span><i class="fi-rs-apps-sort"></i>Sort by:</span>
                                </div>
                                <div class="sort-by-dropdown-wrap">
                                    <span> Featured <i class="fi-rs-angle-small-down"></i></span>
                                </div>
                            </div>
                            <div class="sort-by-dropdown">
                                <ul>
                                    <li><a href="#" id="featured" data-value="{{ $id }}">Featured</a>
                                    </li>
                                    <li><a href="#" id="low-to-high" data-value="{{ $id }}">Price: Low
                                            to
                                            High</a></li>
                                    <li><a href="#" id="high-to-low" data-value="{{ $id }}">Price: High
                                            to
                                            Low</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="row product-grid" id="withoutajax">

                    @foreach ($products as $product)
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">
                                        <a href="shop-product-right.html">
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

                                                <a aria-label="Compare" class="action-btn" id="{{ $product->id }}"
                                            onclick="addToCompare(this.id)"><i class="fi-rs-shuffle"></i></a>
                                            <a aria-label="Quick view" class="action-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#quickViewModal{{ $product->id }}">
                                                <i class="fi-rs-eye"></i>
                                                </a>
                                    </div>

                                    @php
                                        $amount = $product->selling_price - $product->discount_price;
                                        $discount = ($amount / $product->selling_price) * 100;
                                        $average_rating = $product->reviews->pluck('rating')->countBy()->sortDesc()->keys()->first() ?? 0;
                                        $rating = ($average_rating/5.0)*100;
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
                                        <a href="shop-grid-right.html">{{ $product->subcategory->category_name }}</a>
                                    </div>
                                    <h2><a href="/product/details/{{$product->id}}/{{$product->product_slug}}"> {{ \Illuminate\Support\Str::limit($product->product_name, 70) }} </a></h2>
                                    <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" style="width: {{$rating}}%"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted"> {{$average_rating}}</span>
                                    </div>

                                    <div>

                                        @if ($product->vendor_id == null)
                                            <span class="font-small text-muted">By <a
                                                    href="vendor-details-1.html">Owner</a></span>
                                        @else
                                            <span class="font-small text-muted">By <a
                                                    href="vendor-details-1.html">{{ $product->vendor->name }}</a></span>
                                        @endif
                                    </div>

                                    <div class="product-card-bottom">


                                        @if ($product->discount_price == null)
                                            <div class="product-price">
                                                <span>${{ $product->selling_price }}</span>

                                            </div>
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
                    <!--end product card-->



                </div>

                <!-- Ajax filter -->

                <div id="data" class="row product-grid">


                </div>
                <!--end Ajax product card-->


                <!--product grid-->


                <!--End Deals-->


            </div>
            <div class="col-lg-1-5 primary-sidebar sticky-sidebar">

                <!-- Fillter By Price -->
                <div class="sidebar-widget price_range range mb-30">
                    <h5 class="section-title style-1 mb-30">Fill by price</h5>

                    <div class="price-filter">
                        <div class="price-filter-inner">
                            <div id="slider-range" class="mb-20"></div>
                            <div class="d-flex justify-content-between">
                                <div class="caption">From: <strong id="slider-range-value1"
                                        class="text-brand"></strong></div>
                                <div class="caption">To: <strong id="slider-range-value2"
                                        class="text-brand"></strong></div>
                            </div>
                        </div>
                    </div>


                    <br>
                    <a href="#" id="price-filter" data-value="{{ $id }}"
                        class="btn btn-sm btn-default"><i class="fi-rs-filter mr-5"></i>
                        Fillter</a>
                </div>

                <!-- Fillter By Brand -->
                <div class="list-group">
                    <div class="list-group-item mb-10 mt-10">
                        <h5 class="section-title style-1 mb-30">Fill by brand</h5>

                        <div class="custome-checkbox">
                                 <!-- Filter By Brand -->
                <brand>
                @foreach ($brands as $item)
                    <input class="form-check-input brand-checkbox" type="checkbox"
                        id="brandCheckbox{{ $loop->index + 1 }}" name="brands[]"
                        value="{{ $item->id }}" />
                    <label class="form-check-label" for="brandCheckbox{{ $loop->index + 1 }}">
                        <span>{{ $item->brand_name }}</span>
                        <!-- Display the product count next to the brand name -->
                        <span class="brand-count-{{ $item->id }}" style="margin-left: 2px; font-weight: bold; color: #3bb77e">
                            {{ $brandCounts[$item->id] ?? 0 }}
                        </span>
                    </label>
                    <input type="hidden" name="subcat" data-value="{{ $id }}" />
                    <br />
                @endforeach
                </brand>

                        </div>

                    </div>
                </div>
                <br>

                <!--category widget --->
                <div class="sidebar-widget widget-category-2 mb-30">
                    <h5 class="section-title style-1 mb-30">Category</h5>
                    <ul>

                        @foreach ($categories as $item)
                   
                            <li>
                                <a href="/product/category/{{ $item->id }}/{{ $item->category_slug }}"> <img
                                        src="{{ asset($item->category_image) }}"
                                        alt="" />{{ $item->category_name }}</a><span
                                    class="count">{{ $item->products->count() }}</span>
                            </li>
                        
                        @endforeach

                    </ul>
                </div>



            </div>
        </div>
    </div>




</main>

<!-- Filter  Category Mark -->
<script>
    $(document).ready(function() {
        // AJAX request on Get Items button click
        $('#featured').click(function() {
            $('#withoutajax').hide();
            $('#product-found').hide();
            $('html, body').animate({
                scrollTop: 260
            }, 'slow');
            var value = $(this).data('value');
            $.ajax({
                type: 'GET',
                url: '/products/subcat/featured/' + value,
                success: function(response) {
                    // Handle the response (list of items)
                    console.log(response);

                    var productsCount = response.products.length;

                    var rows = ""

                    $.each(response.products, function(key, value) {
                        var rating = (value.average_rating/5.0)*100;


                        var discountBadge = "";
                        if (value.discount_percent === null) {
                            discountBadge = '<span class="new">New</span>';
                        } else {
                            discountBadge = '<span class="hot">' + value
                                .discount_percent + '%</span>';
                        }

                        var owner = ""

                        if (value.vendor_id === null) {
                            owner =
                                '<span class="font-small text-muted">By <a href="vendor-details-1.html">Owner</a></span>';
                        } else {

                            owner =
                                '<span class="font-small text-muted">By <a href="vendor-details-1.html">' +
                                value.vendor_name + '</a></span>';

                        }

                        var price = ""

                        if (value.discount_price === null) {
                            price = '<div class="product-price"><span>' + value
                                .selling_price + '</span> </div>'
                        } else {
                            price = '<div class="product-price"> <span>' + value
                                .discount_price +
                                '</span> <span class="old-price">' + value
                                .selling_price + '</span> </div>'
                        }



                        rows += `
        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
            <div class="product-cart-wrap mb-30">
                <div class="product-img-action-wrap">
                    <div class="product-img product-img-zoom">
                        <a href="/product-details/${value.product_id}">
                            <img class="default-img" src="/${value.product_image}" alt="">
                            <img class="hover-img" src="/${value.product_image_hover || value.product_image}" alt="">
                        </a>
                    </div>

                    <div class="product-action-1">
                        <a aria-label="Add To Wishlist" class="action-btn" id="${value.product_id}" onclick="addToWishList(this.id)">
                            <i class="fi-rs-heart"></i>
                        </a>
                        <a aria-label="Compare" class="action-btn" id="${value.product_id}" onclick="addToCompare(this.id)">
                            <i class="fi-rs-shuffle"></i>
                        </a>
                        <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal${value.product_id}">
                            <i class="fi-rs-eye"></i>
                        </a>
                    </div>

                    <div class="product-badges product-badges-position product-badges-mrg">
                        ${discountBadge}
                    </div>
                </div>

                <div class="product-content-wrap">
                    <div class="product-category">
                        <a href="#">${value.product_category}</a>
                    </div>
                    <h2><a href="/product-details/${value.product_id}">${value.product_name}</a></h2>

                    <div class="product-rate-cover">
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: ${rating}%"></div>
                        </div>
                        <span class="font-small ml-5 text-muted">${value.average_rating}</span>
                    </div>

                    ${owner}

                    <div class="product-card-bottom">
                        ${price}
                        <div class="add-cart">
                            <a class="add" href="/add-to-cart/${value.product_id}">
                                <i class="fi-rs-shopping-cart mr-5"></i>Add
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
                    });

                    // Display products in the productContainer div
                    $('#data').html(rows);

                    var countData = '<p>We found <strong class="text-brand">' +
                        productsCount + '</strong> items for you!</p>'
                    $('#targetElement').html(countData);

                },
                error: function(error) {
                    // Handle errors
                    console.error(error);
                }
            });
        });



        // Other event handlers for Update and Delete buttons
    });
</script>

<!--Filter SubCategory Price-->

<script>
    <!-- Filter Price -->
    $(document).ready(function () {
        $('#price-filter').click(function (e) {
            e.preventDefault();

            $('#withoutajax').hide();
            $('#product-found').show();

            var value = $(this).data('value');

            var minValueText = $("#slider-range-value1").text();
            var maxValueText = $("#slider-range-value2").text();

            var minValue = parseFloat(minValueText.replace('$', '').replace(/,/g, ''));
            var maxValue = parseFloat(maxValueText.replace('$', '').replace(/,/g, ''));

            $.ajax({
                type: 'POST',
                 url: '/products/subcat/price-filter/' + value,

                data: {
                    minPrice: minValue,
                    maxPrice: maxValue
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    var products = response.products;
                    var brands = response.brands; // Expected array of available brands [{id: 1, name: 'BrandX'}, ...]

                    var productsCount = products.length;
                    var rows = "";

                    $.each(products, function (key, value) {
                        var rating = (value.average_rating/5.0)*100;

                        var discountBadge = value.discount_percent === null
                            ? '<span class="new">New</span>'
                            : '<span class="hot">' + value.discount_percent + '%</span>';

                        var owner = value.vendor_id === null
                            ? '<span class="font-small text-muted">By <a href="#">Owner</a></span>'
                            : '<span class="font-small text-muted">By <a href="#">' + value.vendor_name + '</a></span>';

                        var price = value.discount_price === null
                            ? '<div class="product-price"><span>' + value.selling_price + ' EGP</span></div>'
                            : '<div class="product-price"><span>' + value.discount_price + ' EGP</span><span class="old-price">' + value.selling_price + ' EGP</span></div>';
                           
                                               rows += `
        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
            <div class="product-cart-wrap mb-30">
                <div class="product-img-action-wrap">
                    <div class="product-img product-img-zoom">
                        <a href="/product-details/${value.product_id}">
                            <img class="default-img" src="/${value.product_image}" alt="">
                            <img class="hover-img" src="/${value.product_image_hover || value.product_image}" alt="">
                        </a>
                    </div>

                    <div class="product-action-1">
                        <a aria-label="Add To Wishlist" class="action-btn" id="${value.product_id}" onclick="addToWishList(this.id)">
                            <i class="fi-rs-heart"></i>
                        </a>
                        <a aria-label="Compare" class="action-btn" id="${value.product_id}" onclick="addToCompare(this.id)">
                            <i class="fi-rs-shuffle"></i>
                        </a>
                        <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal${value.product_id}">
                            <i class="fi-rs-eye"></i>
                        </a>
                    </div>

                    <div class="product-badges product-badges-position product-badges-mrg">
                        ${discountBadge}
                    </div>
                </div>

                <div class="product-content-wrap">
                    <div class="product-category">
                        <a href="#">${value.product_category}</a>
                    </div>
                    <h2><a href="/product-details/${value.product_id}">${value.product_name}</a></h2>

                    <div class="product-rate-cover">
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: ${rating}%"></div>
                        </div>
                        <span class="font-small ml-5 text-muted">${value.average_rating}</span>
                    </div>

                    ${owner}

                    <div class="product-card-bottom">
                        ${price}
                        <div class="add-cart">
                            <a class="add" href="/add-to-cart/${value.product_id}">
                                <i class="fi-rs-shopping-cart mr-5"></i>Add
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;


                    });

                    $('#data').html(rows);
                    $('#targetElement').html(`<p>We found <strong class="text-brand">${productsCount}</strong> items for you!</p>`);

                    // Update brand checkboxes based on filtered price
                    let brandHtml = "";
                    $.each(brands, function (index, brand) {
                        brandHtml += `
                            <div class="form-check">
                                <input class="form-check-input brand-checkbox" type="checkbox" value="${brand.id}" id="brand-${brand.id}">
                                <label class="form-check-label" for="brand-${brand.id}">${brand.name}</label>
                            </div>`;
                    });

                    $('#brand-list').html(brandHtml); // Make sure the brand container has this ID
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });
</script>
<!--Sorting Price-->
<script>
    $(document).ready(function () {
        function fetchProducts(sortType, value) {
            $('#withoutajax').hide();
            $('#product-found').hide();
            $('html, body').animate({ scrollTop: 260 }, 'slow');

            $.ajax({
                type: 'GET',
                url: '/products/subcat/' + sortType + '/' + value,
                success: function (response) {
                    console.log(response);
                    var productsCount = response.products.length;
                    var rows = "";

                    $.each(response.products, function (key, value) {
                        var rating = (value.average_rating/5.0)*100;

                        var discountBadge = value.discount_percent === null
                            ? '<span class="new">New</span>'
                            : '<span class="hot">' + value.discount_percent + '%</span>';

                        var owner = value.vendor_id === null
                            ? '<span class="font-small text-muted">By <a href="vendor-details-1.html">Owner</a></span>'
                            : '<span class="font-small text-muted">By <a href="vendor-details-1.html">' + value.vendor_name + '</a></span>';

                        var price = value.discount_price === null
                            ? '<div class="product-price"><span>' + value.selling_price + '</span></div>'
                            : '<div class="product-price"><span>' + value.discount_price + '</span> <span class="old-price">' + value.selling_price + '</span></div>';

                        rows += `
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">
                                        <a href="shop-product-right.html">
                                            <img class="default-img" src="/${value.product_image}" alt="" />
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
                                        <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal"><i class="fi-rs-eye"></i></a>
                                    </div>
                                    <div class="product-badges product-badges-position product-badges-mrg">${discountBadge}</div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category"><a href="shop-grid-right.html">${value.product_category}</a></div>
                                    <h2><a href="shop-product-right.html">${value.product_name}</a></h2>
                                    <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" style="width:  ${rating}%"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted"> ${value.average_rating}</span>
                                    </div>
                                    <div>${owner}</div>
                                    <div class="product-card-bottom">
                                        ${price}
                                        <div class="add-cart">
                                            <a class="add" href="shop-cart.html"><i class="fi-rs-shopping-cart mr-5"></i>Add</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });

                    $('#data').html(rows);
                    $('#targetElement').html('<p>We found <strong class="text-brand">' + productsCount + '</strong> items for you!</p>');
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }

        // Bind click events
        $('#low-to-high').click(function () {
            fetchProducts('low-to-high', $(this).data('value'));
        });

        $('#high-to-low').click(function () {
            fetchProducts('high-to-low', $(this).data('value'));
        });
    });
</script>



    <script>
$(document).ready(function () {
    // Filter by brand
    $(document).on('change', '.brand-checkbox', function () {
        $('#withoutajax').hide();
        $('#product-found').hide();

        var subcatId = $('input[name="subcat"]').data('value');
        var selectedBrands = [];

        $('.brand-checkbox:checked').each(function () {
            selectedBrands.push($(this).val());
        });

        $.ajax({
            type: 'POST',
            url: '/products/subcat/brand-filter/' + subcatId,
            data: {
                brand_ids: selectedBrands,
                min_price: $('#minPrice').val(),
                max_price: $('#maxPrice').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                var products = response.products;
                var brandCounts = response.brand_counts;
                var productsCount = products.length;
                var rows = "";

                $.each(products, function (index, product) {
                    var rating = (product.average_rating / 5.0) * 100;

                    var discountBadge = product.discount_percent === null
                        ? '<span class="new">New</span>'
                        : '<span class="hot">' + ((product.selling_price - product.discount_price) / 100).toFixed(0) + '%</span>';

                    var owner = product.vendor_id === null || !product.vendor?.name
                        ? '<span class="font-small text-muted">By <a href="#">Owner</a></span>'
                        : '<span class="font-small text-muted">By <a href="#">' + product.vendor.name + '</a></span>';

                    var price = product.discount_price === null
                        ? '<div class="product-price"><span>' + product.selling_price + ' EGP</span></div>'
                        : '<div class="product-price"><span>' + product.discount_price + ' EGP</span> <span class="old-price">' + product.selling_price + ' EGP</span></div>';

                    var thumbnail = product.product_thumbnail ?? 'default.jpg';
                    var category = product.category.category_name ?? 'Category';
                    var productName = product.product_name ?? 'Product';

                    rows += `
                        <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">
                                        <a href="shop-product-right.html">
                                            <img class="default-img" src="/${thumbnail}" alt="" />
                                        </a>
                                    </div>
                                    <div class="product-action-1">
                                        <a aria-label="Add To Wishlist" class="action-btn add-to-wishlist" data-id="${product.id}" href="javascript:void(0)">
                                            <i class="fi-rs-heart"></i>
                                        </a>
                                        <a aria-label="Compare" class="action-btn add-to-compare" data-id="${product.id}" href="javascript:void(0)">
                                            <i class="fi-rs-shuffle"></i>
                                        </a>
                                        <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target="#quickViewModal${product.id}">
                                            <i class="fi-rs-eye"></i>
                                        </a>
                                    </div>
                                    <div class="product-badges product-badges-position product-badges-mrg">
                                        ${discountBadge}
                                    </div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category">
                                        <a href="#">${category}</a>
                                    </div>
                                    <h2><a href="#">${productName}</a></h2>
                                    <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" style="width: ${rating}%"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted">${product.average_rating}</span>
                                    </div>
                                    <div>${owner}</div>
                                    <div class="product-card-bottom">
                                        ${price}
                                        <div class="add-cart">
                                            <a class="add" href="#"><i class="fi-rs-shopping-cart mr-5"></i>Add</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                $('#data').html(rows);
                $('#targetElement').html('<p>We found <strong class="text-brand">' + productsCount + '</strong> items for you!</p>');

                $('.brand-checkbox').each(function () {
                    var brandId = $(this).val();
                    var count = brandCounts[brandId] || 0;
                    $('.brand-count-' + brandId).text(count);
                });
            },
            error: function (error) {
                console.error(error);
            }
        });
    });

    // Delegated click handler for dynamically added wishlist buttons
    $(document).on('click', '.add-to-wishlist', function (e) {
        e.preventDefault();
        let productId = $(this).data('id');
        addToWishList(productId); // must be globally defined elsewhere
    });

    // Delegated click handler for compare buttons
    $(document).on('click', '.add-to-compare', function (e) {
        e.preventDefault();
        let productId = $(this).data('id');
        addToCompare(productId); // must be globally defined elsewhere
    });
});

</script>






@endsection
