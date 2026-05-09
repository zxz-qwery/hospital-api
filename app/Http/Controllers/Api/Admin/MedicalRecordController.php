<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{
    /**
     * 列表（分页 + 筛选）
     */
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['user', 'doctor']);

        // 用户筛选
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // 医生筛选
        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $list = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'code' => 200,
            'data' => $list
        ]);
    }

    /**
     * 新增就诊记录
     */
    public function store(Request $request)
    {
        try {
            // ✅ 参数校验（非常重要）
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'doctor_id' => 'required|exists:doctors,id',
                'diagnosis' => 'required|string',
                'treatment' => 'required|string',
            ], [
                'user_id.required' => '请选择用户',
                'doctor_id.required' => '请选择医生',
                'diagnosis.required' => '请填写诊断结果',
                'treatment.required' => '请填写治疗方案',
            ]);

            MedicalRecord::create([
                'user_id' => $request->user_id,
                'doctor_id' => $request->doctor_id,
                'diagnosis' => $request->diagnosis,
                'treatment' => $request->treatment,
            ]);

            return response()->json([
                'code' => 200,
                'message' => '添加成功'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => '添加失败',
                'error' => $e->getMessage() // 🔥 调试用
            ]);
        }
    }

    /**
     * 删除
     */
    public function destroy($id)
    {
        $record = MedicalRecord::find($id);

        if (!$record) {
            return response()->json([
                'code' => 404,
                'message' => '记录不存在'
            ]);
        }

        $record->delete();

        return response()->json([
            'code' => 200,
            'message' => '删除成功'
        ]);
    }

    /**
     * （可选）详情
     */
    public function show($id)
    {
        $record = MedicalRecord::with(['user', 'doctor'])->find($id);

        if (!$record) {
            return response()->json([
                'code' => 404,
                'message' => '记录不存在'
            ]);
        }

        return response()->json([
            'code' => 200,
            'data' => $record
        ]);
    }
}