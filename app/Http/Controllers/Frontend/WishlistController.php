<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class WishlistController extends Controller
{

    //Adding Products in wishlist template using AJAX

    public function AddToWishList(Request $request, $product_id){

        if(Auth::check()){
            $exists = Wishlist::where('user_id',Auth::id())->where('product_id',$product_id)->first();

            if(!$exists){
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'product_id' => $product_id,
                    'created_at' => Carbon::now(),
    
                ]);
                $wishlist_count = Wishlist::where('user_id', Auth::id())->count();

                // $product = Product::with('reviews')->findOrFail($product_id); // Load product with reviews

                return response()->json([
                    'success' => 'Successfully Added On Your Wishlist',
                    'count' => $wishlist_count,
            
                ]);
            }else{
                return response()->json(['error' => 'This Product Has Already on Your Wishlist' ]);
            }

        }else{
            return response()->json([
                "error"=> "At First Login Your Account"
            ]);
        }

    } 

//Showing Wishlist View Template
    public function WishlistView(){

        return view('frontend.product.product_wishlist');
    }


//Show Products in wishlist template using AJAX
  public function GetWishlistProduct()
{
    $wishlist = Wishlist::with('product')
        ->where('user_id', Auth::id())
        ->latest()
        ->get()
        ->filter(function ($item) {
            // Remove items where the product is null (e.g. deleted)
            return $item->product !== null;
        })
        ->values(); // Reindex the array

    $wishQty = $wishlist->count(); // Use filtered list count

    return response()->json([
        'wishlist' => $wishlist,
        'wishQty' => $wishQty
    ]);
}


//Show wishlist count when any updates happened using AJAX

    public function getWishlistCount()
{
    if (Auth::check()) {
        $count = Wishlist::where('user_id', Auth::id())->count();
        return response()->json(['count' => $count]);
    } else {
        return response()->json(['count' => 0]);
    }
}


    public function WishlistRemove($id){

     Wishlist::where('user_id',Auth::id())->where('id',$id)->delete();
     return response()->json(['success' => 'Successfully Product Remove' ]);
    }// End Method

   
}
