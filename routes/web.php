<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\DenyAdminAccess;
use Illuminate\Support\Facades\Password;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Frontend\CompareController;
use App\Http\Controllers\Admin\SiteSettingController;

use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Frontend\CheckOutController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Admin\ShippingAreaController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\Vendor\VendorReviewController;
use App\Http\Controllers\Vendor\VendorProductController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Admin\AdminAuthenticatedController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Vendor\VendorAuthenticatedController;
use App\Http\Controllers\Frontend\StripeController;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';

// Route::get('/',function(){
//     return view('/index');
// })->middleware(['verified','admin.access'])->name('dashboard');

Route::get('/',function(){
    return view('/index');
})->middleware('admin.access');

/////////////////////////////////////////////////////////



/*
--------------------------------------------------------------------------------------------------------------------------
** Verify Email
--------------------------------------------------------------------------------------------------------------------------
*/

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

 //Submit Gmail form 
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

 //Resending Gmail Verification
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');





/*
------------------------------------------------------------------------------------------------------------------------
Reset Password 
------------------------------------------------------------------------------------------------------------------------
*/

Route::view('/forgot-password', 'auth.forgot-password')->middleware('guest')->name('password.request');

Route::post('/forgot-password',[PasswordResetLinkController::class,'passwordEmail'] )->middleware('guest')->name('password.email');
/*
Laravel recognisez the route name "password.reset" by default to build link for submitting gmail when sent to go to this URL here 
with tokens 
*/
Route::get('/reset-password/{token}', [NewPasswordController::class,'reset'])->middleware('guest')->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class,'passwordUpdate'])->middleware('guest')->name('password.update');






/*
---------------------------------------------------------------------------------------------------------------------
User Authentication                                                                                                  |
---------------------------------------------------------------------------------------------------------------------
*/

Route::get('/user/logout', [AuthenticatedSessionController::class, 'destroy'])
->name('logout');



