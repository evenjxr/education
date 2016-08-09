<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;
use App\Models\Manage as MM;
use App\Models\Role as RM;


class Login extends Controller
{
    public function index()
    {
//        if (Session::get('admin.id')) {
//            return redirect('manage/admin/index');
//        }
        return view('login.login');
    }

    public function login()
    {
        $params = Input::all();
        $admin = MM::where('username', $params['username'])->where('password', md5($params['password']))->first();
        if ($admin) {
            Session::put('admin.id', $admin->id);
            Session::put('admin.username', $admin->username);
            return redirect('manage/admin/index');
        }
        return redirect('manage/login/index')->with('errors', '账号密码有误');
    }

    public function loginout()
    {
        Session::forget('admin.id');
        Session::forget('admin.role_id');
        Session::forget('admin.name');
        Session::forget('admin.city_id');
        Session::forget('admin.moudel_id');
        Session::forget('admin.moudel_name');
        return redirect('manage/login/index');
    }
}