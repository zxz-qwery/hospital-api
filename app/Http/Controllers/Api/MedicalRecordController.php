<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{

    // 我的就诊记录
    public function index(Request $request)
    {
        $records = MedicalRecord::where('user_id',$request->user_id)->get();

        return response()->json([
            'code'=>200,
            'data'=>$records
        ]);
    }

}