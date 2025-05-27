@php
    $seo = App\Models\SeoSetting::find(1);
@endphp
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="title" content="{{ $seo->meta_title }}" />
    <meta name="author" content="{{ $seo->meta_author }}" />
    <meta name="keywords" content="{{ $seo->meta_keyword }}" />
    <meta name="description" content="{{ $seo->meta_description }}" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('Frontend/assets/imgs/banner/paYield2 Logo.png') }}" />
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('Frontend/assets/css/plugins/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('Frontend/assets/css/plugins/slider-range.css') }}" />
    <link rel="stylesheet" href="{{ asset('Frontend/assets/css/main.css?v=5.3') }}" />

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script src="https://js.stripe.com/v3/"></script>


</head>

<style>
    .hero-slider-1 .single-hero-slider {
        height: 700px !important;

    }
</style>


<body>
    <!-- Modal -->

    <!-- Quick view -->
    @include('frontend.home_components.quickview')

    @include('frontend.home_components.header')

    <main class="main">
        @yield('main')

    </main>

    @include('frontend.home_components.footer')

    <!-- Preloader Start -->

    {{--   

         <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="text-center">
                    <img src="{{ asset('Frontend/assets/imgs/theme/loading.gif') }}" alt="" />
                </div>
            </div>
        </div>
    </div>
        
        
    --}}






    <!-- Vendor JS-->
    <script src="{{ asset('Frontend/assets/js/vendor/modernizr-3.6.0.min.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
    {{-- <script src="{{ asset('Frontend/assets/js/vendor/jquery-migrate-3.3.0.min.js') }}"></script> --}}
    <script src="{{ asset('Frontend/assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/slick.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/jquery.syotimer.min.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/waypoints.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/wow.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/slider-range.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/magnific-popup.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/select2.min.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/counterup.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/images-loaded.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/isotope.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/scrollup.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/jquery.vticker-min.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/jquery.theia.sticky.js') }}"></script>
    <script src="{{ asset('Frontend/assets/js/plugins/jquery.elevatezoom.js') }}"></script>
    <!-- Template  JS -->
    <script src="{{ asset('Frontend/assets/js/main.js?v=5.3') }}"></script>
    <script src="{{ asset('Frontend/assets/js/shop.js?v=5.3') }}"></script>
    <script src="{{ asset('Frontend/assets/js/script.js') }}"></script>

    <!---Sweetalert code -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;

                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;

                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;

                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
        @endif
    </script>

    <!--Sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

  
        <!--Added By Nada-->
        <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

        <script>
    // Set CSRF token globally for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });  </script>
// product details 
<!-- product details -->


<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>

/**
 * Add To Cart For ((Quick View && Product Details ))
 * Click Button Then add to cart with updated quantity
 * &
 * Update Quantity when just increasing and decreasing it
 */


 function addToCart(button) {
    let form = button.closest('form');
    let quantityInput = form.querySelector('input.qty-val');
    let quantity = quantityInput ? parseInt(quantityInput.value) : 1;

    let formData = new FormData(form);
    formData.set('product_quantity', quantity); // Ensure quantity is current

    fetch('/cart/product/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.success);

            // Update cart UI
            $('#mainCartQty').text(data.cartQty);
            $('#productCount').text(data.cartQty);
            $('#cartQty').text(data.cartQty);
            $('#mainCartTotal').text(data.cartTotal);

            // Optionally refresh mini cart
            miniCart();
        } else if (data.error) {
            toastr.error(data.error);
        }
    })
    .catch(error => {
        toastr.error('Failed to add product to cart.');
        console.error('Error:', error);
    });
}

/**
 * Add To Cart For ((The Remain buttons  ))
 * 
 */

