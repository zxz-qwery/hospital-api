<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    // 医生列表 + 分页 + 模糊搜索
    public function index(Request $request)
    {
        $query = Doctor::with('department'); // ⭐关联科室

        // 模糊搜索（姓名）
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // 按科室筛选
        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        $pageSize = $request->pageSize ?? 10;

        $list = $query->orderBy('id','desc')->paginate($pageSize);

        return response()->json([
            'code'=>200,
            'data'=>[
                'list'=>$list->items(),
                'total'=>$list->total()
            ]
        ]);
    }

    // 添加医生
    public function store(Request $request)
    {
        $doctor = Doctor::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'title' => $request->title,
            'avatar' => $request->avatar,
            'introduction' => $request->introduction,
            'status' => $request->status ?? 1
        ]);

        return response()->json([
            'code'=>200,
            'message'=>'添加成功',
            'data'=>$doctor
        ]);
    }

    // 修改医生
    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json([
                'code'=>404,
                'message'=>'医生不存在'
            ]);
        }

        $doctor->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'title' => $request->title,
            'avatar' => $request->avatar,
            'introduction' => $request->introduction,
            'status' => $request->status
        ]);

        return response()->json([
            'code'=>200,
            'message'=>'修改成功',
            'data'=>$doctor
        ]);
    }

    // 删除医生
    public function destroy($id)
    {
        $doctor = Doctor::find($id);

        if (!$doctor) {
            return response()->json([
                'code'=>404,
                'message'=>'医生不存在'
            ]);
        }

        $doctor->delete();

        return response()->json([
            'code'=>200,
            'message'=>'删除成功'
        ]);
    }
}