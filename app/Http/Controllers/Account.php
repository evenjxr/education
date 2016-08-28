<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Input;
use Illuminate\Support\Facades\Cache;
use App\Extra\SMS;

use App\Models\Manage as ManageM;
use App\Models\Teacher as TeacherM;
use App\Models\Student as StudentM;
use App\Models\Institution as InstitutionM;
use App\Models\LoginToken as LTM;
use App\Models\InviteRecord as InviteRecordM;
use App\Models\InviteCode as InviteCodeM;


class Account extends Controller
{
    public function login(Request $request)
    {
        //验证参数
        $this->validateLogin($request);
        $param = Input::all();
        switch ($param['type']) {
            case 'teacher':
                $model = new TeacherM;
                break;
            case 'manage':
                $model = new ManageM;
                break;
            case 'institution':
                $model = new InstitutionM;
                break;
            default:
                $model = new StudentM;
                break;
        }
        $user = $model->where('mobile', $param['mobile'])->where('password', md5($param['password']))->first();
        if ($user) {
            $token = LTM::makeToken();
            LTM::saveToken($user, $param['type'], $token);
            $data = [
                'token' => $token,
                'user' => $user,
                'type' => $param['type']
            ];
            return response()->json(['success' => 'Y', 'msg' => '登陆成功', 'data' => $data]);
        } else {
            return response()->json(['success' => 'N', 'msg' => '登陆失败, 密码输入有误']);
        }
    }


    public function regist(Request $request)
    {
        //验证参数
        $this->validateRegist($request);
        $param = Input::all();
        switch ($param['type']) {
            case 'teacher':
                $model = new TeacherM;
                break;
            case 'manage':
                $model = new ManageM;
                break;
            case 'institution':
                $model = new InstitutionM;
                break;
            default:
                $model = new StudentM;
                break;
        }

        $user = $model->firstOrCreate(
            [
                'mobile' => $param['mobile'],
                'password' => md5($param['password'])
            ]);

        if ($user) {
            $this->insertInviteCode($user,$param['type']);
            $token = LTM::makeToken();
            LTM::saveToken($user, $param['type'], $token);
            $data = [
                'token' => $token,
                'user' => $user,
                'type' => $param['type']
            ];
            if (!empty($param['invitecode'])) {
                $this->insertInvite($user, $param['type'], $param['invitecode']);
            }
            return response()->json(['success' => 'Y', 'msg' => '注册成功', 'data' => $data]);
        } else {
            return response()->json(['success' => 'N', 'msg' => '注册失败']);
        }
    }

    private function insertInviteCode($user,$type)
    {
        $code = $invite_code = $this->make_coupon_card();
        InviteCodeM::forceCreate([
            'code' => $code,
            'type' => $type,
            'user_id' => $user->id
        ]);
        return;
    }

    private function insertInvite($user,$type,$code)
    {
        InviteRecordM ::firstOrCreate([
            'user_id'=>$user->id,
            'type'=>$type,
            'code'=>$code]);
        return;
    }

    public  function updatePassword(Request $request)
    {
        $this->userInfo($request);
        $password = Input::get('password');
        $this->userInfo->update(['password'=>md5($password)]);
        return response()->json(['success' => 'Y','msg' => '修改成功']);
    }

    public  function findPassword(Request $request)
    {
        $this->validatePassword($request);
        $param = Input::all();
        $one = TeacherM::where('mobile',$param['mobile'])->first();
        if(!$one) {
            $one = StudentM::where('mobile',$param['mobile'])->first();
        }
        if(!$one) {
            $one = InstitutionM::where('mobile',$param['mobile'])->first();
        }
        if(!$one) {
            $one = ManageM::where('mobile',$param['mobile'])->first();
        }
        if ($one) {
            $one->update(['password'=>md5($param['password'])]);
            return response()->json(['success' => 'Y','msg' => '修改成功']);
        } else {
            return response()->json(['success' => 'N','msg' => '修改失败请联系管理员']); 
        }
    }

    public function detail(Request $request)
    {
        $this->userInfo($request);
        if (isset($this->userInfo->state)) {
            $this->userInfo->state = unserialize($this->userInfo->state);
        }
        return response()->json(['success'=>'Y','msg' => '', 'data'=>$this->userInfo]);
    }

