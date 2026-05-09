<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    // 医生列表
    public function index(Request $request)
    {
        $department_id = $request->department_id;

        $query = Doctor::query();

        if ($department_id) {
            $query->where('department_id', $department_id);
        }

        $doctors = $query->get();

        return response()->json([
            'code' => 200,
            'message' => '获取医生成功',
            'data' => $doctors
        ]);
    }

    // 医生详情
    public function show($id)
    {
        $doctor = Doctor::find($id);

        return response()->json([
            'code' => 200,
            'data' => $doctor
        ]);
    }
}