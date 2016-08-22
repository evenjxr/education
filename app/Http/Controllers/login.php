<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Input;

use App\Models\Manage as ManageM;
use App\Models\Teacher as TeacherM;
use App\Models\Student as StudentM;
use App\Models\LoginToken as LTM;



class Login extends Controller
{
    public function index(Request $request)
    {
        //验证参数
        $v = $this->validateLogin($request);

        if ($v->fails())) {
            return response()->json(['success' => 'N','msg' => $v->errors()->toArray()]);
        } else {
            $param = Input::all();
            switch ($param['type']) {
                case 'teacher':
                    $model = new TeacherM();
                    break;
                case 'manage':
                    $model = new ManageM();
                    break;                    
                default:
                    $model = new StudentM();
                    break;
            }

            $user = $model->where('mobile',$param['mobile'])->where('password',$param['password'])->first();
            $token = LTM::makeToken();
            LTM::saveToken($user,$token);
            $data = [
                'token' => $token,
                'user' => $user 
            ];
            return response()->json(['success' => 'Y','msg' => '登陆成功','data' => $data]);
        }
    }


    private function validateLogin($request)
    {
        return  Validator::make($request->all(), [
            'mobile' => 'required|digits:11'
        ], [
            'mobile.required' => '请填写您的手机号。',
            'mobile.digits' => '请输入一个正确的手机号。',
            'mobile.whether_auth_sms_sent' => '短信验证码已发送！',
        ]);
    }




}