<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    // 列表
    public function index(Request $request)
    {
        $query = Announcement::query();

        if ($request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        return response()->json([
            'code' => 200,
            'data' => $query->orderBy('id', 'desc')->paginate(10)
        ]);
    }

    // 新增
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json([
            'code' => 200,
            'message' => '添加成功'
        ]);
    }

    // 修改
    public function update(Request $request, $id)
    {
        $announcement = Announcement::find($id);

        if (!$announcement) {
            return response()->json([
                'code' => 404,
                'message' => '公告不存在'
            ]);
        }

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content
        ]);

        return response()->json([
            'code' => 200,
            'message' => '修改成功'
        ]);
    }

    // 删除
    public function destroy($id)
    {
        Announcement::destroy($id);

        return response()->json([
            'code' => 200,
            'message' => '删除成功'
        ]);
    }

    // 详情（可选）
    public function show($id)
    {
        $data = Announcement::find($id);

        return response()->json([
            'code' => 200,
            'data' => $data
        ]);
    }
}