function handleAddToCart(button) {
    // Look for the nearest .product-data container within the closest parent .product-cart-wrap
    let productData = button.closest('.product-cart-wrap').querySelector('.product-data');

    if (!productData) {
        toastr.error('Product data not found.');
        return;
    }

    let product_id = productData.querySelector('input[name="product_id"]').value;
    let product_color = productData.querySelector('input[name="product_color"]').value;
    let product_size = productData.querySelector('input[name="product_size"]').value;
    let vendor_id = productData.querySelector('input[name="vendor_id"]').value;
    let product_quantity = productData.querySelector('input[name="product_quantity"]').value;

    console.log('Product Data:', {
        product_id,
        product_color,
        product_size,
        vendor_id,
        product_quantity
    });

    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let formData = new FormData();
    formData.append('product_id', product_id);
    formData.append('product_color', product_color);
    formData.append('product_size', product_size);
    formData.append('vendor_id', vendor_id);
    formData.append('product_quantity', product_quantity);

    fetch('/cart/add-to-cart', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response Data:', data); // Log response for debugging
        if (data.success) {
            toastr.success(data.success);
            $('#mainCartQty').text(data.cartQty);
            $('#productCount').text(data.cartQty);
            $('#cartQty').text(data.cartQty);
            $('#mainCartTotal').text(data.cartTotal);
            if (typeof miniCart === 'function') miniCart();
        } else if (data.error) {
            toastr.error(data.error);
        }
    })
    .catch(error => {
        toastr.error('Failed to add product to cart.');
        console.error('Error:', error);
    });
}






// ---------- Remove Cart Item ----------
function removeCartItem(rowId) {
    $.ajax({
        type: 'GET',
        url: `/cart/remove/${rowId}`,
        success: function(response) {
            toastr.success(response.success);
            loadCartItems();
            miniCart();
        },
        error: function() {
            toastr.error('Failed to remove item.');
        }
    });
}

// ---------- Update Local Summary UI ----------
function updateCartSummaryUI() {
    let totalQty = 0;
    let totalPrice = 0;

    $('#cartPage tr').each(function () {
        const qty = parseInt($(this).find('input.qty-val').val()) || 0;
        const price = parseFloat($(this).find('.price span').attr('data-unit-price')) || 0;
        totalQty += qty;
        totalPrice += price * qty;
    });

    $('#productCount').text(totalQty);
    $('#mainCartQty').text(totalQty);
    $('#cartQty').text(totalQty);
    $('#mainCartTotal').text(totalPrice.toFixed(2));
}

// ---------- Qty Up and Down ----------
$('body').on('click', '.qtyCart-up', function () {
    const $input = $(this).siblings('input.qty-val');
    const $cartItem = $(this).closest('tr');
    const currentQty = parseInt($input.val());
    const availableStock = parseInt($cartItem.attr('data-stock')) || 9999;
    const priceText = $cartItem.find('.price span').attr('data-unit-price');
    const pricePerUnit = parseFloat(priceText);
    const rowId = $(this).attr('id');

    if (currentQty >= availableStock) {
        toastr.warning(`Maximum quantity available is ${availableStock}.`);
        return;
    }

    const newQty = currentQty + 1;
    $input.val(newQty);
    const newSubtotal = (pricePerUnit * newQty).toFixed(2);
    $cartItem.find('[data-title="Subtotal"] span').text(`${newSubtotal} EGP`);

    updateCartSummaryUI();
    
    cartIncrement(rowId);
});

$('body').on('click', '.qtyCart-down', function () {
    const $input = $(this).siblings('input.qty-val');
    const $cartItem = $(this).closest('tr');
    const currentQty = parseInt($input.val());
    const priceText = $cartItem.find('.price span').attr('data-unit-price');
    const pricePerUnit = parseFloat(priceText);
    const rowId = $(this).attr('id');

    if (currentQty > 1) {
        const newQty = currentQty - 1;
        $input.val(newQty);
        const newSubtotal = (pricePerUnit * newQty).toFixed(2);
        $cartItem.find('[data-title="Subtotal"] span').text(`${newSubtotal} EGP`);

        updateCartSummaryUI();
     
        cartDecrement(rowId);
    }
});

