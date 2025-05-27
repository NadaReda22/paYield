<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;

class RequestController extends Controller
{
    public function ReturnRequest()
    {
  $orders=Order::where('return_order',1)->orderBy('id','DESC')->get();

  return view('admin.return_order.return_request',compact('orders'));
    }

    public function ApproveRequest($id)
    {
  $order=Order::findOrFail($id);
  $order->update(['return_order'=>2]);
   return redirect()->back()->with('success','Return Order Successfully');
    }


    public function CompleteReturn()
   {
  $orders=Order::where('return_order',2)->orderBy('id','DESC')->get();

  return view('admin.return_order.complete_return_request',compact('orders'));
    }
}
