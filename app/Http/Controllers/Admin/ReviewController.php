<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{  
    
    public function reviewPending()
    {
        $reviews=Review::with(['product','user'])->where('status',0)->orderBy('id','DESC')->get();

        return view('admin.review.pending_review',compact('reviews'));
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


    public function reviewStore(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'product_id'=>'required|exists:products,id',
            'comment'=>'required|string',
            'rating'=>'required|numeric|between:0,5.0',
        ]);

       // Create the review using validated data
    $review = Review::create([
        'user_id' => Auth::user()->id,  
        'product_id' => $request->product_id,
        'comment' => $request->comment,
        'rating' => $request->rating,
        'status' => 0,  // default status as 0 (pending)
    ]);

    // Save the review
    $review->save();


        $review->save();
        return redirect()->back()->with('success','Review is sent successfully');
    }
  
}