/*
--------------------------------------------------------------------------------------------------------------------
Admin Authentication                                                                                                 |
--------------------------------------------------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function(){
 
    // Route::middleware('guest')->group(function(){
        
        //Admin Authentication
        Route::get('/login', [AdminAuthenticatedController::class, 'create'])->name('admin.login');

        Route::post('/', [AdminAuthenticatedController::class, 'store']);
    // });
       //Admin Authentication
    Route::middleware(['auth','role:admin'])->group(function(){

        // Admin Dashboard
        Route::get('/dashboard',[AdminController::class,'index'])->name('admin.dashboard');
        Route::get('/profile', [AdminController::class, 'AdminProfile']);
        Route::post('/profile/update', [AdminController::class, 'AdminProfileUpdate']);
        Route::get('/password/change', [AdminController::class, 'AdminChangePassword']);
        Route::post('/password/update', [AdminController::class, 'AdminUpdatePassword']);

        Route::get('/logout', [AdminAuthenticatedController::class, 'destroy'])
        ->name('admin.logout');

        //brand Routes
        Route::prefix('brand')->group(function(){
            Route::get('/all',[BrandController::class,'index'])->name('brand.index');
            Route::get('/add',[BrandController::class,'create'])->name('brand.create');
            Route::post('/store',[BrandController::class,'store'])->name('brand.store');
            Route::get('/edit/{id}',[BrandController::class,'edit'])->name('brand.edit');
            Route::post('/update/{id}',[BrandController::class,'update'])->name('brand.update');
            Route::get('/delete/{id}', [BrandController::class, 'destroy'])->name('brand.delete');
         });

  
        // Category Routes
        Route::prefix('category')->group(function () {
            Route::get('/all', [CategoryController::class, 'index'])->name('category.index');
            Route::get('/add', [CategoryController::class, 'create'])->name('category.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
            Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
            Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');
        });

        // SubCategory Routes
        Route::prefix('subcategory')->group(function () {
            Route::get('/all', [SubCategoryController::class, 'index'])->name('subcategory.index');
            Route::get('/add', [SubCategoryController::class, 'create'])->name('subcategory.create');
            Route::post('/store', [SubCategoryController::class, 'store'])->name('subcategory.store');
            Route::get('/edit/{id}', [SubCategoryController::class, 'edit'])->name('subcategory.edit');
            Route::post('/update/{id}', [SubCategoryController::class, 'update'])->name('subcategory.update');
            Route::get('/delete/{id}', [SubCategoryController::class, 'destroy'])->name('subcategory.delete');
        });

        // Product Routes
        Route::prefix('product')->group(function () {
            Route::get('/all', [ProductController::class, 'index'])->name('product.index');
            Route::get('/add', [ProductController::class, 'create'])->name('product.create');
            Route::post('/store', [ProductController::class, 'store'])->name('product.store');
            Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
            Route::post('/update/{id}', [ProductController::class, 'update'])->name('product.update');
            Route::get('/delete/{id}', [ProductController::class, 'destroy'])->name('product.delete');
            Route::get('/inactive/{id}', [ProductController::class, 'ProductInactive'])->name('product.inactive');
            Route::get('/active/{id}', [ProductController::class, 'ProductActive'])->name('product.active');
            // Route::post('/inactive/{id}', [ProductController::class, 'ProductInactive'])->name('product.inactive');
            Route::get('/multimg/edit/{id}', [ProductController::class, 'MultImgEdit'])->name('product.multimg.edit');
            Route::post('/multiimg/update', [ProductController::class, 'MultImgUpdate'])->name('product.multimg.update');
            Route::get('/multiimg/delete/{id}', [ProductController::class, 'MultImgDelete'])->name('product.multimg.delete');
            Route::post('/thumbnail/update/{id}', [ProductController::class, 'ThumbnailUpdate'])->name('product.thumbnail.update');
            Route::get('/details/{id}/{product_slug}',[ProductController::class,'ProductrDetails'])->name('product.details');
        });

            //Vendor Manage

        Route::prefix('vendor')->group(function()
            {
                Route::get('/active',[VendorController::class ,'ActiveVendor'])->name('ActiveVendor.all');
                Route::get('/inactive',[VendorController::class ,'InactiveVendor'])->name('InactiveVendor.all');
                Route::get('/active/details/{id}',[VendorController::class ,'activeVendorDetails'])->name('ActiveVendor.details');
                Route::get('/inactive/details/{id}',[VendorController::class ,'inactiveVendorDetails'])->name('InactiveVendor.details');
                Route::post('/active/approve/{id}',[VendorController::class ,'activeVendorApprove'])->name('ActiveVendor.approve');
                Route::post('/inactive/approve/{id}',[VendorController::class ,'inActiveVendorApprove'])->name('InactiveVendor.approve');

            });

            //User Manage
        Route::prefix('user-manage')->group(function(){
                //User Manage
            Route::get('/user/all',[UserController::class,'usersAllData'])->name('users.all');
            Route::get('/user/edit/{id}',[UserController::class,'userEdit'])->name('user.edit');
            Route::post('/user/update/{id}',[UserController::class,'userUpdate'])->name('user.update');
            Route::get('/user/delete/{id}',[UserController::class,'userDestroy'])->name('user.destroy');

                //Vendor Manage
            Route::get('/vendor/all',[UserController::class,'vendorsAllData'])->name('vendors.all');
            Route::get('/vendor/edit/{id}',[UserController::class,'vendorEdit'])->name('vendor.edit');
            Route::post('/vendor/update/{id}',[UserController::class,'vendorUpdate'])->name('vendor.update');
            Route::get('/vendor/delete/{id}',[UserController::class,'vendorDestroy'])->name('vendor.destroy');

    });
               //Admin Manage
        Route::prefix('admin-manage')->group(function(){
            Route::get('/all',[AdminController::class,'adminAllData'])->name('admins.all');
            Route::get('/add',[AdminController::class,'adminCreate'])->name('admins.create');
            Route::post('/store',[AdminController::class,'adminStore'])->name('admins.store');
            Route::get('/edit/{id}',[AdminController::class,'adminEdit'])->name('admins.edit');
            Route::post('/update/{id}',[AdminController::class,'adminUpdate'])->name('admins.update');
            Route::get('/delete/{id}',[AdminController::class,'adminDestroy'])->name('admins.destroy');
        });
        //Slider Manage
        Route::prefix('slider')->group(function(){
            Route::get('/all',[SliderController::class,'sliderAllData'])->name('sliders.all');
            Route::get('/add',[SliderController::class,'sliderCreate'])->name('slider.create');
            Route::post('/store',[SliderController::class,'sliderStore'])->name('slider.store');
            Route::get('/edit/{id}',[SliderController::class,'sliderEdit'])->name('slider.edit');
            Route::post('/update/{id}',[SliderController::class,'sliderUpdate'])->name('slider.update');
            Route::get('/delete/{id}',[SliderController::class,'sliderDestroy'])->name('slider.destroy');
    });
     //Banner Manage
     Route::prefix('banner')->group(function(){
        Route::get('/all',[BannerController::class,'bannerAllData'])->name('banners.all');
        Route::get('/add',[BannerController::class,'bannerCreate'])->name('banner.create');
        Route::post('/store',[BannerController::class,'bannerStore'])->name('banner.store');
        Route::get('/edit/{id}',[BannerController::class,'bannerEdit'])->name('banner.edit');
        Route::post('/update/{id}',[BannerController::class,'bannerUpdate'])->name('banner.update');
        Route::get('/delete/{id}',[BannerController::class,'bannerDestroy'])->name('banner.destroy');
});

     //Coupon Manage
     Route::prefix('coupon')->group(function(){
        Route::get('/all',[CouponController::class,'couponAllData'])->name('coupons.all');
        Route::get('/add',[CouponController::class,'couponCreate'])->name('coupon.create');
        Route::post('/store',[CouponController::class,'couponStore'])->name('coupon.store');
        Route::get('/edit/{id}',[CouponController::class,'couponEdit'])->name('coupon.edit');
        Route::post('/update/{id}',[CouponController::class,'couponUpdate'])->name('coupon.update');
        Route::get('/delete/{id}',[CouponController::class,'couponDestroy'])->name('coupon.destroy');
});

         //Blog Manage
         Route::prefix('blog')->group(function(){
            Route::get('/category/all',[BlogController::class,'categoryAllData'])->name('blog.category.all');
            Route::get('/category/add',[BlogController::class,'categoryCreate'])->name('blog.category.create');
            Route::post('/category/store',[BlogController::class,'categoryStore'])->name('blog.category.store');
            Route::get('/category/edit/{id}',[BlogController::class,'categoryEdit'])->name('blog.category.edit');
            Route::post('/category/update/{id}',[BlogController::class,'categoryUpdate'])->name('blog.category.update');
            Route::get('/category/delete/{id}',[BlogController::class,'categoryDestroy'])->name('blog.category.destroy');
            //Post Manage
            Route::get('/post/all',[BlogController::class,'postAllData'])->name('blog.post.all');
            Route::get('/post/add',[BlogController::class,'postCreate'])->name('blog.post.create');
            Route::post('/post/store',[BlogController::class,'postStore'])->name('blog.post.store');
            Route::get('/post/edit/{id}',[BlogController::class,'postEdit'])->name('blog.post.edit');
            Route::post('/post/update/{id}',[BlogController::class,'postUpdate'])->name('blog.post.update');
            Route::get('/post/delete/{id}',[BlogController::class,'postDestroy'])->name('blog.post.destroy');
    });

               //Review Manage
               Route::prefix('review')->group(function(){
                Route::get('/publish',[ReviewController::class,'reviewPublish'])->name('review.publish');
                Route::get('/pending',[ReviewController::class,'reviewPending'])->name('review.pending');
                Route::get('/pending/approve/{id}',[ReviewController::class,'reviewApprove'])->name('review.approve');
                Route::get('/publish/delete/{id}',[ReviewController::class,'reviewDestroy'])->name('review.destroy');
            });

            //Site Manage
            Route::prefix('setting')->group(function(){
            Route::get('/site',[SiteSettingController::class,'siteSetting'])->name('site.setting');
            Route::get('/seo',[SiteSettingController::class,'seoSetting'])->name('site.seo');
            Route::post('/site/update',[SiteSettingController::class,'siteUpdate'])->name('site.setting.update');
            Route::post('/seo/update',[SiteSettingController::class,'seoUpdate'])->name('seo.setting.update');
        });

            //Permission Manage
            Route::prefix('permission')->group(function(){
                Route::get('/all',[RoleController::class,'allPermission'])->name('permissions.all');
                Route::get('/add',[RoleController::class,'addPermission'])->name('permissions.add');
                Route::post('/store',[RoleController::class,'storePermission'])->name('permissions.store');
                Route::get('/edit/{id}',[RoleController::class,'editPermission'])->name('permissions.edit');
                Route::post('/update/{id}',[RoleController::class,'updatePermission'])->name('permissions.update');
                Route::get('/delete/{id}',[RoleController::class,'deletePermission'])->name('permissions.destroy');

            });
            //Role Manage
            Route::prefix('roles')->group(function(){
                Route::get('/all',[RoleController::class,'allRole'])->name('roles.all');
                Route::get('/add',[RoleController::class,'addRole'])->name('roles.add');
                Route::post('/store',[RoleController::class,'storeRole'])->name('roles.store');
                Route::get('/edit/{id}',[RoleController::class,'editRole'])->name('roles.edit');
                Route::post('/update/{id}',[RoleController::class,'updateRole'])->name('roles.update');
                Route::get('/delete/{id}',[RoleController::class,'deleteRole'])->name('roles.destroy');
            });

            //Role Permissions Manage
            Route::prefix('role/permissions')->group(function(){
                Route::get('/all',[RoleController::class,'allRolePermissions'])->name('role.permission.all');
                Route::get('/add',[RoleController::class,'addRolePermissions'])->name('role.permission.add');
                Route::post('/store',[RoleController::class,'storeRolePermissions'])->name('role.permission.store');
                Route::get('/edit/{id}',[RoleController::class,'editRolePermissions'])->name('role.permission.edit');
                Route::post('/update/{id}',[RoleController::class,'updateRolePermissions'])->name('role.permission.update');
                Route::get('/delete/{id}',[RoleController::class,'deleteRolePermissions'])->name('role.permission.destroy');
            });

            //Role Permissions Manage
            Route::prefix('ship')->group(function(){
            
                /**
                  * 
                  * DivisionShipping Area
                  *
                  */
                Route::prefix('division')->group(function(){
                Route::get('/all',[ShippingAreaController::class,'AllDivision'])->name('ship_division.all');
                Route::get('/add',[ShippingAreaController::class,'AddDivision'])->name('ship_division.add');
                Route::post('/store',[ShippingAreaController::class,'StoreDivision'])->name('ship_division.store');
                Route::get('/edit/{id}',[ShippingAreaController::class,'EditDivision'])->name('ship_division.edit');
                Route::post('/update',[ShippingAreaController::class,'UpdateDivision'])->name('ship_division.update');
                Route::get('/delete/{id}',[ShippingAreaController::class,'DeleteDivision'])->name('ship_division.destroy');
            });                
                 /**
                  * 
                  * DistrictShipping Area
                  *
                  */
                Route::prefix('district')->group(function(){
                    Route::get('/all',[ShippingAreaController::class,'AllDistrict'])->name('ship_district.all');
                    Route::get('/add',[ShippingAreaController::class,'AddDistrict'])->name('ship_district.add');
                    Route::post('/store',[ShippingAreaController::class,'StoreDistrict'])->name('ship_district.store');
                    Route::get('/edit/{id}',[ShippingAreaController::class,'EditDistrict'])->name('ship_district.edit');
                    Route::post('/update',[ShippingAreaController::class,'UpdateDistrict'])->name('ship_district.update');
                    Route::get('/delete/{id}',[ShippingAreaController::class,'DeleteDistrict'])->name('ship_district.destroy');
                });                 
                 /**
                  * 
                  * StateShipping Area
                  *
                  */
                Route::prefix('state')->group(function(){
                    Route::get('/all',[ShippingAreaController::class,'AllState'])->name('ship_state.all');
                    Route::get('/add',[ShippingAreaController::class,'AddState'])->name('ship_state.add');
                    Route::post('/store',[ShippingAreaController::class,'StoreState'])->name('ship_state.store');
                    Route::get('/edit/{id}',[ShippingAreaController::class,'EditState'])->name('ship_state.edit');
                    Route::post('/update',[ShippingAreaController::class,'UpdateState'])->name('ship_state.update');
                    Route::get('/delete/{id}',[ShippingAreaController::class,'DeleteState'])->name('ship_state.destroy');
                });
        });

        //End Shipping Area Routes 
           //Order Routes
        Route::prefix('order')->group(function(){
            Route::get('/pending',[OrderController::class,'PendingOrder'])->name('pending.order');
            Route::get('/confirmed',[OrderController::class,'ConfirmedOrder'])->name('confirmed.order');
            Route::get('/processing',[OrderController::class,'ProcessingOrder'])->name('processing.order');
            Route::get('/delivered',[OrderController::class,'DeliveredOrder'])->name('delivered.order');
            Route::get('/order-details/{id}',[OrderController::class,'OrderDetails'])->name('order.details');
            Route::get('/invoice/download/{id}',[OrderController::class,'InvoiceDownload'])->name('invoice.download');

            Route::get('/pending/confirm/{id}',[OrderController::class,'PendingToConfirm'])->name('order.confirm');
            Route::get('/confirm/processing/{id}',[OrderController::class,'ConfirmToProcessing'])->name('order.processing');
            Route::get('/processing/delivered/{id}',[OrderController::class,'ProcessingToDelivered'])->name('processing.delivered');

            //Stock Manage 
           Route::get('/product/stock', [OrderController::class, 'ProductStock'])->name('product.stock');

            //End Order Manage Routes
            
            //Return Request Routes
            Route::prefix('return')->group(function(){
                Route::get('/request',[RequestController::class,'ReturnRequest'])->name('order.return.request');
                Route::get('/complete',[RequestController::class,'CompleteReturn'])->name('order.complete.return');
                Route::get('/request/approve/{id}',[RequestController::class,'ApproveRequest'])->name('order.approve.return');
            
            });
              //End Return Request Routes

              // Report Routes
            Route::get('/report/view',[ReportController::class,'ReportView'])->name('order.view');
            Route::post('/by/date',[ReportController::class,'searchByDate'])->name('order.date');
            Route::post('/by/year',[ReportController::class,'searchByYear'])->name('order.year');
            Route::post('/by/month',[ReportController::class,'searchByMonth'])->name('order.month');
            Route::get('/by/user',[ReportController::class,'OrderByUser'])->name('order.by_user');
            Route::post('/search/by/user',[ReportController::class,'SearchByUser'])->name('order.user.show');
         
            // End Report Routes



         });
    

    });

    });



