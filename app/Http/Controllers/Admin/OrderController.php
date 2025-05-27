<?php

namespace App\Http\Controllers\Admin;

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



class OrderController extends Controller
{
   

    public function PendingOrder(){
        $orders = Order::where('status','pending')->orderBy('id','DESC')->get();
        return view('admin.order.pending_order',compact('orders'));
    } // End Method  

    public function ConfirmedOrder(){
        $orders = Order::where('status','confirm')->orderBy('id','DESC')->get();
        return view('admin.order.confirmed_order',compact('orders'));
    } // End Method 


 public function ProcessingOrder(){
        $orders = Order::where('status','processing')->orderBy('id','DESC')->get();
        return view('admin.order.processing_order',compact('orders'));
    } // End Method 


    public function DeliveredOrder(){
        $orders = Order::where('status','delivered')->orderBy('id','DESC')->get();
        return view('admin.order.delivered_order',compact('orders'));
    } // End Method 

    public function OrderDetails($id){
        $order = Order::with(['division','district','state','User'])->findOrFail($id);
        $orderItem = OrderItem::with(['order','product','user'])->where(
            [
                'order_id'=>$id,
            ])->get();
        return view('admin.order.admin_order_details',compact(['order','orderItem']));
    } // End Method 




    public function PendingToConfirm($order_id){

        Order::findOrFail($order_id)->update(['status' => 'confirm']);

        return redirect()->back()->with('success','Order Confirmed Successfully'); 


    }// End Method 

    
    public function ConfirmToProcessing($order_id){

        Order::findOrFail($order_id)->update(['status' => 'processing']);

        return redirect()->back()->with('success','Order Processed Successfully'); 


    }// End Method 
    public function ProcessingToDelivered($order_id){

        $product = OrderItem::where('order_id', $order_id)->get();

        foreach($product as $item){
             Product::where('id',$item->product_id)
                 ->update([
                    'product_quantity' => DB::raw('product_quantity-'.$item->qty) 
                 ]);
        }

        Order::findOrFail($order_id)->update(['status' => 'delivered']);

        return redirect()->back()->with('success','Order Delivered Successfully'); 


    }// End Method 


    public function InvoiceDownload($id){

        $order = Order::with(['division','district','state','User'])->findOrFail($id);
        $orderItem = OrderItem::with(['order','product','user'])->where(
            [
                'order_id'=>$id,
            ])->get();
             


            //Download Invoice PDF

            // Instantiate and configure Dompdf
            $options = new Options();
            $options->set('tempDir', public_path());
            $options->set('chroot', public_path());


            // instantiate and use the dompdf class
            $dompdf = new Dompdf($options);
            // Load the Blade view and render it as HTML
            $html=view('admin.order.admin_order_invoice',compact(['order','orderItem']))->render();
            $dompdf->loadHtml($html);
            
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'landscape');
            
            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser
            return $dompdf->stream('invoice-' . $id . '.pdf');



    }// End Method 


    public function ProductStock(){

        $products = Product::latest()->get();
        return view('admin.product.product_stock',compact('products'));

    }// End Method 

}
