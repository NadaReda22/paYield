<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Brand;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Models\MultiImg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{


    public function VendorDetails($id){
        $vendor = User::findOrFail($id);
        $vendor_products = Product::where('user_id', $id)->orderBy('id', 'DESC')->get();
        return view('frontend.vendor.vendorDetails', compact('vendor_products','vendor'));

    }

    public function AllVendor(){
        $vendors = User::where('role', 'vendor')->where('status','active')->orderBy('id','DESC')->get();
        return view('Frontend.vendor.vendor_all', compact('vendors'));
    }  

  public function ProductDetails($id)
  {

    $product=Product::findOrFail($id);

    $category=$product->category_id;
    $relatedProduct=Product::where('category_id',$category)->where('id','!=',$id)->orderBy('id','DESC')->limit(4)->get();

    $color=$product->product_color;
    $product_color=explode(',',$color);

    
    $size=$product->product_size;
    $product_size=explode(',',$size);
   
    $multiImage=MultiImg::where('product_id',$id)->get();


    return view('frontend.product.product_details',compact('relatedProduct','product','product_color','product_size','multiImage'));
  }




    public function CatWiseProduct($id,$slug)
    {
        $products = Product::where('status',1)->where('category_id',$id)->orderBy('id','DESC')->get();
        $categories = Category::whereNull('parent_id')->orderBy('category_name','ASC')->get();
        $breadcat = Category::where('id',$id)->first();
        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();//??
        $brands = Brand::whereHas('products', function ($query) use ($id) {
            $query->where('status', 1)
                  ->where('category_id', $id);
        })->get();
        // dd($brands);
                return view('frontend.product.category_view',compact('products','categories', 'breadcat', 'newProduct', 'id','brands'));
    }


    public function SubCatWiseProduct($id,$slug)
    {
        $products = Product::where('status',1)->where('subcategory_id',$id)->orderBy('id','DESC')->get();
        $categories = Category::orderBy('category_name','ASC')->get();
        $breadsubcat = Category::with('subCategories','parent')->where('id',$id)->first();
        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();
        $brands = Brand::whereHas('products', function ($query) use ($id) {
            $query->where('status', 1)
                  ->where('subcategory_id', $id);
        })->get();       
     return view('frontend.product.subcategory_view',compact('products','categories', 'breadsubcat', 'newProduct','id', 'brands'));
    }


    public function ProductSearch(Request $request){

        $request->validate(['search' => "required"]);

        $item = $request->search;
        $categories = Category::orderBy('category_name','ASC')->get();
        $products = Product::where('product_name','LIKE',"%$item%")->get();
        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();
        return view('frontend.product.search',compact('products','item','categories','newProduct'));

    }// End Method 

    public function SearchProduct(Request $request)
{
        // Validate input
        $validated = $request->validate([
            'search' => 'required|string' // Ensure it is a string and has at least 3 characters
        ]);
    
        $item = $request->search;
    
        // Search products based on the search term
        $products = Product::where('product_name', 'LIKE', "%$item%")
            ->select('product_name', 'product_slug', 'product_thumbnail', 'selling_price', 'id')
            ->limit(6)
            ->get();
    
        // Return the partial view with search results (important for AJAX)
        return view('frontend.product.search_product', compact('products'))->render();
    
}


//For SubCategory

public function FilterLowToHigh($id, Request $request)
{
    $minPrice = $request->input('min_price', 0);
    $maxPrice = $request->input('max_price', PHP_INT_MAX);

    $products = Product::where('subcategory_id', $id)
        ->whereBetween('discount_price', [$minPrice, $maxPrice])
        ->orderBy('discount_price', 'asc')
        ->get();

    $formattedProducts = [];

    foreach ($products as $product) {
        $formattedProducts[] = [
            'product_name' => $product->product_name,
            'product_image' => $product->product_thumbnail,
            'product_category' => $product->category->category_name,
            'vendor_name' => $product->vendor->name,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'discount_percent' => 100 - round(($product->discount_price * 100) / $product->selling_price),
            'vendor_id' => $product->vendor_id,
        'average_rating' => $product->reviews->pluck('rating')->countBy()->sortDesc()->keys()->first() ?? 0,


        ];
    }

    return response()->json(['products' => $formattedProducts]);
}



public function FilterHighToLow($id, Request $request)
{
    $minPrice = $request->input('min_price', 0);
    $maxPrice = $request->input('max_price', PHP_INT_MAX);

    $products = Product::where('subcategory_id', $id)
        ->whereBetween('discount_price', [$minPrice, $maxPrice])
        ->orderBy('discount_price', 'desc')
        ->get();

    $formattedProducts = [];

    foreach ($products as $product) {
        $formattedProducts[] = [
            'product_name' => $product->product_name,
            'product_image' => $product->product_thumbnail,
            'product_category' => $product->category->category_name,
            'vendor_name' => $product->vendor->name,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'discount_percent' => 100 - round(($product->discount_price * 100) / $product->selling_price),
            'vendor_id' => $product->vendor_id,
            'average_rating' => $product->reviews->pluck('rating')->countBy()->sortDesc()->keys()->first() ?? 0,


        ];
    }

    return response()->json(['products' => $formattedProducts]);
}


//////////////////////////////////////////

public function Featured($id){
    $products = Product::where('subcategory_id', $id)->where('featured', 1)->orderBy('id', 'desc')->get();

    $count = Product::where('subcategory_id', $id)->where('featured', 1)->orderBy('id', 'desc')->count();

    //return response()->json(['products' => $products]);
    $formattedProducts = [];
    
    foreach ($products as $product) {
        $formattedProducts[] = [
            'product_name' => $product->product_name,
            'product_image' => $product->product_thumbnail,
            'product_category' => $product->category->category_name,
            'vendor_name' => $product->vendor->name,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'discount_percent' => 100 - round(($product->discount_price * 100) / $product->selling_price),
            'vendor_id' => $product->vendor_id,
            'total_product' => $count,
            'product_id' => $product->id,
           'average_rating' => $product->reviews->pluck('rating')->countBy()->sortDesc()->keys()->first() ?? 0,

        ];
    }

    return response()->json(['products' => $formattedProducts]);

}

public function PriceFilter(Request $request, $id){

    $minPrice = $request->input('minPrice');
    $maxPrice = $request->input('maxPrice');

    
    $products = Product::where('subcategory_id', $id)->whereBetween('discount_price', [$minPrice, $maxPrice])->get();

    $count = Product::where('subcategory_id', $id)->whereBetween('discount_price', [$minPrice, $maxPrice])->count();
    
    $formattedProducts = [];
    
    foreach ($products as $product) {
        $formattedProducts[] = [
            'product_name' => $product->product_name,
            'product_image' => $product->product_thumbnail,
            'product_category' => $product->category->category_name,
            'vendor_name' => $product->vendor->name,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'discount_percent' => 100 - round(($product->discount_price * 100) / $product->selling_price),
            'vendor_id' => $product->vendor_id,
            'total_product' => $count,
            'average_rating' => $product->reviews->pluck('rating')->countBy()->sortDesc()->keys()->first() ?? 0,

        ];
    }

    return response()->json(['products' => $formattedProducts]);
} 

public function BrandFilter(Request $request, $subcategoryId)
{
    try {
        $selectedBrands = $request->input('brand_ids', []);
        $minPrice = $request->input('min_price', 0);
        $maxPrice = $request->input('max_price', PHP_INT_MAX);

        // Fetch subcategory and confirm it's a child category
        $subcategory = Category::where('id', $subcategoryId)
            ->whereNotNull('parent_id')
            ->firstOrFail();

        Log::info("Subcategory found: {$subcategory->category_name}");

        // Fetch products under this subcategory
        $productsQuery = Product::with('brands', 'vendor', 'category')
            ->where('subcategory_id', $subcategoryId)
            ->whereBetween('discount_price', [$minPrice, $maxPrice]);

        // Apply brand filter
        if (!empty($selectedBrands)) {
            $productsQuery->whereHas('brands', function ($query) use ($selectedBrands) {
                $query->whereIn('brands.id', $selectedBrands);
            });
        }

        $products = $productsQuery->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found for the selected filters.'], 200);
        }

        // Count how many times each brand appears
        $brandCounts = [];
        foreach ($products as $product) {
            foreach ($product->brands as $brand) {
                $brandCounts[$brand->id] = isset($brandCounts[$brand->id]) ? $brandCounts[$brand->id] + 1 : 1;
            }
              // 👉 Your original calculation for each product:
                $product->average_rating = $product->reviews
                ->pluck('rating')
                ->countBy()
                ->sortDesc()
                ->keys()
                ->first() ?? 0;
        }

        Log::info('Fetched Products:', $products->toArray());
        Log::info('Brand Counts:', $brandCounts);

        return response()->json([
            'products' => $products,
            'brand_counts' => $brandCounts
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error in filterByBrand: ' . $e->getMessage());
        return response()->json(['error' => 'Internal server error'], 500);
    }
}



/////////////////////////////////

public function CBrandFilter(Request $request, $categoryId)
{
    try {
        $selectedBrands = $request->input('brand_ids', []);
        $minPrice = $request->input('min_price', 0); // Minimum price filter
        $maxPrice = $request->input('max_price', PHP_INT_MAX); // Maximum price filter
        
        // Find the category
        $category = Category::findOrFail($categoryId);

        // Initialize the products query
        $productsQuery = Product::with('brands', 'vendor', 'category')
            ->where('category_id', $categoryId)
            ->whereBetween('discount_price', [$minPrice, $maxPrice]); // Apply price range filter

        // Apply brand filter only if selected brands are provided
        if (!empty($selectedBrands)) {
            $productsQuery->whereHas('brands', function ($query) use ($selectedBrands) {
                $query->whereIn('brands.id', $selectedBrands);
            });
        }

        // Get the filtered products
        $products = $productsQuery->get();

        // If no products found, return a message
        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found for the selected filters.'], 200);
        }

        // Log fetched products and their brands for debugging
        Log::info('Fetched Products:', $products->toArray());

        // Initialize brand count array
        $brandCounts = [];

        // Count the brands from the filtered products
        foreach ($products as $product) {
            foreach ($product->brands as $brand) {
                // Count the brands
                $brandCounts[$brand->id] = isset($brandCounts[$brand->id]) ? $brandCounts[$brand->id] + 1 : 1;
            }
             // 👉 Your original calculation for each product:
                $product->average_rating = $product->reviews
                ->pluck('rating')
                ->countBy()
                ->sortDesc()
                ->keys()
                ->first() ?? 0;
        }

        // Log the brand counts for debugging
        Log::info('Brand Counts:', $brandCounts);

        // Return products along with brand counts
        return response()->json([
            'products' => $products,
            'brand_counts' => $brandCounts,
            'average_rating' => $product->reviews->pluck('rating')->countBy()->sortDesc()->keys()->first() ?? 0,

        ], 200);

    } catch (\Exception $e) {
        // Handle any exceptions
        return response()->json(['error' => 'Internal server error'], 500);
    }
}



//category 

public function CFilterLowToHigh($id, Request $request)
{
    $minPrice = $request->input('min_price', 0); // Minimum price filter
    $maxPrice = $request->input('max_price', PHP_INT_MAX); // Maximum price filter

    $products = Product::where('category_id', $id)
        ->whereBetween('discount_price', [$minPrice, $maxPrice]) // Price range filter
        ->orderBy('discount_price', 'asc')
        ->get();

    $formattedProducts = [];

    foreach ($products as $product) {
        $formattedProducts[] = [
            'product_name' => $product->product_name,
            'product_image' => $product->product_thumbnail,
            'product_category' => $product->category->category_name,
            'vendor_name' => $product->vendor->name,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'discount_percent' => 100 - round(($product->discount_price * 100) / $product->selling_price),
            'vendor_id' => $product->vendor_id,
            'average_rating' => $product->reviews->pluck('rating')->countBy()->sortDesc()->keys()->first() ?? 0,

        ];
    }

    return response()->json(['products' => $formattedProducts]);
}

public function CFilterHighToLow($id, Request $request)
{
    $minPrice = $request->input('min_price', 0); // Minimum price filter
    $maxPrice = $request->input('max_price', PHP_INT_MAX); // Maximum price filter

    $products = Product::where('category_id', $id)
        ->whereBetween('discount_price', [$minPrice, $maxPrice]) // Price range filter
        ->orderBy('discount_price', 'desc')
        ->get();

    $formattedProducts = [];

    foreach ($products as $product) {
        $formattedProducts[] = [
            'product_name' => $product->product_name,
            'product_image' => $product->product_thumbnail,
            'product_category' => $product->category->category_name,
            'vendor_name' => $product->vendor->name,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'discount_percent' => 100 - round(($product->discount_price * 100) / $product->selling_price),
            'vendor_id' => $product->vendor_id,
        'average_rating' => $product->reviews->pluck('rating')->countBy()->sortDesc()->keys()->first() ?? 0,

        ];
    }

    return response()->json(['products' => $formattedProducts]);
}


public function CPriceFilter(Request $request, $id){

    $minPrice = $request->input('minPrice');
    $maxPrice = $request->input('maxPrice');

    
    $products = Product::where('category_id', $id)->whereBetween('discount_price', [$minPrice, $maxPrice])->get();

    $count = Product::where('category_id', $id)->whereBetween('discount_price', [$minPrice, $maxPrice])->count();
    
    $formattedProducts = [];
    
    foreach ($products as $product) {
        $formattedProducts[] = [
            'product_name' => $product->product_name,
            'product_image' => $product->product_thumbnail,
            'product_category' => $product->category->category_name,
            'vendor_name' => $product->vendor->name,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'discount_percent' => 100 - round(($product->discount_price * 100) / $product->selling_price),
            'vendor_id' => $product->vendor_id,
            'total_product' => $count,
            'average_rating' => $product->reviews->pluck('rating')->countBy()->sortDesc()->keys()->first() ?? 0,

        ];
    }

    return response()->json(['products' => $formattedProducts]);

} 


public function CFeatured($id){
    $products = Product::where('category_id', $id)->where('featured', 1)->orderBy('id', 'desc')->get();

    $count = Product::where('category_id', $id)->where('featured', 1)->orderBy('id', 'desc')->count();

    //return response()->json(['products' => $products]);
    $formattedProducts = [];
    
    foreach ($products as $product) {
        $formattedProducts[] = [
            'product_name' => $product->product_name,
            'product_image' => $product->product_thumbnail,
            'product_category' => $product->category->category_name,
            'vendor_name' => $product->vendor->name,
            'selling_price' => $product->selling_price,
            'discount_price' => $product->discount_price,
            'discount_percent' => 100 - round(($product->discount_price * 100) / $product->selling_price),
            'vendor_id' => $product->vendor_id,
            'total_product' => $count,
            'product_id' => $product->id,
        'average_rating' => $product->reviews->pluck('rating')->countBy()->sortDesc()->keys()->first() ?? 0,

        ];
    }

    return response()->json(['products' => $formattedProducts]);

}  


}
