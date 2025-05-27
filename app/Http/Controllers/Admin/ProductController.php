<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\MultiImg;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'subcategory', 'brands'])->get(); 
        $categories = Category::with('subcategories')->get();
        $brands = Brand::all(); 
    
        return view('admin.product.all_product', compact('products', 'categories', 'brands'));
    }
    

    public function create() :View
    {
        $categories = Category::with('subCategories')->get();
        $brands = Brand::all();
        $multiImg=MultiImg::all();
        $activeVendor = User::where('role', 'vendor')->where('status', 'active')->get();
        return view('admin.product.add_product', compact(['categories', 'brands','multiImg','activeVendor']));
    }

public function store(Request $request ) : RedirectResponse
    {
 $products = Product::with('category')->get();

 $validator = Validator::make($request->all(), 
    [
        'subcategory_id' => 'nullable|exists:categories,id', // Ensure subcategory exists in categories
        'category_id' => 'nullable|exists:categories,id',    // Ensure category exists in categories
        'vendor_id' => 'required|exists:users,id',             // Ensure user exists
        'product_name' => 'required|string|max:255',
        'product_code' => 'required|string|max:255|unique:products,product_code',
        'product_quantity' => 'required|numeric|min:0|max:99999999',
        'product_tags' => 'nullable|string|max:255',
        'product_size' => 'nullable|string|max:255',
        'product_color' => 'nullable|string|max:255',
        'selling_price' => 'required|numeric|min:0|max:99999999.99',
        'discount_price' => 'nullable|numeric|min:0|max:99999999.99',
        'short_descp' => 'required|string',
        'long_descp' => 'required|string',
        'product_thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Image validation
        'hot_deals' => 'nullable|in:0,1',
        'featured' => 'nullable|in:0,1',
        'special_offer' => 'nullable|in:0,1',
        'special_deals' => 'nullable|in:0,1',
        'status' => 'nullable|in:0,1', // Only allow 0 or 1
        'brand_ids' => 'nullable|array', // Ensure brands array is passed
        'brands.ids.*' => 'exists:brands,id', // Ensure each brand exists
        'multi_img'=>'array',
        'multi_img.*'=>'image|mimes:jpg,jpeg,png,gif|max:2048',
    ]
    );


        // Create product
        $product = Product::create(array_merge(
            $request->except('brands', 'product_thumbnail', 'multi_img'),
            ['user_id'=>$request->user_id]
        ));
        

    if ($request->hasFile('product_thumbnail')) {
        $fileName = time() . '.' . $request->product_thumbnail->extension();
        $request->product_thumbnail->move(public_path('uploads/products/'), $fileName);
        $product->product_thumbnail = 'uploads/products/' . $fileName;
    }

    $product->save();
    // Debug before saving
// dd($request->brands);

       // Attach Product to Selected Brands
       if ($request->has('brands')) {
        $product->brands()->sync($request->brands);
    }
    $product->save();

     // Debug to check if data was saved
    //  dd($product->brands()->get());
    
          
    // Store multi images 

 // Store multi images 
if ($request->hasFile('multi_img')) {
    $multiImgs = $request->file('multi_img');  // Use lowercase 'file'
    foreach ($multiImgs as $img) {
        // Generate a unique filename for each image
        $fileName = time() . '_' . uniqid() . '.' . $img->extension(); //put uniq id for each image in multiple if come together in the same time
        
        // Move the file to the designated directory
        $img->move(public_path('uploads/products/multiimgs/'), $fileName);
        
        // Construct the image path
        $img_path = 'uploads/products/multiimgs/' . $fileName;
        
        // Create a new record for the uploaded image
        MultiImg::create([
            'image'      => $img_path,
            'product_id' => $product->id,
        ]);
    }
}


    return redirect()->route('product.index')->with('success', 'Product created successfully');
    }