// ---------- AJAX Cart Qty Change ----------
function cartIncrement(rowId) {
    $.ajax({
        type: 'GET',
        url: `/cart/increment/${rowId}`,
        success: function(response) {
            if (response.success) {
                loadCartItems();
                miniCart();
                getCouponCalculation(); 
                $('#mainCartQty').text(response.cartQty);
                $('#productCount').text(response.cartQty);
                $('#cartQty').text(response.cartQty);
                $('#mainCartTotal').text(response.cartTotal);
            } else if (response.warning) {
                toastr.warning(response.warning);
            }
        },
        error: function() {
            toastr.error('Failed to increase quantity.');
        }
    });
}

function cartDecrement(rowId) {
    $.ajax({
        type: 'GET',
        url: `/cart/decrement/${rowId}`,
        success: function(response) {
            if (response.success) {
                loadCartItems();
                miniCart();
                getCouponCalculation(); 
                $('#mainCartQty').text(response.cartQty);
                $('#productCount').text(response.cartQty);
                $('#cartQty').text(response.cartQty);
                $('#mainCartTotal').text(response.cartTotal);
            } else if (response.warning) {
                toastr.warning(response.warning);
            }
        },
        error: function() {
            toastr.error('Failed to decrease quantity.');
        }
    });
}

// ---------- Load Cart Items ----------
function loadCartItems() {
    $.ajax({
        type: 'GET',
        url: '/cart/product/get',
        dataType: 'json',
        success: function(response) {
                 // Handle guest users - silently return
            // if (!response.authenticated) return;
            if (response.carts) {
                let cartHtml = '';
                $.each(response.carts, function(index, item) {
                    cartHtml += `
<tr data-row-id="${item.rowId}" data-stock="${item.options.stock}">
    <td class="custome-checkbox start pl-30">
        <img src="/${item.options.image}" width="50" alt="${item.name}" class="mr-3">
    </td>
    <td class="product-image" colspan="2">
        <div class="d-flex align-items-center">
            <h6 class="mb-5">
                <a class="product-name mb-10 text-heading">${item.name}</a>
            </h6>
        </div>
    </td>
    <td class="price" data-title="Price">
        <span data-unit-price="${item.price}">${item.price} EGP</span>
    </td>
    <td class="text-left" data-title="Color"><span>${item.options.color || 'N/A'}</span></td>
    <td class="text-left" data-title="Size"><span>${item.options.size || 'N/A'}</span></td>
    <td class="text-left detail-info" data-title="Qty">
        <div class="detail-extralink mr-15">
            <div class="detail-qty border radius">
                <a href="javascript:void(0);" class="qtyCart-down qty-down" id="${item.rowId}"><i class="fi-rs-angle-small-down"></i></a>
                <input type="text" name="product_quantity" class="qty-val" value="${item.qty}" min="1" readonly>
                <a href="javascript:void(0);" class="qtyCart-up qty-up" id="${item.rowId}"><i class="fi-rs-angle-small-up"></i></a>
            </div>
        </div>
    </td>
    <td class="text-left" data-title="Subtotal">
        <span>${(item.qty * item.price).toFixed(2)} EGP</span>
    </td>
    <td class="text-left" data-title="Remove">
        <button class="btn btn-sm btn-danger" onclick="removeCartItem('${item.rowId}')">×</button>
    </td>
</tr>
                    `;
                });

                $('#cartPage').html(cartHtml);
                $('#mainCartQty').text(response.cartQty);
                $('#productCount').text(response.cartQty);
                $('#cartQty').text(response.cartQty);
                $('#mainCartTotal').text(response.cartTotal);
                $('#cartSubTotal').text(response.cartSubTotal);
              
            }
        },
            error: (xhr) => {
            // if (xhr.status === 401 || xhr.status === 403 ) {
            //     // Guest user or unauthorized - do nothing silently
            //     return;
            // }
            showToast('error', 'Failed to load compare products.');
        }
    });
}


