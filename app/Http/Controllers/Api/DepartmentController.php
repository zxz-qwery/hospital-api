<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // 科室列表
    public function index()
    {
        $departments = Department::all();

        return response()->json([
            'code' => 200,
            'message' => '获取科室成功',
            'data' => $departments
        ]);
    }

    // 科室详情
    public function show($id)
    {
        $department = Department::find($id);

        return response()->json([
            'code' => 200,
            'data' => $department
        ]);
    }
}