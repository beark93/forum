<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class AuthController extends Controller
{
    /**
     * Display login page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // post 리스트 view
        return view('post.loginform');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $userInfo = $request->only('id', 'pw');
        $userInfo['pw'] = md5($userInfo['pw']);

        // post 존재 확인
        $count = User::where($userInfo)->count();
        if($count <= 0) {
            $err = array(
                'result' => "err",
                'msg' => "ID 또는 비밀번호가 잘못되었습니다."
            );

            return json_encode($err);
        }

        // post 조회
        $user = User::where($userInfo)->first();

        session(['id' => $user->id, 'name' => $user->name, 'login' => "Y"]);

        $err = array(
            'result' => "success",
            'msg' => "로그인이 완료되었습니다."
        );

        return json_encode($err);
    }

    /**
     * Logout
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function logout(Request $request)
    {
        $request->session()->forget('id');
        $request->session()->forget('name');
        $request->session()->forget('login');

        $err = array(
            'result' => "success",
        );

        return json_encode($err);
    }
}
