<?php


namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;

use Stripe\Charge;
use Stripe\Stripe;
use App\Models\User;
use App\Models\Order;
use App\Mail\OrderMail;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderCompletedNotification;
use Gloudemans\Shoppingcart\Facades\Cart; // if you use this package

class StripeController extends Controller
{
    public function StripeOrder(Request $request)
    {
        // Calculate total amount (handle coupons etc.)
        $total_amount = Session::has('coupon') ? Session::get('coupon')['total_amount'] : round(floatval(Cart::total()));

         $admins = User::where('role', 'admin')->get();


        // Set Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Get token from frontend
        $token = $request->stripeToken;

        // Create charge
        $charge = Charge::create([
            'amount' => $total_amount * 100, // amount in cents
            'currency' => 'usd',
            'description' => 'Your Store Description',
            'source' => $token,
            'metadata' => ['order_id' => uniqid()]
        ]);

        // Store order and order items in DB
        $order_id = Order::insertGetId([
            'user_id' => Auth::id(),
            'ship_division_id' => $request->division_id,
            
            'ship_district_id' => $request->district_id,
            'ship_state_id' => $request->state_id,
            // your other fields here from $request
            'name' => $request->name ?? Auth::user()->name ,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'notes' => $request->notes,
            'payment_type' => $charge->payment_method,
            'payment_method' => 'Stripe',
            'transaction_id' => $charge->balance_transaction,
            'currency' => $charge->currency,
            'amount' => $total_amount,
            'order_number' => $charge->metadata->order_id,
            'invoice_no' => 'EOS' . mt_rand(10000000, 99999999),
            'order_date' => Carbon::now()->format('d F Y'),
            'order_month' => Carbon::now()->format('F'),
            'order_year' => Carbon::now()->format('Y'), 
            'status' => 'pending',
            'created_at' => Carbon::now(),
        ]);

        // Save order items from cart
        foreach (Cart::content() as $cart) {
            OrderItem::insert([
                'order_id' => $order_id,
                'product_id' => $cart->id,
                'vendor_id'  => $cart->options->vendor_id,
                'qty' => $cart->qty,
                'price' => $cart->price,
                'created_at' => Carbon::now(),
            ]);
        }

        // Send confirmation email
        $invoice = Order::findOrFail($order_id);
        $data = [
            'invoice_no' => $invoice->invoice_no,
            'amount' => $total_amount,
            'name' => $invoice->name,
            'email' => $invoice->email,
        ];
        // Mail::to($invoice->email)->send(new OrderMail($data));

        // Clear session and cart
        if (Session::has('coupon')) Session::forget('coupon');
        Cart::destroy();
    $vendors= User::where('role', 'vendor')->get();
     Notification::send($admins, new OrderCompletedNotification($request->name));
     Notification::send($vendors, new OrderCompletedNotification($request->name));

        //send order request complete to user  
       Mail::to($invoice->email)->send(new orderMail($invoice));

        // Redirect with success message
        return redirect('/')->with('message', 'Your Order Placed Successfully');
    }


    public function CashOrder(Request $request)
{
    $admins = User::where('role', 'admin')->get();
    $vendors= User::where('role', 'vendor')->get();

    $totalAmount = Session::has('coupon')
        ? Session::get('coupon')['total_amount']
        : round((float) Cart::total());

    $invoiceNo = 'EOS' . mt_rand(10000000, 99999999);
    $now = Carbon::now();

    $orderId = Order::insertGetId([
        'user_id'       => Auth::id(),
        'ship_division_id'   => $request->division_id,
        'ship_district_id'   => $request->district_id,
        'ship_state_id'      => $request->state_id,
        'name'          => $request->name,
        'email'         => $request->email,
        'phone'         => $request->phone,
        'address'        => $request->address,
        'post_code'     => $request->post_code,
        'notes'         => $request->notes,
        'payment_type'  => 'Cash On Delivery',
        'payment_method'=> 'Cash On Delivery',
        'currency'      => 'Usd',
        'amount'        => $totalAmount,
        'invoice_no'    => $invoiceNo,
        'order_date'    => $now->format('d F Y'),
        'order_month'   => $now->format('F'),
        'order_year'    => $now->format('Y'),
        'status'        => 'pending',
        'created_at'    => $now,
    ]);

    $invoice = Order::findOrFail($orderId);

    // Mail::to($invoice->email)->send(new OrderMail([
    //     'invoice_no' => $invoice->invoice_no,
    //     'amount'     => $totalAmount,
    //     'name'       => $invoice->name,
    //     'email'      => $invoice->email,
    // ]));

    foreach (Cart::content() as $cart) {
        OrderItem::insert([
            'order_id'   => $orderId,
            'product_id' => $cart->id,
            'vendor_id'  => $cart->options->vendor_id,
            'color'      => $cart->options->color,
            'size'       => $cart->options->size,
            'qty'        => $cart->qty,
            'price'      => $cart->price,
            'created_at' => $now,
        ]);
    }

    Session::forget('coupon');
    Cart::destroy();

    Notification::send($admins, new OrderCompletedNotification($request->name));
    Notification::send($vendors, new OrderCompletedNotification($request->name));

            //send order request complete to user   
       Mail::to($invoice->email)->send(new orderMail($invoice));

    return redirect('/')->with([
        'message' => 'Your order has been placed successfully.',
        'alert-type' => 'success',
    ]);
}


}
