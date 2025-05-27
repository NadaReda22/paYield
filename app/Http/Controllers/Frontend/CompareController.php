<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\Compare;

class CompareController extends Controller
{

    // View all compare products

    public function allCompare()
    {
        return view('frontend.compare.view_compare');
    }


    // Add product to compare list
    public function addToCompare($product_id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to add to compare list.']);
        }

        $user = Auth::user();

        $exists = Compare::where('user_id', $user->id)
            ->where('product_id', $product_id)
            ->first();

        if ($exists) {
            return response()->json(['error' => 'Product already in compare list.']);
        }

        Compare::create([
            'user_id' => $user->id,
            'product_id' => $product_id,
        ]);

        return response()->json(['success' => 'Product added to compare list.']);
    }

  

   public function getCompareProduct()
{
    // Check if user is authenticated first
    if (!Auth::check()) {
        return response()->json([
            'authenticated' => false,
            'compare' => [],
            'compareQty' => 0,
        ]);
    }

    $compare = Compare::with('product')
        ->where('user_id', Auth::id())
        ->latest()
        ->get()
        ->filter(function ($item) {
            // Remove items where the product is null (e.g. deleted)
            return $item->product !== null;
        })
        ->values(); // Reindex the array

    return response()->json([
        'authenticated' => true,
        'compare' => $compare,
        'compareQty' => $compare->count(),
    ]);
}


    // Remove a product from compare list
    public function compareRemove($id)
    {
        $compare = Compare::find($id);

        if ($compare && $compare->user_id === Auth::id()) {
            $compare->delete();
            return response()->json(['success' => 'Product removed from compare list.']);
        }

        return response()->json(['error' => 'Unauthorized or not found.']);
    }
}
