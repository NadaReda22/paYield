@extends('layout.main')

@section('main')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">



@section('title')
    {{ $breadcat->category_name }} Category
@endsection

<main class="main">
    <div class="page-header mt-30 mb-50">
        <div class="container">
            <div class="archive-header">
                <div class="row align-items-center">
                    <div class="col-xl-3">
                    <h1 class="mb-15">
    {{ $breadcat->category_name }}
 
</h1>

                        <div class="breadcrumb">
                            <a href="/" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                            <span></span>    {{ $breadcat->category_name }}<span></span> 
    @foreach($breadcat->subcategories as $subcategory)
  
                       
                        <a style="color:#78BC53;"  href="/product/category/{{ $breadcat->id }}/{{ $breadcat->category_slug }}">{{ $subcategory->category_name }}</a>  /
    @endforeach
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
                                        <a
                                            href="/product/category/{{ $product->category_id }}/{{ $product->category->category_slug }}">{{ $product->category->category_name }}</a>
                                    </div>
                                    <h2><a href="/product/details/{{ $product->id }}/{{ $product->product_slug }}">
                                            {{ \Illuminate\Support\Str::limit($product->product_name, 70) }} </a></h2>
                                    <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" style="width: {{$rating}}%"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted"> {{$average_rating}}</span>
                                    </div>
                                    <div>

                                        @if ($product->vendor_id == null)
                                            <span class="font-small text-muted">By <a href="#">Owner</a></span>
                                        @else
                                            <span class="font-small text-muted">By <a
                                                    href="/vendor/details/{{ $product->vendor->id }}">{{ $product->vendor->name }}</a></span>
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
                        Fillter
                    </a>
                </div>

      
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




                <!--category widget --->
                <div class="sidebar-widget widget-category-2 mb-30">
                    <h5 class="section-title style-1 mb-30">Category</h5>
                    <ul>

                        @foreach ($categories as $item)
                            <li>
                                <a href="/product/category/{{ $item->id }}/{{ $item->category_slug}}"> <img
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
    $(document).ready(function () {
        // Active class toggle on dropdown
        $(".sort-by-dropdown ul li a").click(function (event) {
            event.preventDefault();
            $(".sort-by-dropdown ul li a").removeClass("active");
            $(this).addClass("active");
        });

        // Common function to load products
        function loadProducts(orderType, value) {
            $("#withoutajax").hide();
            $("html, body").animate({ scrollTop: 260 }, "slow");

            $.ajax({
                type: "GET",
                url: `/products/cat/${orderType}/${value}`,
                success: function (response) {
                    const products = response.products;
                    let rows = "";

                    $.each(products, function (key, value) {
                        var rating = (value.average_rating / 5.0) * 100;
                        const discountBadge = value.discount_percent === null
                            ? '<span class="new">New</span>'
                            : `<span class="hot">${value.discount_percent}%</span>`;

                        const owner = value.vendor_id === null
                            ? '<span class="font-small text-muted">By <a href="vendor-details-1.html">Owner</a></span>'
                            : `<span class="font-small text-muted">By <a href="vendor-details-1.html">${value.vendor_name}</a></span>`;

                        const price = value.discount_price === null
                            ? `<div class="product-price"><span>${value.selling_price}</span></div>`
                            : `<div class="product-price"><span>${value.discount_price}</span> <span class="old-price">${value.selling_price}</span></div>`;

                        rows += `
                            <div class="col-lg-1-5 col-md-4 col-12 col-sm-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="shop-product-right.html">
                                                <img class="default-img" src="/${value.product_image}" alt="no image" />
                                            </a>
                                        </div>
                                        <div class="product-action-1">
                                                                   <a aria-label="Add To Wishlist"
                                class="action-btn small hover-up"
                                id="${value.product_id}"
                                onclick="addToWishList(this.id)"
                                href="javascript:void(0)" tabindex="0">
                                <i class="fi-rs-heart"></i>
                                </a>

                                        <a aria-label="Compare" class="action-btn"   id="${value.product_id}" onclick="addToCompare(this.id)"><i
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
                                                <div class="product-rating" style="width: ${rating}%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted">${value.average_rating}</span>
                                        </div>
                                        ${owner}
                                        <div class="product-card-bottom">
                                            ${price}
                                            <div class="add-cart">
                                                <a class="add" href="#" onclick="addToCart(${value.product_id}); return false;"><i class="fi-rs-shopping-cart mr-5"></i>Add</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                    });

                    $("#data").html(rows);
                    $("#targetElement").html(`<p>We found <strong class="text-brand">${products.length}</strong> items for you!</p>`);
                },
                error: function (error) {
                    console.error("Error fetching products:", error);
                }
            });
        }
    });


    
</script>

<!--- Filter Featured --->
<script>
    $(document).ready(function () {
        $('#featured').click(function () {
            $('#withoutajax').hide();
            $('#product-found').hide();
            $('html, body').animate({ scrollTop: 260 }, 'slow');
            var value = $(this).data('value');

            $.ajax({
                type: 'GET',
                url: '/products/cat/featured/' + value,
                success: function (response) {
                    var productsCount = response.products.length;
                    var rows = "";

                    $.each(response.products, function (key, value) {
                        var rating = (value.average_rating / 5.0) * 100;
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
                                        <a aria-label="Add To Wishlist" class="action-btn" id="${value.product_id}" onclick="addToWishList(this.id)"><i class="fi-rs-heart"></i></a>
                                        <a aria-label="Compare" class="action-btn" id="${value.product_id}" onclick="addToCompare(this.id)"><i class="fi-rs-shuffle"></i></a>
                                        <a aria-label="Quick view" class="action-btn" data-bs-toggle="modal" data-bs-target='#quickViewModal${value.product_id}'><i class="fi-rs-eye"></i></a>
                                    </div>
                                    <div class="product-badges product-badges-position product-badges-mrg">${discountBadge}</div>
                                </div>
                                <div class="product-content-wrap">
                                    <div class="product-category"><a href="shop-grid-right.html">${value.product_category}</a></div>
                                    <h2><a href="shop-product-right.html">${value.product_name}</a></h2>
                                    <div class="product-rate-cover">
                                        <div class="product-rate d-inline-block">
                                            <div class="product-rating" style="width: ${rating}%"></div>
                                        </div>
                                        <span class="font-small ml-5 text-muted">${value.average_rating}</span>
                                    </div>
                                    ${owner}
                                    <div class="product-card-bottom">
                                        ${price}
                                        <div class="product-data" style="display:none;">
                                            <input type="hidden" name="product_id" value="${value.product_id}">
                                            <input type="hidden" name="product_color" value="${value.product_color || ''}">
                                            <input type="hidden" name="product_size" value="${value.product_size || ''}">
                                            <input type="hidden" name="vendor_id" value="${value.vendor_id || ''}">
                                            <input type="hidden" name="product_quantity" value="1">
                                        </div>
                                        <div class="add-cart">
                                            <a class="add" href="#" onclick="handleAddToCart(this); return false;"><i class="fi-rs-shopping-cart mr-5"></i>Add</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    });

                    $('#data').html(rows);
                    var countData = '<p>We found <strong class="text-brand">' + productsCount + '</strong> items for you!</p>';
                    $('#targetElement').html(countData);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });
    });
</script>

<!--Filter Price-->
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
                 url: '/products/cat/price-filter/' + value,

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
                                <div class="product-data" style="display:none;">
                                            <input type="hidden" name="product_id" value="${value.product_id}">
                                            <input type="hidden" name="product_color" value="${value.product_color || ''}">
                                            <input type="hidden" name="product_size" value="${value.product_size || ''}">
                                            <input type="hidden" name="vendor_id" value="${value.vendor_id || ''}">
                                            <input type="hidden" name="product_quantity" value="1">
                                        </div>
                                        <div class="add-cart">
                                            <a class="add" href="#" onclick="handleAddToCart(this); return false;"><i class="fi-rs-shopping-cart mr-5"></i>Add</a>
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



<!--Filter Price Sort-->
<script>
$(document).ready(function () {
    function fetchProducts(sortType, value) {
        $('#withoutajax').hide();
        $('#product-found').hide();
        $('html, body').animate({ scrollTop: 260 }, 'slow');

        $.ajax({
            type: 'GET',
            url: '/products/cat/' + sortType + '/' + value,
            success: function (response) {
                console.log(response);
                var productsCount = response.products.length;
                var rows = "";

                $.each(response.products, function (key, value) {
                    var rating = (value.average_rating / 5.0) * 100;
                    var discountBadge = value.discount_percent === null
                        ? '<span class="new">New</span>'
                        : '<span class="hot">' + value.discount_percent + '%</span>';

                    var owner = value.vendor_id === null
                        ? '<span class="font-small text-muted">By <a href="vendor-details-1.html">Owner</a></span>'
                        : '<span class="font-small text-muted">By <a href="vendor-details-1.html">' + value.vendor_name + '</a></span>';

                    var price = value.discount_price === null
                        ? '<div class="product-price"><span>' + value.selling_price + '</span></div>'
                        : '<div class="product-price"><span>' + value.discount_price + '</span> <span class="old-price">' + value.selling_price + '</span></div>';

                        $.each(products, function (key, value) {
    var rating = (value.average_rating / 5.0) * 100;

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
                                <a href="/product-details/${value.id}">
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
                                <div class="product-data" style="display:none;">
                                    <input type="hidden" name="product_id" value="${value.product_id || ''}">
                                    <input type="hidden" name="product_color" value="${value.product_color || ''}">
                                    <input type="hidden" name="product_size" value="${value.product_size || ''}">
                                    <input type="hidden" name="vendor_id" value="${value.vendor_id || ''}">
                                    <input type="hidden" name="product_quantity" value="1">
                                </div>
                                <div class="add-cart">
                                    <a class="add" href="#" onclick="handleAddToCart(this); return false;"><i class="fi-rs-shopping-cart mr-5"></i>Add</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            });


                $('#data').html(rows);
                $('#targetElement').html('<p>We found <strong class="text-brand">' + productsCount + '</strong> items for you!</p>');

                // Re-bind button events for dynamically added elements
                $(document).off('click', '.action-btn').on('click', '.action-btn', function () {
                    var id = $(this).attr('id');

                    if ($(this).find('i').hasClass('fi-rs-heart')) {
                        addToWishList(id);
                    } else if ($(this).find('i').hasClass('fi-rs-shuffle')) {
                        addToCompare(id);
                    }
                    // Quick view doesn't need a JS call, it uses Bootstrap modal
                });
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
    $(document).on('change', '.brand-checkbox', function () {
        // Hide default sections before loading new filtered results
        $('#withoutajax').hide();
        $('#product-found').hide();

        // Get subcategory ID from a data attribute on an input field
        var subcatId = $('input[name="subcat"]').data('value');
        var selectedBrands = [];

        // Collect all selected brand IDs
        $('.brand-checkbox:checked').each(function () {
            selectedBrands.push($(this).val());
        });

        // Perform AJAX request
        $.ajax({
            type: 'POST',
            url: '/products/cat/brand-filter/' + subcatId,
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

                // Loop through each returned product and create HTML blocks
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
                    var category = product.category?.category_name ?? 'Category';
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
                                        <a aria-label="Add To Wishlist"
                                           class="action-btn small hover-up wishlist-btn"
                                           id="${product.id}" href="javascript:void(0)">
                                           <i class="fi-rs-heart"></i>
                                        </a>
                                        <a aria-label="Compare"
                                           class="action-btn compare-btn"
                                           id="${product.id}" href="javascript:void(0)">
                                           <i class="fi-rs-shuffle"></i>
                                        </a>
                                        <a aria-label="Quick view"
                                           class="action-btn quickview-btn"
                                           data-bs-toggle="modal"
                                           data-bs-target="#quickViewModal${product.id}">
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
                                        
                                        <div class="product-data" style="display:none;">
                                        <input type="hidden" name="product_id" value="${product.id || ''}">
                                           <input type="hidden" name="product_color" value="${product.product_color || ''}">
                                            <input type="hidden" name="product_size" value="${product.product_size || ''}">
                                            <input type="hidden" name="vendor_id" value="${product.vendor_id || ''}">
                                            <input type="hidden" name="product_quantity" value="1">
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
                    `;
                });

                // Inject the generated HTML into the product container
                $('#data').html(rows);

                // Show product count
                $('#targetElement').html('<p>We found <strong class="text-brand">' + productsCount + '</strong> items for you!</p>');

                // Update the count of products next to each brand
                $('.brand-checkbox').each(function () {
                    var brandId = $(this).val();
                    var count = brandCounts[brandId] || 0;
                    $('.brand-count-' + brandId).text(count);
                });

                // ✅ Re-bind button events for newly added elements
                $(document).off('click', '.wishlist-btn').on('click', '.wishlist-btn', function () {
                    var id = $(this).attr('id');
                    addToWishList(id);
                });

                $(document).off('click', '.compare-btn').on('click', '.compare-btn', function () {
                    var id = $(this).attr('id');
                    addToCompare(id);
                });

                // If needed, you can also bind other dynamic events like quick view here
            }
        });
    });
});
</script>






@endsection