/*
-------------------------------------------------------------------------------------------------------------------------
Vendor Authentication
-------------------------------------------------------------------------------------------------------------------------
*/
Route::prefix('vendor')->group(function(){


    Route::middleware(['auth','admin.access','role:vendor'])->group(function(){
        //When  Authenticated User want to login as vendor too 
        // Route::get('/login', [VendorAuthenticatedController::class, 'create'])
        // ->name('vendor.login');
        
        // Route::post('/', [VendorAuthenticatedController::class, 'store']);

        Route::get('/dashboard' ,fn()=>view('vendor.index'));

        Route::get('/logout', [VendorAuthenticatedController::class, 'destroy'])
        ->name('vendor.logout');

        // vendor Dashboard
        Route::get('/dashboard',[VendorDashboardController::class,'index'])->name('vendor.dashboard');
        Route::get('/profile', [VendorDashboardController::class, 'VendorProfile']);
        Route::post('/profile/update', [VendorDashboardController::class, 'VendorProfileUpdate']);
        Route::get('/password/change', [VendorDashboardController::class, 'VendorChangePassword']);
        Route::post('/password/update', [VendorDashboardController::class, 'VendorUpdatePassword']);
        
        // Product Routes //add role :vendor midddleware
        Route::prefix('product')->group(function () {
            Route::get('/all', [VendorProductController::class, 'index'])->name('vendor.product.index');
            Route::get('/add', [VendorProductController::class, 'create'])->name('vendor.product.create');
            Route::post('/store', [VendorProductController::class, 'store'])->name('vendor.product.store');
            Route::get('/edit/{id}', [VendorProductController::class, 'edit'])->name('vendor.product.edit');
            Route::post('/update/{id}', [VendorProductController::class, 'update'])->name('vendor.product.update');
            Route::get('/delete/{id}', [VendorProductController::class, 'destroy'])->name('vendor.product.delete');
            Route::get('/inactive/{id}', [VendorProductController::class, 'ProductInactive'])->name('vendor.product.inactive');
            Route::get('/active/{id}', [VendorProductController::class, 'ProductActive'])->name('vendor.product.active');
            // Route::post('/inactive/{id}', [VendorProductController::class, 'ProductInactive'])->name('vendor.product.inactive');
            Route::get('/multimg/edit/{id}', [VendorProductController::class, 'MultImgEdit'])->name('vendor.product.multimg.edit');
            Route::post('/multiimg/update', [VendorProductController::class, 'MultImgUpdate'])->name('vendor.product.multimg.update');
            Route::get('/multiimg/delete/{id}', [VendorProductController::class, 'MultImgDelete'])->name('vendor.product.multimg.delete');
            Route::post('/thumbnail/update/{id}', [VendorProductController::class, 'ThumbnailUpdate'])->name('vendor.product.thumbnail.update');
        });


        Route::prefix('order')->group(function(){
            Route::get('/pending',[VendorOrderController::class,'PendingOrder'])->name('vendor.pending.order');
            Route::get('/details/{id}',[VendorOrderController::class,'OrderDetails'])->name('order.details');
            Route::get('/return',[VendorOrderController::class,'ReturnOrder'])->name('order.return');
            Route::get('/return/complete',[VendorOrderController::class,'CompleteReturn'])->name('order.complete.return');
        });
            //End Order Manage Routes

            Route::prefix('review')->group(function(){
                Route::get('/all',[VendorReviewController::class,'AllReview'])->name('vendor.review');
            });
    });

});

