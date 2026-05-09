<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json([
                'code' => 400,
                'message' => '没有文件'
            ]);
        }

        $file = $request->file('file');

        // 生成文件名
        $filename = time() . '_' . $file->getClientOriginalName();

        // 存储到 public/uploads
        $path = $file->storeAs('uploads', $filename, 'public');

        return response()->json([
            'code' => 200,
            'message' => '上传成功',
            'data' => [
                'url' => '/storage/' . $path
            ]
        ]);
    }
}