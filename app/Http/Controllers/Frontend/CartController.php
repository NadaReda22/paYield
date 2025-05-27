<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ShipDistrict;
use App\Models\ShipDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ShipState;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller

{

    /**
     * View Cart Template
     */
    public function myCart()
    {
        return view('frontend.mycart.view_mycart');
    }


    /**
     * Add To Cart 
     */

// Add To Cart  for (())
// Add To Cart
// Add To Cart
// Add To Cart
public function addToCart(Request $request)
{
    Log::info('Add to Cart Request:', $request->all());
    Session::forget('coupon');

    $data = $request->only(['product_id', 'product_quantity', 'product_color', 'product_size', 'vendor_id']);
    $product = Product::findOrFail($data['product_id']);
    $price = $product->discount_price ?? $product->selling_price;

    // Total quantity of this product (any variations) in the cart
    $totalQuantityInCart = Cart::content()->filter(function ($cartItem) use ($product) {
        return $cartItem->id === $product->id;
    })->sum('qty');

    $availableStock = $product->product_quantity;

    // Global stock validation
    if ($totalQuantityInCart + $data['product_quantity'] > $availableStock) {
        return response()->json([
            'error' => "Only {$availableStock} units available in stock. You currently have {$totalQuantityInCart} in the cart.",
        ]);
    }

    // Check if the same variation (color & size) already exists
    $existingItem = Cart::content()->filter(function ($cartItem) use ($product, $data) {
        return $cartItem->id === $product->id &&
               $cartItem->options->color === ($data['product_color'] ?? null) &&
               $cartItem->options->size === ($data['product_size'] ?? null);
    })->first();

    // Quantity for the exact variation
    $variationQty = $existingItem ? $existingItem->qty : 0;
    $requestedQty = $data['product_quantity'];
    $variationTotal = $variationQty + $requestedQty;

    // Per-variation check (optional: you can customize this logic based on product structure)
    if ($variationTotal > $product->product_quantity) {
        return response()->json([
            'error' => "The selected variation (Color: {$data['product_color']}, Size: {$data['product_size']}) has only {$product->product_quantity} items in stock. You already have {$variationQty} in cart.",
        ]);
    }

    if ($existingItem) {
        Cart::update($existingItem->rowId, [
            'qty' => $variationTotal,
            'price' => (float) $price,
        ]);
    } else {
        Cart::add([
            'id' => $product->id,
            'name' => $product->product_name,
            'qty' => $requestedQty,
            'weight' => 1,
            'price' => (float) $price,
            'options' => [
                'color' => $data['product_color'] ?? null,
                'size' => $data['product_size'] ?? null,
                'image' => $product->product_thumbnail,
                'vendor_id' => $product->user_id,
                'stock' => $product->product_quantity,
            ],
        ]);
    }

    // Subtotal calculation
    $cartSubTotal = Cart::content()->reduce(function ($total, $cartItem) {
        return $total + ($cartItem->qty * $cartItem->price);
    }, 0);

    Log::info('Cart Content:', Cart::content()->toArray());

    return response()->json([
        'success' => 'Product successfully added to your cart.',
        'cartQty' => Cart::count(),
        'cartTotal' => (float) str_replace(',', '', Cart::total()),
        'cartSubTotal' => number_format($cartSubTotal, 2, '.', ''),
        'carts' => Cart::content(),
    ]);
}







    
    
    /**
     * View MINI CART
     */

// View Mini Cart
public function viewMiniCart()
{
    // Check if user authenticated
    if (Auth::check()) {
        // Authenticated user's cart (usually saved in DB or user session)
        $cartContents = Cart::content();
        $cartSubTotal = $cartContents->reduce(function ($total, $cartItem) {
            return $total + ($cartItem->qty * $cartItem->price);
        }, 0);

        return response()->json([
            'authenticated' => true,
            'carts' => $cartContents,
            'cartQty' => Cart::count(),
            'cartTotal' => Cart::total(),
            'cartSubTotal' => number_format($cartSubTotal, 2, '.', ''),
        ], 200);
    } else {
        // Guest user: cart stored in session (default Cart::content() also uses session)
        $cartContents = Cart::content();
        if ($cartContents->isEmpty()) {
            // If no cart session exists yet, return empty cart response
            return response()->json([
                'authenticated' => false,
                'carts' => [],
                'cartQty' => 0,
                'cartTotal' => '0.00',
                'cartSubTotal' => '0.00',
            ], 200);
        }

        $cartSubTotal = $cartContents->reduce(function ($total, $cartItem) {
            return $total + ($cartItem->qty * $cartItem->price);
        }, 0);

        return response()->json([
            'authenticated' => false,
            'carts' => $cartContents,
            'cartQty' => Cart::count(),
            'cartTotal' => Cart::total(),
            'cartSubTotal' => number_format($cartSubTotal, 2, '.', ''),
        ], 200);
    }
}


    
    /**
     * Remove From MINI CART
     */

    public function removeFromMiniCart($rowId){

        Cart::remove($rowId);
        return response()->json(['success' => 'Product Remove From Cart']);

    }

    /**
     * GET CART Contents
     */

public function getCartContents()
{
       // Cart::content() uses session storage by default for guests
    $cartContents = Cart::content();

    // Calculate subtotal manually if your Cart package doesn't provide subtotal()
    $cartSubTotal = $cartContents->reduce(function ($total, $cartItem) {
        return $total + ($cartItem->qty * $cartItem->price);
    }, 0);

    return response()->json([
        'success' => true,
        'carts' => $cartContents,
        'cartQty' => Cart::count(),
        'cartTotal' => Cart::total(),
        'cartSubTotal' => number_format($cartSubTotal, 2, '.', ''),
        'authenticated' => Auth::check(),
    ]);

}


    /**
     * Remove CART Item
     */
     
     public function removeCartItem($rowId)
     {
        //first remove item
        Cart::remove($rowId);        
        //Then check coupon existing
        $this->recalculateCoupon();

        return response()->json(['success' => 'Product removed from cart successfully.']);
     }



// Cart Increment
public function cartIncrement($rowId)
{
    $row = Cart::get($rowId);
    $product = Product::findOrFail($row->id);
    $availableStock = $product->product_quantity;

    $newQty = $row->qty + 1;

    if ($newQty <= $availableStock) {
        // Update quantity in the cart
        Cart::update($rowId, ['qty' => $newQty]);

        // Persist the cart in session if using session-based cart
        Cart::store(session()->getId());

        // Recalculate coupon (if applicable)
        $this->recalculateCoupon();

        // Calculate new subtotal (without tax)
        $cartSubTotal = Cart::content()->reduce(function ($total, $cartItem) {
            return $total + ($cartItem->qty * $cartItem->price); // Subtotal without tax
        }, 0);

        return response()->json([
            'success'  => true,
            'cartQty'  => Cart::content()->sum('qty'), // Total quantity
            'cartTotal' => number_format($cartSubTotal, 2), // Subtotal (without tax)
        ]);
    }

    return response()->json([
        'warning' => "Only $availableStock items available in stock.",
    ]);
}

// Cart Decrement
public function cartDecrement($rowId)
{
    $row = Cart::get($rowId);

    if ($row->qty > 1) {
        // Decrease quantity in the cart
        Cart::update($rowId, ['qty' => $row->qty - 1]);

        // Persist the cart in session if using session-based cart
        Cart::store(session()->getId());

        // Recalculate coupon (if applicable)
        $this->recalculateCoupon();

        // Calculate new subtotal (without tax)
        $cartSubTotal = Cart::content()->reduce(function ($total, $cartItem) {
            return $total + ($cartItem->qty * $cartItem->price); // Subtotal without tax
        }, 0);

        return response()->json([
            'success'  => true,
            'cartQty'  => Cart::content()->sum('qty'), // Total quantity
            'cartTotal' => number_format($cartSubTotal, 2), // Subtotal (without tax)
        ]);
    }

    return response()->json(['warning' => 'Minimum quantity is 1']);
}

     
        /**
         * Function To check if coupon existing
         */
        private function recalculateCoupon()
        {
            // Calculate the subtotal (without tax)
            $cartSubTotal = Cart::content()->reduce(function ($total, $cartItem) {
                // Subtotal is calculated by multiplying quantity with the price (excluding tax)
                return $total + ($cartItem->qty * $cartItem->price);
            }, 0);
        
            // Check if there's a coupon in the session
            if (Session::has('coupon')) {
                $couponName = Session::get('coupon')['coupon_name'];
                $coupon = Coupon::where('coupon_name', $couponName)->first();
        
                if ($coupon) {
                    // Calculate the discount amount based on the subtotal (without tax)
                    $discountAmount = round($cartSubTotal * $coupon->coupon_discount / 100);
                    
                    // Calculate the new total after applying the discount
                    $newTotal = round($cartSubTotal - $discountAmount);
        
                    /**
                     * Store all coupon data in the session under the 'coupon' key.
                     * This is used later in other parts of the application like order placement or cart total display.
                     */
                    Session::put('coupon', [
                        'coupon_name'     => $coupon->coupon_name,
                        'coupon_discount' => $coupon->coupon_discount,
                        'discount_amount' => $discountAmount,
                        'total_amount'    => $newTotal,  // The new total after discount
                    ]);
                }
            }
        
            // Log the cart content for debugging purposes
            Log::info('Cart Content:', Cart::content()->toArray());
        }
        



/*-------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------COUPON------------------------------------------*/
/*-------------------------------------------------------------------------------------------------------------*/


// Apply Coupon
// Apply Coupon
public function applyCoupon(Request $request)
{
    // Log to check the coupon_name received
    Log::info("Applying Coupon: " . $request->coupon_name);

    // Fetch the coupon details from the database
    $coupon = Coupon::where('coupon_name', $request->coupon_name)
        ->where('coupon_validity', '>=', Carbon::now()->format('Y-m-d'))
        ->first();

    // Calculate the subtotal (excluding tax)
    $subtotal = Cart::content()->reduce(function ($total, $cartItem) {
        return $total + ($cartItem->qty * $cartItem->price); // Subtotal without tax
    }, 0);

    if ($coupon) {
        // Calculate the discount amount based on the subtotal
        $discountAmount = round($subtotal * $coupon->coupon_discount / 100);
        
        // Calculate the new total after applying the discount
        $newTotal = round($subtotal - $discountAmount);

        // Store coupon details in the session
        Session::put('coupon', [
            'coupon_name'     => $coupon->coupon_name,
            'coupon_discount' => $coupon->coupon_discount,
            'discount_amount' => $discountAmount,
            'total_amount'    => $newTotal,
        ]);

        return response()->json([
            'validity' => true,
            'success'  => 'Coupon applied successfully',
        ]);
    }

    return response()->json([
        'validity' => false,
        'error'    => 'Invalid or expired coupon',
    ]);
}

// Get Coupon Calculation
public function getCalculationCoupon()
{
    // Calculate the subtotal (excluding tax)
    $subtotal = Cart::content()->reduce(function ($total, $cartItem) {
        return $total + ($cartItem->qty * $cartItem->price); // Subtotal without tax
    }, 0);

    if (Session::has('coupon')) {
        $coupon = Session::get('coupon');
        
        // Calculate the discount amount based on the subtotal
        $discountAmount = round($subtotal * $coupon['coupon_discount'] / 100, 2);
        
        // Calculate the new total after applying the discount
        $totalAmount = round($subtotal - $discountAmount, 2);

        // Return the calculation details as a JSON response
        return response()->json([
            'subtotal'        => number_format($subtotal, 2),
            'coupon_name'     => $coupon['coupon_name'],
            'coupon_discount' => $coupon['coupon_discount'],
            'discount_amount' => number_format($discountAmount, 2),
            'total_amount'    => number_format($totalAmount, 2),
        ]);
    }

    // Return the subtotal if no coupon is applied
    return response()->json([
        'total' => number_format($subtotal, 2),
    ]);
}



// Remove Coupon
public function removeCoupon()
{
    Log::info("Removing Coupon");  // Log coupon removal


    Session::forget('coupon');

    return response()->json([
        'success' => 'Coupon removed successfully',   
    ]);
}


//////////////////////////////////////////////////////////

public function checkoutCreate()
{
    if (!Auth::check()) {
        return redirect('/login')->with([
            'message' => 'You need to login first.',
            'alert-type' => 'error'
        ]);
    }

    if (Cart::count() <= 0) {
        return redirect('/')->with([
            'message' => 'Your cart must contain at least one product.',
            'alert-type' => 'error'
        ]);
    }

    $carts     = Cart::content();
    $cartQty   = Cart::count();
    $cartTotal = Cart::content()->reduce(function ($total, $item) {
        return $total + ($item->qty * $item->price); // Exclude tax
    }, 0);

    $divisions = ShipDivision::orderBy('division_name', 'ASC')->get();
    $districts = ShipDistrict::orderBy('district_name', 'ASC')->get();
    $states = ShipState::orderBy('state_name', 'ASC')->get();



    return view('frontend.checkout.checkout_view', compact(
        'carts', 'cartQty', 'cartTotal', 'divisions','districts','states',
    ));
}



}