// ---------- Remove Item From Mini Cart ----------
function miniCartRemove(rowId) {
    $.ajax({
        type: 'GET',
        url: '/cart/mini/product/remove/' + rowId,
        dataType: 'json',
        success: function(response) {
            // Show success message
            toastr.success(response.success);

              getCouponCalculation(); 
              loadCartItems();
              updateCartSummaryUI();
            // Reload the mini cart to reflect changes
            miniCart();
        },
        error: function() {
            toastr.error('Failed to remove the item from the cart.');
        }
    });
}


/// ---------- Load Mini Cart ----------
function miniCart() {
    $.ajax({
        type: 'GET',
        url: '/cart/mini/product',
        dataType: 'json',
        success: function(response) {
            // If user not authenticated, silently return (no error message)
            // if (!response.authenticated) return;

            console.log('Mini cart response:', response); // debug

            // Update subtotal and quantities
            $('#cartSubTotal').text(response.cartSubTotal);
            $('#cartQty').text(response.cartQty);
            $('#mcartQty').text(response.cartQty);

            // Build mini cart items HTML
            let miniCartHtml = '';
            $.each(response.carts, function(key, value) {
                miniCartHtml += `
                    <li>
                        <div class="shopping-cart-img">
                            <a href="shop-product-right.html">
                                <img alt="${value.name}" src="/${value.options.image}" style="width:50px;height:50px;" />
                            </a>
                        </div>
                        <div class="shopping-cart-title" style="margin: -73px 74px 14px; width: 146px;">
                            <h4><a href="shop-product-right.html">${value.name}</a></h4>
                            <h4><span>${value.qty} × </span>${value.price} EGP</h4>
                        </div>
                        <div class="shopping-cart-delete" style="margin: -85px 1px 0px;">
                            <a href="javascript:void(0);" id="${value.rowId}" onclick="miniCartRemove(this.id)">
                                <i class="fi-rs-cross-small"></i>
                            </a>
                        </div>
                    </li>`;
            });

            $('#miniCart').html(miniCartHtml);
            $('#mminiCart').html(miniCartHtml);
        },
        error: function(xhr) {
            // if (xhr.status === 401 || xhr.status === 403) {
            //     // Guest or unauthorized — just silently ignore
            //     return;
            // }
            toastr.error('Failed to load mini cart.');
        }
    });
}

// Run on page load
$(document).ready(function() {
    miniCart();
});






 

</script>


    <!-- End -->



      <!---------------------------------------------------------------------------------------->
    <!------------------------------Show Coupon--------------------------------------------->
    <!---------------------------------------------------------------------------------------->
<!-- Start Coupon -->

