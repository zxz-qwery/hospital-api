<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{

public function store(Request $request)
{
    $user_id = $request->user_id;
    $doctor_id = $request->doctor_id;
    $schedule_id = $request->schedule_id;

    $schedule = Schedule::find($schedule_id);

    if (!$schedule) {
        return response()->json([
            'code' => 400,
            'message' => '排班不存在'
        ]);
    }

    // 统计已预约人数
    $count = Appointment::where('schedule_id', $schedule_id)->count();

    if ($count >= $schedule->max_number) {
        return response()->json([
            'code' => 400,
            'message' => '号源已满'
        ]);
    }

    // 创建预约
    $appointment = Appointment::create([
        'user_id' => $user_id,
        'doctor_id' => $doctor_id,
        'schedule_id' => $schedule_id,
        'status' => '已预约'
    ]);

    // ⭐ 新增：生成通知
    Notification::create([
        'user_id' => $user_id,
        'title' => '预约成功',
        'content' => '您已成功预约就诊时间：'.$schedule->schedule_date.' '.$schedule->time_period,
        'is_read' => 0
    ]);

    return response()->json([
        'code' => 200,
        'message' => '预约成功',
        'data' => $appointment
    ]);
}

    // 用户通知
    public function index(Request $request)
    {
        $list = Notification::where('user_id',$request->user_id)->get();

        return response()->json([
            'code'=>200,
            'data'=>$list
        ]);
    }

    // 已读
    public function read($id)
    {
        $notification = Notification::find($id);

        $notification->is_read = 1;
        $notification->save();

        return response()->json([
            'code'=>200,
            'message'=>'已读'
        ]);
    }

}