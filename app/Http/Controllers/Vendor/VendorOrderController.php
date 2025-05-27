<?php

namespace App\Http\Controllers\Vendor;

use Dompdf\Dompdf;
use Dompdf\Options;

use App\Models\User;
use App\Models\Order;

use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ShipState;
use App\Models\ShipDistrict;
use App\Models\ShipDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class VendorOrderController extends Controller
{

    public function PendingOrder(){
        $orderitem = OrderItem::with('order')
        ->whereHas('order', function ($query) {
            $query->where('status', 'pending');
        })
        ->where('vendor_id', Auth::user()->id) // Assuming the vendor is authenticated
        ->orderBy('id', 'DESC')
        ->get();
    
    
        return view('vendor.order.pending_order',compact('orderitem'));
    } // End Method  


    public function OrderDetails($id){
        $order = Order::with(['division','district','state','User'])->findOrFail($id);
        $orderItem = OrderItem::with(['order','product','user'])->where(
            [
                'order_id'=>$id,
            ])->get();
        return view('vendor.order.vendor_order_details',compact(['order','orderItem']));
    } // End Method 




   public function ReturnOrder()
    {
        $orderitem = OrderItem::with('order')
        ->whereHas('order', function ($query) {
            $query->where('return_order', 1);
        })
        ->where('vendor_id', Auth::user()->id) // Assuming the vendor is authenticated
        ->orderBy('id', 'DESC')
        ->get();
    

  return view('vendor.order.return_order',compact('orderitem'));
    }


    public function CompleteReturn()
   {

        $orderitem = OrderItem::with('order')
        ->whereHas('order', function ($query) {
            $query->where('return_order', 2);
        })
        ->where('vendor_id', Auth::user()->id) // Assuming the vendor is authenticated
        ->orderBy('id', 'DESC')
        ->get();

  return view('vendor.order.complete_return_order',compact('orderitem'));
    }



}
