<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller
{

    // 公告列表
    public function index()
    {
        $list = Announcement::orderBy('id','desc')->get();

        return response()->json([
            'code'=>200,
            'data'=>$list
        ]);
    }

    // 公告详情
    public function show($id)
    {
        $announcement = Announcement::find($id);

        return response()->json([
            'code'=>200,
            'data'=>$announcement
        ]);
    }

}