Route::get('/product/details/{id}',[VendorProductController::class,'ProductrDetails'])->name('product.details');





/*
-------------------------------------------------------------------------------------------------------------------------
Frontend
-------------------------------------------------------------------------------------------------------------------------
*/
 
  Route::middleware(['admin.access'])->group(function(){
       
     //Product

    Route::prefix('product')->group(function(){

        Route::get('category/{id}/{slug}',[HomeController::class,'CatWiseProduct']);
        Route::get('sub-category/{id}/{slug}',[HomeController::class,'SubCatWiseProduct']);
        
        Route::post('search' , [HomeController::class, 'ProductSearch'])->name('product.search');
        Route::post('/search-product', [HomeController::class, 'SearchProduct'])->name('search.product');
        
        Route::get('details/{id}/{slug}',[HomeController::class,'ProductDetails']);



    });

          //product-filter-subcategory
        Route::get('/products/subcat/low-to-high/{id}', [HomeController::class, 'FilterLowToHigh']);  //Ajax
        Route::get('/products/subcat/high-to-low/{id}', [HomeController::class, 'FilterHighToLow']);
        Route::get('/products/subcat/featured/{id}', [HomeController::class, 'Featured']);
        Route::post('/products/subcat/price-filter/{id}', [HomeController::class, 'PriceFilter']);
        Route::post('/products/subcat/brand-filter/{id}', [HomeController::class, 'BrandFilter']);
        //end

        //product-filter-category
        Route::get('/products/cat/low-to-high/{id}', [HomeController::class, 'CFilterLowToHigh']);  //Ajax
        Route::get('/products/cat/high-to-low/{id}', [HomeController::class, 'CFilterHighToLow']);
        Route::get('/products/cat/featured/{id}', [HomeController::class, 'CFeatured']);
        Route::post('/products/cat/price-filter/{id}', [HomeController::class, 'CPriceFilter']);
        Route::post('/products/cat/brand-filter/{id}', [HomeController::class, 'CBrandFilter']);
        

        //Blog
        Route::prefix('blog')->group(function(){
        Route::get('/posts' , [BlogController::class, 'AllBlog']);
        Route::get('/post/details/{id}/{slug}' , [BlogController::class, 'BlogDetails'] );  
        Route::get('/post/category/{id}/{slug}' , [BlogController::class, 'BlogPostCategory']); 
        });

        Route::prefix('/cart')->group(function(){
      
            //Product Cart
            Route::get( 'mycart', [CartController::class, 'myCart']);
            Route::get('product/get',[CartController::class, 'getCartContents'] );
            Route::get( 'remove/{rowId}', [CartController::class, 'removeCartItem']);
            Route::get('decrement/{rowId}' , [CartController::class, 'cartDecrement']);
            Route::get('increment/{rowId}', [CartController::class, 'cartIncrement']);
            //Cart Route
            Route::post('product/add', [CartController::class, 'addToCart'])->name('cart.add'); //Ajax Request
            Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add.cart');

            Route::get('mini/product', [CartController::class, 'viewMiniCart']); //Ajax Request
            Route::get('mini/product/remove/{rowId}', [CartController::class, 'removeFromMiniCart']); //Ajax Request

     });   

 
                
     //Show Vendor List in HomePage
     Route::prefix('vendor')->group(function(){

        Route::get("/details/{id}", [HomeController::class, 'VendorDetails']);
        Route::get("/all", [HomeController::class, 'AllVendor']);
    
    });


   


       //Vendor Authentication
        Route::get('/become/vendor', [VendorController::class, 'becomeVendor']);
        Route::post('/vendor/register', [VendorController::class, 'VendorRegister']);
         Route::get('/vendor/login', [VendorAuthenticatedController::class, 'create'])
        ->name('vendor.login');
        Route::post('/vendor/dashboard', [VendorAuthenticatedController::class, 'store']);
    

    Route::middleware(['auth','verified','role:user'])->group(function(){
     
        //Wishlist

        Route::prefix('/wishlist')->group(function(){
        Route::post('add/{product_id}', [WishlistController::class, 'AddToWishList']); //Ajax request
        Route::get('count', [WishlistController::class, 'getWishlistCount']);//Ajax request
        Route::get('view', [WishlistController::class, 'WishlistView']);
        Route::get("get/products", [WishlistController::class, 'GetWishlistProduct']);//Ajax request
        Route::get('remove/{id}' , [WishlistController::class, 'WishlistRemove']);//Ajax request


     });



    //coupon-apply
     Route::prefix('coupon')->group(function(){  
            //Apply Coupon
        Route::post('apply', [CartController::class, 'applyCoupon']);
        Route::get('calculation', [CartController::class, 'getCalculationCoupon']);  
        Route::get('remove', [CartController::class, 'removeCoupon']);
     
    });


        //checkout 
        Route::get('/checkout', [CartController::class, 'CheckoutCreate']);

        Route::get('/district-get/ajax/{division_id}' , [CheckOutController::class, 'DistrictGetAjax']);
        Route::get('/state-get/ajax/{district_id}' ,   [CheckOutController::class, 'StateGetAjax']);
        Route::post('/checkout/store' ,  [CheckOutController::class, 'CheckoutStore'] );
    
    //Stripe Payment
        Route::post('/stripe/order' , [StripeController::class, 'StripeOrder'] );
        Route::post('/cash/order' ,  [StripeController::class, 'CashOrder'] );

    
        //Compare Products

    Route::prefix('compare')->group(function(){  
     //Adding To Compare 
    Route::post('/add/{product_id}', [CompareController::class, 'addToCompare']); //Ajax request
        //compare product route  
    Route::get('/all', [CompareController::class, 'allCompare']);
    Route::get('/product/get' ,  [CompareController::class, 'getCompareProduct']);
    Route::get('remove/{id}', [CompareController::class, 'compareRemove']);

    });


    //Review
    Route::post('/product/review',[ReviewController::class,'reviewStore'])->name('review.store');



    //User Dashboard
  
         Route::prefix('user')->group(function(){

             //User Order

            Route::get('/dashboard', [UserController::class, 'Dashboard'])->name('dashboard');
            Route::get('/order', [UserController::class, 'orders'])->name('order');//orders page 
            Route::get('/order/view/details/{id}', [UserController::class, 'viewOrderDetail'])->name('orderViewDetail'); 
            Route::get('/order/invoice_download/{order_id}' , [UserController::class, 'downloadInvoice'] ); 

            // Order Tracking 
            Route::get('/track/order' ,[UserController::class, 'showTrackOrderForm'])->name('user.track.order');
            Route::post('/order/tracking' , [UserController::class, 'trackOrder'])->name('order.tracking');

            //Return Order
            Route::post('/return/order/{order_id}', [UserController::class, 'returnOrder'])->name('user.orders');
            Route::get('/return/order/page' ,  [UserController::class, 'returnOrderPage'])->name('return.order.page');

            //User Account 
            Route::get('/account/details', [UserController::class, 'accountDetails'])->name('accountDetails');
            Route::get('/password/settings', [UserController::class, 'passwordSettings'])->name('passwordSettings');
            Route::post('/update/password', [UserController::class, 'updatePassword']);
            Route::post('/update/{id}',[UserController::class,'userUpdate']);

        
         });
       

        });

    });


   /*
    -------------------------------------------------------------------------------------------------------------------------
    Localization
    -------------------------------------------------------------------------------------------------------------------------
    */

     
        Route::get('/lang/{locale}', function ($locale) {
           
            if (!in_array($locale, ['en', 'ar'])) {
                abort(400);
            }

            Session::put('locale', $locale);
            App::setLocale($locale);

            return Redirect::back();
        });


