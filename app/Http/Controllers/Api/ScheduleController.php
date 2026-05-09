<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // 排班列表
    public function index(Request $request)
    {
        $doctor_id = $request->doctor_id;

        $query = Schedule::query();

        if ($doctor_id) {
            $query->where('doctor_id', $doctor_id);
        }

        // 只查询今天以后的排班
        $query->whereDate('schedule_date', '>=', date('Y-m-d'));

        $schedules = $query->get();

        // 如果数据库没有排班，自动生成未来7天排班
        if ($schedules->isEmpty()) {

            $autoSchedules = [];

            for ($i = 0; $i < 7; $i++) {

                $date = date('Y-m-d', strtotime("+$i day"));

                // 上午
                $autoSchedules[] = [
                    'id' => $i * 2 + 1,
                    'doctor_id' => $doctor_id,
                    'schedule_date' => $date,
                    'time_period' => '上午',
                    'max_number' => 20,
                    'current_number' => rand(0, 10)
                ];

                // 下午
                $autoSchedules[] = [
                    'id' => $i * 2 + 2,
                    'doctor_id' => $doctor_id,
                    'schedule_date' => $date,
                    'time_period' => '下午',
                    'max_number' => 20,
                    'current_number' => rand(0, 10)
                ];
            }

            $schedules = $autoSchedules;
        }

        return response()->json([
            'code' => 200,
            'message' => '获取排班成功',
            'data' => $schedules
        ]);
    }
}