<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    /**
     * 预约列表 + 分页 + 多条件筛选
     */
    public function index(Request $request)
    {
        $query = Appointment::with([
            'user',
            'doctor.department',
            'schedule'
        ]);

        // 用户ID筛选
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
// 用户姓名
if ($request->real_name) {
    $query->whereHas('user', function ($q) use ($request) {
        $q->where('real_name', 'like', '%' . $request->real_name . '%');
    });
}

// 医生姓名模糊查询
if ($request->name) {
    $query->whereHas('doctor', function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->name . '%');
    });
}


        // 医生筛选
        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // 科室筛选（通过医生）
        if ($request->department_id) {
            $query->whereHas('doctor', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // 状态筛选
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // 时间范围筛选
		if ($request->start_date && $request->end_date) {
    $query->whereBetween('created_at', [
        $request->start_date . ' 00:00:00',
        $request->end_date . ' 23:59:59'
    ]);
}

        // 分页
        $pageSize = $request->pageSize ?? 10;

        $list = $query->orderBy('id', 'desc')->paginate($pageSize);

        return response()->json([
            'code' => 200,
            'data' => [
                'list' => $list->items(),
                'total' => $list->total()
            ]
        ]);
    }

    /**
     * 预约详情
     */
    public function show($id)
    {
        $appointment = Appointment::with([
            'user',
            'doctor.department',
            'schedule'
        ])->find($id);

        if (!$appointment) {
            return response()->json([
                'code' => 404,
                'message' => '预约不存在'
            ]);
        }

        return response()->json([
            'code' => 200,
            'data' => $appointment
        ]);
    }

    /**
     * 修改预约状态
     */
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'code' => 404,
                'message' => '预约不存在'
            ]);
        }

        if (!$request->status) {
            return response()->json([
                'code' => 400,
                'message' => '状态不能为空'
            ]);
        }

        $appointment->status = $request->status;
        $appointment->save();

        return response()->json([
            'code' => 200,
            'message' => '状态更新成功'
        ]);
    }

    /**
     * 删除预约
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'code' => 404,
                'message' => '预约不存在'
            ]);
        }

        $appointment->delete();

        return response()->json([
            'code' => 200,
            'message' => '删除成功'
        ]);
    }
}