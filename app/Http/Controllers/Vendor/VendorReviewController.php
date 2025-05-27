<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class VendorReviewController extends Controller
{
    public function AllReview()
    {
        $review = Review::with(['product', 'user'])
        ->where('user_id', Auth::user()->id)
        ->orderBy('id', 'DESC')
        ->get();
    
        return view('vendor.review.approve_review',compact('review'));
    }

    public function reviewPublish()
    {
        $reviews=Review::with(['product','user'])->where('status',1)->orderBy('id','DESC')->get();

        return view('admin.review.publish_review',compact('reviews'));
    }


    public function reviewApprove($id)
    {
        $review=Review::findOrFail($id)->update(['status'=>1]);

        return redirect()->route('review.publish')->with('success','Review is published successfully');
    }

    public function reviewDestroy($id)
    {
        $review=Review::findOrFail($id);
        $review->delete();

        return redirect()->route('review.publish')->with('success','Review is deleted successfully');
    }
}
