<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{

    // 添加评价
    public function store(Request $request)
    {
        $review = Review::create([
            'user_id'=>$request->user_id,
            'doctor_id'=>$request->doctor_id,
            'rating'=>$request->rating,
            'content'=>$request->content
        ]);

        return response()->json([
            'code'=>200,
            'message'=>'评价成功',
            'data'=>$review
        ]);
    }

    // 医生评价列表
    public function doctorReviews($doctor_id)
    {
        $reviews = Review::where('doctor_id',$doctor_id)->get();

        return response()->json([
            'code'=>200,
            'data'=>$reviews
        ]);
    }

}