<!-- Start Coupon -->
<script type="text/javascript">
    // Apply Coupon Function
    function applyCoupon() {
        var coupon_name = $('#coupon_name').val();
        console.log('Applying coupon:', coupon_name);  // Debugging log

        $.ajax({
            type: "POST",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Ensure CSRF token is included
            },
            data: {
                coupon_name: coupon_name
            },
            url: "/coupon/apply",
            success: function(data) {
                console.log('Coupon applied response:', data);  // Debugging log

                getCouponCalculation();

                if (data.validity === true) {
    $('#couponWrapper').hide();
}

                Swal.fire({
                    toast: 'true',
                    position: 'top-end',
                    icon: data.validity ? 'success' : 'error',
                    title: data.validity ? data.success : data.error,
                    showConfirmButton: false,
                    timer: 3000
                });
            },
            error: function(xhr, status, error) {
                console.error("Error applying coupon:", error);  // Debugging log
                Swal.fire({
                    toast: 'true',
                    position: 'top-end',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    }

    // Calculate Coupon Details
function getCouponCalculation() {
    $.ajax({
        type: 'GET',
        url: '/coupon/calculation',
        dataType: 'json',
        success: function(data) {
            console.log('Coupon calculation data:', data);  // Debugging log

            let html = '';

            if (data.coupon_name) {
                html = `
                    <tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Subtotal</h6>
                        </td>
                        <td class="cart_total_amount">
                            <h4 class="text-brand text-end">$${data.subtotal}</h4>
                        </td>
                    </tr>
                    <tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Coupon: ${data.coupon_name} <a href="javascript:void(0);" onclick="couponRemove()"><i class="fi-rs-trash"></i> Remove</a></h6>
                        </td>
                        <td class="cart_total_amount">
                            <h6 class="text-brand text-end">- $${data.discount_amount}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Grand Total</h6>
                        </td>
                        <td class="cart_total_amount">
                            <h4 class="text-brand text-end">$${data.total_amount}</h4>
                        </td>
                    </tr>`;
            } else {
                html = `
                    <tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Subtotal</h6>
                        </td>
                        <td class="cart_total_amount">
                            <h4 class="text-brand text-end">$${data.total}</h4>
                        </td>
                    </tr>
                    <tr>
                        <td class="cart_total_label">
                            <h6 class="text-muted">Grand Total</h6>
                        </td>
                        <td class="cart_total_amount">
                            <h4 class="text-brand text-end">$${data.total}</h4>
                        </td>
                    </tr>`;
            }

            $('#couponCalField').html(html);  // Assuming you want to update a cart details container
        },
        error: function(xhr, status, error) {
            console.error("Error calculating coupon:", error);  // Debugging log
        }
    });
}
$(document).ready(function() {
    // Apply Coupon logic and then call getCouponCalculation
    // applyCoupon();  // Assuming applyCoupon is a function you've defined
    getCouponCalculation();  // Call the function to recalculate coupon data
});

    // Coupon Remove Method
    function couponRemove() {
        $.ajax({
            type: 'GET',
            url: '/coupon/remove',
            success: function(data) {
                console.log('Coupon removed:', data);  // Debugging log
                getCouponCalculation();  // Recalculate the cart totals after coupon removal

                Swal.fire({
                    toast: 'true',
                    position: 'top-end',
                    icon: 'success',
                    title: 'Coupon removed successfully',
                    showConfirmButton: false,
                    timer: 3000
                });

                $('#couponWrapper').show();  // Show coupon form again if needed
            },
            error: function(xhr, status, error) {
                console.error("Error removing coupon:", error);  // Debugging log
                Swal.fire({
                    toast: 'true',
                    position: 'top-end',
                    icon: 'error',
                    title: 'Failed to remove coupon',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        });
    }

    // Handle form submission
    $('#couponForm').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting and reloading the page
        applyCoupon(); // Call the applyCoupon function
    });
</script>
<!-- End Coupon -->



    <!------------------------------------------------------------------------------------------------------------->
    <!----------------------------------Adding Products To WishList---------------------------------------------->
    <!------------------------------------------------------------------------------------------------------------->
  <script>
function addToWishList(product_id) {
    $.ajax({
        type: "POST",
        url: "/wishlist/add/" + product_id,
        data: {
            _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(data) {
            // If user not authenticated, silently return (no error message)
            if (!response.authenticated) return;
            if (data.success) {
                toastr.success(data.success);

                // Call your existing wishlist() function to refresh count and table
                wishlist();

            } else if (data.error) {
                toastr.error(data.error);
            }
        },
       error: function(xhr) {
            if (xhr.status === 401 || xhr.status === 403) {
                toastr.error('You Should Login First');
                return;
            }
            toastr.error('Something went wrong while adding to Wishlist.');
        }
    });
}

</script>

    <!---------------------------------------------------------------------------------------->
    <!------------------------------Show Products themselves in Wishlist template---------------------------------------------->
    <!---------------------------------------------------------------------------------------->



    <!--- get wishlist product -->
    <script>
   // Show Products in wishlist template using AJAX
function wishlist() {
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: "/wishlist/get/products/",
        success: function(response) {
            $('#wishQty').text(response.wishQty);

            var rows = "";

            $.each(response.wishlist, function(key, value) {
                var rating = 0;
                var percentage = 0;

                // Check if reviews exist for the product
                if (value.product.reviews && value.product.reviews.length > 0) {
                    rating = value.product.reviews[0].rating || 0;
                    percentage = (rating / 5) * 100;
                }

 rows += `
<tr class="pt-30 product-cart-wrap">
    <td class="custome-checkbox pl-30">
        <input class="form-check-input" type="checkbox" name="checkbox" />
    </td>
    <td class="image product-thumbnail pt-40">
        <img src="/${value.product.product_thumbnail}" alt="#" />
    </td>
    <td class="product-des product-name">
        <h6><a class="product-name mb-10" href="#">${value.product.product_name}</a></h6>
        <div class="product-rate-cover">
            <div class="product-rate d-inline-block">
                <div class="product-rating" style="width: ${percentage}%"></div>
            </div>
            <span class="font-small ml-5 text-muted">(${rating.toFixed(1)})</span>
        </div>
    </td>
    <td class="price" data-title="Price">
        ${value.product.discount_price === null
            ? `<h4 class="text-brand">${value.product.selling_price}</h4>`
            : `<h4 class="text-brand">${value.product.discount_price}</h4>`
        }
    </td>
    <td class="text-center detail-info" data-title="Stock">
        <span class="stock-status in-stock mb-0">
            ${value.product.status == 1 ? `In Stock` : `Out of Stock`}
        </span>
    </td>
    <td class="text-right" data-title="Cart">
        <div class="product-data d-none">
            <input type="hidden" name="product_id" value="${value.product.id ?? ''}">
            <input type="hidden" name="product_color" value="${value.product.product_color ?? ''}">
            <input type="hidden" name="product_size" value="${value.product.product_size ?? ''}">
            <input type="hidden" name="vendor_id" value="${value.product.vendor_id ?? ''}">
            <input type="hidden" name="product_quantity" value="1">
        </div>


        <button class="btn btn-sm" onclick="handleAddToCart(this)">Add to cart</button>
    </td>
    <td class="action text-center" data-title="Remove">
        <a href="#" class="text-body" id="${value.id}" onclick="wishlistRemove(this.id)">
            <i class="fi-rs-trash"></i>
        </a>
    </td>
</tr>
`;


            });

            $('#wishlist').html(rows);
        }
    });
}

wishlist();




    </script>

    <!-- End -->
<script>
    // ---------- Helper to Update Total ----------
function updateCartTotal(total) {
    $('#mainCartTotal').text(total);
}

// ---------- On Page Load ----------
$(document).ready(function() {
    loadCartItems();
    miniCart(); // ✅ still call here — backend should handle auth check
    loadCartItems();
getCouponCalculation();

});
</script>

<!------------------------------------------------------------------------------------------------>
<!-------------------------Show Wishlist Count when any updates happened (delete,add,...)----------->
<!------------------------------------------------------------------------------------------------->

<script>
    $(document).ready(function () {
        // Fetch the wishlist count when the page loads
        $.ajax({
            type: 'GET',
            url: '/wishlist/count',
            dataType: 'json',
            success: function (data) {
                $('#wishListQty').text(data.count);
            },
            error: function () {
                console.error('Failed to fetch wishlist count.');
            }
        });
    });
</script>

      <!--End-->

<!------------------------------------------------------------------------------------------------>
<!-------------------------Remove Wishlist Products------------------------------------------------>
<!------------------------------------------------------------------------------------------------->

<script>
    function wishlistRemove(id) {
        event.preventDefault(); 
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: "/wishlist/remove/" + id,

            success: function(data) {
                wishlist(); // Assuming this refreshes the wishlist view

                // 🔁 Fetch and update the wishlist count
                $.ajax({
                    type: 'GET',
                    url: '/wishlist/count',
                    dataType: 'json',
                    success: function (res) {
                        $('#wishListQty').text(res.count);
                    },
                    error: function () {
                        console.error('Failed to update wishlist count after removal.');
                    }
                });

                // ✅ Show a SweetAlert2 success toast
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.success,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }
        });
    }
</script>
<!--End Method-->

    

   
<!------------------------------------------------------------------------------------------------>
<!-------------------------Start Compare Ajax-------------------------------------------------->
<!--------------------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------------------------->

<!-- SweetAlert Toast Helper -->
<script>
    const showToast = (type, message) => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 3000
        });
    };
