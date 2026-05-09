<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    // 用户列表 + 搜索 + 分页
  public function index(Request $request)
{
    $query = User::query();

    if ($request->username) {
        $query->where('username','like','%'.$request->username.'%');
    }

    if ($request->phone) {
        $query->where('phone','like','%'.$request->phone.'%');
    }

    if ($request->real_name) {
        $query->where('real_name','like','%'.$request->real_name.'%');
    }

    // 获取分页参数
    $pageSize = $request->pageSize ?? 10;

    $users = $query->orderBy('id','desc')->paginate($pageSize);

    return response()->json([
        'code'=>200,
        'data'=>[
            'list'=>$users->items(),
            'total'=>$users->total()
        ]
    ]);
}

    // 用户详情
    public function show($id)
    {
        $user = User::find($id);

        if(!$user){
            return response()->json([
                'code'=>404,
                'message'=>'用户不存在'
            ]);
        }

        return response()->json([
            'code'=>200,
            'data'=>$user
        ]);
    }

    // 修改用户
    public function update(Request $request,$id)
    {
        $user = User::find($id);

        if(!$user){
            return response()->json([
                'code'=>404,
                'message'=>'用户不存在'
            ]);
        }

        $user->username = $request->username;
        $user->real_name = $request->real_name;
        $user->phone = $request->phone;
        $user->gender = $request->gender;

        $user->save();

        return response()->json([
            'code'=>200,
            'message'=>'修改成功',
            'data'=>$user
        ]);
    }

    // 删除用户
    public function destroy($id)
    {
        $user = User::find($id);

        if(!$user){
            return response()->json([
                'code'=>404,
                'message'=>'用户不存在'
            ]);
        }

        $user->delete();

        return response()->json([
            'code'=>200,
            'message'=>'删除成功'
        ]);
    }

    // 重置用户密码
    public function resetPassword(Request $request,$id)
    {
        $user = User::find($id);

        if(!$user){
            return response()->json([
                'code'=>404,
                'message'=>'用户不存在'
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'code'=>200,
            'message'=>'密码重置成功'
        ]);
    }

}