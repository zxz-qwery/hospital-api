<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

// 修改用户资料
public function updateProfile(Request $request)
{
    $user = $request->user(); // 当前登录用户

    $user->real_name = $request->real_name;
    $user->gender = $request->gender;
    $user->birthday = $request->birthday;
    $user->phone = $request->phone;

    $user->save();

    return response()->json([
        'code' => 200,
        'message' => '资料更新成功',
        'data' => $user
    ]);
}

// 修改密码
public function changePassword(Request $request)
{
    $user = $request->user();

    // 验证原密码
    if(!Hash::check($request->old_password, $user->password)){
        return response()->json([
            'code' => 400,
            'message' => '原密码错误'
        ]);
    }

    // 新密码不能和旧密码一样
    if(Hash::check($request->new_password, $user->password)){
        return response()->json([
            'code' => 400,
            'message' => '新密码不能和旧密码相同'
        ]);
    }

    // 更新密码
    $user->password = bcrypt($request->new_password);
    $user->save();

    return response()->json([
        'code' => 200,
        'message' => '密码修改成功'
    ]);
}
    // 注册
    public function register(Request $request)
    {
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'gender' => $request->gender
        ]);

        return response()->json([
            'code'=>200,
            'message'=>'注册成功',
            'data'=>$user
        ]);
    }

    // 登录
public function login(Request $request)
{
    $user = User::where('username',$request->username)->first();

    if(!$user){
        return response()->json([
            'code'=>404,
            'message'=>'用户不存在'
        ]);
    }

    if(!Hash::check($request->password,$user->password)){
        return response()->json([
            'code'=>400,
            'message'=>'密码错误'
        ]);
    }

    // 生成 token
    $token = $user->createToken('user_token')->plainTextToken;

    return response()->json([
        'code'=>200,
        'message'=>'登录成功',
        'token'=>$token,
        'data'=>$user
    ]);
}
	//退出登录
	public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'code'=>200,
        'message'=>'退出成功'
    ]);
}
}