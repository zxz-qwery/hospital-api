<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    // 科室列表 + 分页 + 模糊搜索
    public function index(Request $request)
    {
        $query = Department::query();

        // 科室名称搜索
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // 分页参数
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

    // 添加科室
    public function store(Request $request)
    {
        $department = Department::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'code'=>200,
            'message'=>'添加成功',
            'data'=>$department
        ]);
    }

    // 删除科室
    public function destroy($id)
    {
        $department = Department::find($id);

        if(!$department){
            return response()->json([
                'code'=>404,
                'message'=>'科室不存在'
            ]);
        }

        $department->delete();

        return response()->json([
            'code'=>200,
            'message'=>'删除成功'
        ]);
    }

// 修改科室
public function update(Request $request, $id)
{
    $department = Department::find($id);

    if (!$department) {
        return response()->json([
            'code' => 404,
            'message' => '科室不存在'
        ]);
    }

    $department->name = $request->name;
    $department->description = $request->description;

    $department->save();

    return response()->json([
        'code' => 200,
        'message' => '修改成功',
        'data' => $department
    ]);
}
}