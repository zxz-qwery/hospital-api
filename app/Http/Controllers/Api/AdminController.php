<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    // 管理员登录
    public function login(Request $request)
    {

        $username = $request->username;
        $password = $request->password;

        $admin = Admin::where('username',$username)->first();

        if(!$admin){
            return response()->json([
                'code'=>401,
                'message'=>'管理员不存在'
            ]);
        }

        if(!Hash::check($password,$admin->password)){
            return response()->json([
                'code'=>401,
                'message'=>'密码错误'
            ]);
        }

        // 生成 token
        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'code'=>200,
            'message'=>'登录成功',
            'token'=>$token,
            'data'=>$admin
        ]);

    }
public function info(Request $request)
{
    return response()->json([
        'code'=>200,
        'data'=>$request->user()
    ]);
}

public function logout(Request $request)
{

    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'code'=>200,
        'message'=>'退出成功'
    ]);

}

}