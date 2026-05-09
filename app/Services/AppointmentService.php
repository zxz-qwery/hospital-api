<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Schedule;

class AppointmentService
{

    // 创建预约
    public function createAppointment($user_id,$doctor_id,$schedule_id)
    {

        $schedule = Schedule::find($schedule_id);

        if(!$schedule){
            return [
                'success'=>false,
                'message'=>'排班不存在'
            ];
        }

        $count = Appointment::where('schedule_id',$schedule_id)->count();

        if($count >= $schedule->max_number){
            return [
                'success'=>false,
                'message'=>'号源已满'
            ];
        }

        $appointment = Appointment::create([
            'user_id'=>$user_id,
            'doctor_id'=>$doctor_id,
            'schedule_id'=>$schedule_id,
            'status'=>'已预约'
        ]);

        return [
            'success'=>true,
            'data'=>$appointment
        ];
    }

}