</script>

<!--Add To Compare-->
<script>
    const addToCompare = (productId) => {
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: `/compare/add/${productId}`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (data) => {
            // If user not authenticated, silently return (no error message)
            // if (!response.authenticated) return;
                fetchCompareProducts();
                if (data.success) {
                    showToast('success', data.success);
                } else {
                    showToast('error', data.error);
                }
            },

                 error: function(xhr) {
            if (xhr.status === 401 || xhr.status === 403) {
                toastr.error('You Should Login First');
                return;
            }
            toastr.error('Something went wrong while adding to compare.');
        }
        });
    };
</script>

<!--Get Compare Products-->
<script>
    const fetchCompareProducts = () => {
    $.ajax({
        type: "GET",
        dataType: 'json',
        url: "/compare/product/get",
        success: (response) => {
            // Handle guest users - silently return
            if (!response.authenticated) return;

            $('#compareQty').text(response.compareQty);

            let rows = '';
            response.compare.forEach(({ id, product }) => {
                const price = product.discount_price 
                    ? `<h4 class="price text-brand">$${product.discount_price}</h4>` 
                    : `<h4 class="price text-brand">$${product.selling_price}</h4>`;

                const stock = product.product_quantity > 0 
                    ? `<span class="stock-status in-stock mb-0">In Stock</span>` 
                    : `<span class="stock-status out-stock mb-0">Stock Out</span>`;

                rows += `
                    <tr class="pr_title">
                        <td class="row_img"><img src="/${product.product_thumbnail}" style="width:300px; height:300px;" alt="compare-img" /></td>
                        <td class="product_name"><h6><a href="#" class="text-heading">${product.product_name}</a></h6></td>
                        <td class="product_price">${price}</td>
                        <td class="row_text font-xs"><p class="font-sm text-muted">${product.short_descp}</p></td>
                        <td class="row_stock">${stock}</td>
                        <td class="row_remove">
                            <a href="javascript:void(0);" class="text-muted" onclick="removeCompare(${id})">
                                <i class="fi-rs-trash mr-5"></i><span>Remove</span>
                            </a>
                        </td>
                    </tr>
                `;
            });

            $('#compare').html(rows);
        },
        error: (xhr) => {
            if (xhr.status === 401 || xhr.status === 403) {
                // Guest user or unauthorized - do nothing silently
                return;
            }
            showToast('error', 'Failed to load compare products.');
        }
    });
};

// Load on page load
$(document).ready(() => {
    fetchCompareProducts();
});

</script>

<!--Remove from Compare-->
<script>
    const removeCompare = (id) => {
        $.ajax({
            type: "GET",
            dataType: 'json',
            url: `/compare/remove/${id}`,
            success: (data) => {
                fetchCompareProducts();
                if (data.success) {
                    showToast('success', data.success);
                } else {
                    showToast('error', data.error);
                }
            },
            error: () => {
                showToast('error', 'Failed to remove product from compare.');
            }
        });
    };
</script>




</body>

</html>
