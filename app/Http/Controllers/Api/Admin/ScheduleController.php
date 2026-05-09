<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // 列表
    public function index(Request $request)
    {
        $query = Schedule::with('doctor');

        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        return response()->json([
            'code' => 200,
            'data' => $query->orderBy('schedule_date', 'desc')->paginate(10)
        ]);
    }

    // 新增排班
    public function store(Request $request)
    {
        $schedule = Schedule::create([
            'doctor_id' => $request->doctor_id,
            'schedule_date' => $request->schedule_date,
            'time_period' => $request->time_period,
            'max_number' => $request->max_number,
            'current_number' => 0
        ]);

        return response()->json(['code' => 200, 'message' => '创建成功']);
    }

    // 删除
    public function destroy($id)
    {
        Schedule::destroy($id);
        return response()->json(['code' => 200, 'message' => '删除成功']);
    }
public function update(Request $request, $id)
{
    $schedule = Schedule::find($id);

    if (!$schedule) {
        return response()->json([
            'code' => 404,
            'message' => '排班不存在'
        ]);
    }

    // ❗关键校验
    if ($request->max_number < $schedule->current_number) {
        return response()->json([
            'code' => 400,
            'message' => '号源不能小于已预约人数'
        ]);
    }

    $schedule->update([
        'time_period' => $request->time_period,
        'max_number' => $request->max_number
    ]);

    return response()->json([
        'code' => 200,
        'message' => '修改成功'
    ]);
}
}