    public function update(Request $request)
    {
        $this->userInfo($request);
        $param = Input::all();
        if (isset($param['state'])) {
            $param['state'] = serialize($param['state']);
        }
        $this->userInfo->update($param);
        return response()->json(['success'=>'Y','msg' => '修改成功', 'data'=>$this->userInfo]);
    }

    public function sms(Request $request)
    {
        $this->validateMobile($request);
        $code = $this->makeAuthSMS($request['mobile']);
        return response()->json(['success'=>'Y','code' => $code]);
    }

    private function makeAuthSMS($mobile)
    {
        $code = rand(100000, 999999);
        $expiresAt10 = Carbon::now()->addMinutes(10);
        $expiresAt1 = Carbon::now()->addMinutes(1);
        Cache::put($mobile . LTM::SMS_TYPE_REGIST, $code, $expiresAt10);
        Cache::put($mobile . LTM::SMS_TYPE_REGIST_RESEND, 1, $expiresAt1);
        SMS::send(LTM::SMS_REGISTER, $mobile, $code);
        return $code;
    }


    public function make_coupon_card()
    {
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = $code[rand(0,25)]
            .strtoupper(dechex(date('m')))
            .date('d').substr(time(),-5)
            .substr(microtime(),2,5)
            .sprintf('%02d',rand(0,99));
        for(
            $a = md5( $rand, true ),
            $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
            $d = '',
            $f = 0;
            $f < 8;
            $g = ord( $a[ $f ] ),
            $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
            $f++
        );
        return $d;
    }

    private function validatePassword($request)
    {
        Validator::extend('check_sms_code', 'App\Validators\Regist@checkSMSCode');
        $this->validate($request, [
            'password' => 'required|between:4,10',
            'smscode' => 'required|digits:6|check_sms_code',
        ], [
            'password.required' => '密码不得为空',
            'password.between' => '密码必须在4到10之间',
            'smscode.required' => '验证码不得为空',
            'smscode.check_sms_code' => '验证码不正确'
        ]);
    }

    private function validateLogin($request)
    {
        $this->validate($request, [
            'mobile' => 'required|digits:11',
            'type' => 'required|in:teacher,student,manage,institution',
            'password' => 'required|between:4,10',
        ], [
            'mobile.required' => '请填写您的手机号。',
            'mobile.digits' => '请输入一个正确的手机号。',
            'type.required' => '登录类型不得为空',
            'type.in' => '登录类型不正确',
            'password.required' => '密码不得为空',
            'password.between' => '登录密码在4到10位之间',
        ]);
    }

    private function validateRegist($request)
    {
        Validator::extend('check_sms_code', 'App\Validators\Regist@checkSMSCode');
        $this->validate($request, [
            'type' => 'required|in:teacher,student,manage,institution'
        ],[
            'type.required' => '登录类型不得为空',
            'type.in' => '登录类型不正确',
        ]);

        $this->validate($request, [
            'mobile' => 'required|digits:11|unique:'.$request->all()['type'].'s,mobile',
            'password' => 'required|between:4,10',
            'smscode' => 'required|check_sms_code',
        ], [
            'mobile.required' => '请填写您的手机号。',
            'mobile.digits' => '请输入一个正确的手机号。',
            'mobile.unique' => '手机号已经存在，直接登录',
            'password.required' => '密码不得为空',
            'password.between' => '登录密码在4到10位之间',
            'smscode.required' => '验证码必须为空',
            'smscode.check_sms_code' => '验证码不正确'
        ]);
    }

    private function validateMobile($request)
    {
        Validator::extend('whether_regist_sms_sent', 'App\Validators\Regist@whetherRegistSMSSent');
        $this->validate($request, [
            'mobile' => 'required|digits:11|whether_regist_sms_sent'
        ], [
            'mobile.required' => '请填写您的手机号。',
            'mobile.digits' => '请输入一个正确的手机号。',
            'mobile.whether_regist_sms_sent' => '短信验证码已发送！',
        ]);
    }



}