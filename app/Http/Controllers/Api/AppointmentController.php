<?php

use App\Services\AppointmentService;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Schedule;

class AppointmentController extends Controller
{

// 预约详情
public function detail($id)
{
    $appointment = Appointment::with(['doctor','schedule'])
        ->find($id);

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

    // 创建预约
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
   
       $appointment = Appointment::create([
           'user_id' => $user_id,
           'doctor_id' => $doctor_id,
           'schedule_id' => $schedule_id,
           'status' => '已预约'
       ]);
   
       return response()->json([
           'code' => 200,
           'message' => '预约成功',
           'data' => $appointment
       ]);
   }

    // 我的预约
    public function myAppointments(Request $request)
    {
        $user_id = $request->user_id;

      $appointments = Appointment::with(['doctor','schedule'])
            ->where('user_id',$user_id)
            ->get();

        return response()->json([
            'code' => 200,
            'data' => $appointments
        ]);
    }

    // 取消预约
    public function cancel($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'code' => 404,
                'message' => '预约不存在'
            ]);
        }

        $appointment->status = '已取消';
        $appointment->save();

        return response()->json([
            'code' => 200,
            'message' => '预约已取消'
        ]);
    }

}