public function edit($id): View
{
    $product = Product::with(['category', 'brands'])->findOrFail($id);
    $multiImg= MultiImg::where('product_id',$id)->get();
    $categories = Category::with('subcategories')->get();// Fetch main categories with subcategories
    $brands = Brand::all(); // Fetch all brands
    $activeVendor = User::where('role', 'vendor')->where('status', 'active')->get();
    // dd($product); 
    return view('admin.product.edit_product', compact(['product', 'categories', 'brands','multiImg','activeVendor']));
}

 
 
 
 
 
    public function update(Request $request, $id): RedirectResponse
    {
        $product = Product::with('brands')->findOrFail($id);

        // dd($request->all());

        $validatedData = $request->validate([
            'subcategory_id'  => 'nullable|exists:categories,id',
            'category_id'     => 'nullable|exists:categories,id',
            'user_id'         => 'required|exists:users,id',
            'product_name'    => 'required|string|max:255',
            // 'product_slug'    => 'required|string|max:255|unique:products,product_slug,'.$id,
            'product_code'    => 'required|string|max:255|unique:products,product_code,'.$id,
            'product_quantity'=> 'required|numeric|min:0|max:99999999',
            'product_tags'    => 'nullable|string|max:255',
            'product_size'    => 'nullable|string|max:255',
            'product_color'   => 'nullable|string|max:255',
            'selling_price'   => 'required|numeric|min:0|max:99999999.99',
            'discount_price'  => 'nullable|numeric|min:0|max:99999999.99',
            'short_descp'     => 'required|string',
            'long_descp'      => 'required|string',
            // Uncomment and adjust if you want to update the thumbnail:
            // 'product_thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'hot_deals'       => 'nullable|in:0,1',
            'featured'        => 'nullable|in:0,1',
            'special_offer'   => 'nullable|in:0,1',
            'special_deals'   => 'nullable|in:0,1',
            'status'          => 'nullable|in:0,1',
            'brands' => 'nullable|array',
            'brands.*' => 'nullable|exists:brands,id',

            // 'multi_img'        => 'array',
            // 'multi_img.*'      => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // dd($request->input('brands'));  // Check if this gives you the correct array of brand IDs

     
            // Update the product with validated data.
    // (Make sure your Product model has fillable fields for the updated attributes.)
    $product->update(Arr::except($validatedData, ['brands']));
   


    // Optionally, update brand relationships if provided.
    if ($request->filled('brands')) {
        $product->brands()->sync($request->brands);
    }
  

        return redirect()->route('product.index')->with('success', 'Product updated successfully');
    }


//Update Product Thumbnail
public function ThumbnailUpdate(Request $request, $id): RedirectResponse
{

   $product = Product::findOrFail($id);


   if ($request->hasFile('product_thumbnail')) {
    $fileName = time() . '.' . $request->product_thumbnail->extension();
    $request->product_thumbnail->move(public_path('uploads/products/'), $fileName);
    $product->product_thumbnail = 'uploads/products/' . $fileName;
    $product->save();
}


return redirect()->route('product.index')->with('success', 'Product updated successfully');
}


//Edit Multi Images

// public function MultImgEdit($id): View
// {
//     $multiImg= MultiImg::where('product_id',$id)->get();

//     return view('admin.product.edit_product', compact('multiImg'));
// }




            //Update Multi Images

public function MultImgUpdate(Request $request){

            if ($request->hasFile('multi_img')) {

                $multiImgs=$request->File('multi_img');
               
    
                foreach( $multiImgs as $id=> $img){ 
                    $oldImg=MultiImg::findOrFail($id);
                    if(file_exists(public_path($oldImg->image)))
                    {
                        unlink(public_path($oldImg->image));
                    }
                 
               
             
                $fileName = time() . '.' . $img->extension();
                $img->move(public_path('uploads/products/multiimgs'), $fileName);
                $img_path = 'uploads/products/multiimgs/' . $fileName; 
                $oldImg->update(
                    [
                        'image'=>$img_path,
                    ]
                ); 
            }
            
            }
        return redirect()->back()->with('success' ,'Images updated successfully');
    }


    public function MultImgDelete($id ){

                $Img=MultiImg::findOrFail($id);
                if(file_exists(public_path($Img->image)))
                {
                    unlink(public_path($Img->image));
                }
                $Img->delete(); 
                
                return redirect()->back()->with('success' ,'Image deleted successfully');
            }
        
  



    //Inactive Products

    public function ProductInactive($id)
    {
$product =Product::findOrFail($id)->update(['status'=>0]);
return redirect()->back()->with('success','Product inactive successfully');
    }
 //Active Product
    public function ProductActive($id)
    {
$product =Product::findOrFail($id)->update(['status'=>1]);
return redirect()->back()->with('success','Product active successfully');
    }
 //Product Delete
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->product_thumbnail && file_exists(public_path($product->product_thumbnail))) {
            unlink(public_path($product->product_thumbnail));
        }
        $product->delete();
        
        return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
    }


        public function ProductrDetails($id, $slug){
        $product = Product::findOrFail($id);
        $color = $product->product_color;
        $product_color = explode(',', $color);
        

        $size = $product->product_size;
        $product_size = explode(',', $size);
        $multiImage = MultiImg::where('product_id',$id)->get();
        $cat_id = $product->category_id;
        $relatedProduct = Product::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(4)->get();

        return view('frontend.product.product_details', compact('product','product_color', 'product_size','multiImage','relatedProduct'));
    }
}

