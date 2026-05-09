<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function statistics()
{
    // 今日预约
    $todayCount = Appointment::whereDate('created_at', Carbon::today())->count();

    // 总预约
    $totalCount = Appointment::count();

    // 已取消
    $cancelCount = Appointment::where('status', '已取消')->count();

    // 科室排行
    $departmentRank = DB::table('appointments as a')
        ->join('doctors as doc', 'a.doctor_id', '=', 'doc.id')
        ->join('departments as d', 'doc.department_id', '=', 'd.id')
        ->select('d.name as name', DB::raw('count(*) as total'))
        ->groupBy('d.id', 'd.name')
        ->orderByDesc('total')
        ->get();

    // 医生排行
    $doctorRank = DB::table('appointments as a')
        ->join('doctors as doc', 'a.doctor_id', '=', 'doc.id')
        ->select('doc.name as name', DB::raw('count(*) as total'))
        ->groupBy('doc.id', 'doc.name')
        ->orderByDesc('total')
        ->get();

    // 状态分布
    $statusData = DB::table('appointments')
        ->select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->get();

    return response()->json([
        'code' => 200,
        'data' => [
            'today_count' => $todayCount,
            'total_count' => $totalCount,
            'cancel_count' => $cancelCount,
            'department_rank' => $departmentRank,
            'doctor_rank' => $doctorRank,
            'status_data' => $statusData
        ]
    ]);
}
public function trend()
{
    // 最近7天（包含今天）
    $days = 7;

    $dates = [];
    $counts = [];

    for ($i = $days - 1; $i >= 0; $i--) {

        $date = now()->subDays($i)->format('Y-m-d');
        $label = now()->subDays($i)->format('m-d');

        $count = \App\Models\Appointment::whereDate('created_at', $date)->count();

        $dates[] = $label;
        $counts[] = $count;
    }

    return response()->json([
        'code' => 200,
        'data' => [
            'dates' => $dates,
            'counts' => $counts
        ]
    ]);
}
}