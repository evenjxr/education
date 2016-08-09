<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Input;
use Cookie;
use App\Extra\SMS;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

use App\Models\User as UM;
use App\Models\LoginToken as LTM;
use App\Models\City as CM;


/**
 * author:jxr
 * create-date:2015-7-28
 * introduction: user login,user regist, user info ,user info modify
 */

class User extends Controller
{

    public function cityLists()
    {
        $data = CM::lists('name','id');
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

    public function setDefaultCity(Request $request)
    {
        $this->getUserInfo($request);
        UM::where('id',$this->user->id)->update(['city_id'=>$request->get('city_id')]);
        return response()->json(['success' => 'Y','msg' => '设置成功']);
    }
    
    /**
     *授权登录
     * @return Response
     */
    public function auth()
    {
        if ($_GET['code']) {
            return $this->login();
        } else {
            return response()->json(['success' => 'N','msg' => '登陆失败']);
        }
    }


    public function login()
    {
        $api = new Api();
        $userData  = $api->getUserData();
        if ($userData) {
            $data = [
                'openid' => $userData['openid'],
                'nickname' => $userData['nickname'],
                'sex' => $userData['sex'],
                'city' => $userData['city'],
                'avatar' => $userData['headimgurl']
            ];
            $user = UM::where('openid',$userData['openid'])->first();
            if (empty($user)) {
                $user = UM::firstOrCreate($data);
            }
            $token = LTM::makeToken();
            LTM::saveToken($user,$token);
            return response()->json(['success' => 'Y','msg' => '登陆成功','token' => $token]);
        } else {
            return response()->json(['success' => 'N','msg' => '登陆失败']);
        }
    }


    /**
     * @route v1/user/login/sms
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sms(Request $request)
    {
        $v = $this->validateMobile($request);
        if ($v->fails())
            return response()->json(['success' => 'N','msg' => $v->errors()->toArray()]);
        $flag = $this->validateHasSend($request->all()['mobile']);
        if ($flag)
            return response()->json(['success' => 'N','msg' => '短信已发送']);
        //Send the verification code
        $code = $this->makeAuthSMS($request['mobile']);

        return response()->json(['success'=>'Y','code' => $code]);
    }

    /**
     * 创建短信验证码，放入缓存，并记录cache
     * @param $mobile
     * @return int
     */
    private function makeAuthSMS($mobile)
    {
        $code = rand(100000, 999999);
        $expiresAt10 = Carbon::now()->addMinutes(10);
        $expiresAt1 = Carbon::now()->addMinutes(1);
        Cache::put($mobile . LTM::SMS_TYPE_LOGIN, $code, $expiresAt10);
        Cache::put($mobile . LTM::SMS_TYPE_LOGIN_RESEND, 1, $expiresAt1);
        SMS::send(LTM::SMS_AUTH_ID, $mobile, $code);
        return $code;
    }


    //用户的个人信息接口
    public function detail(Request $request)
    {
        $this->getUserInfo($request);
        $data = UM::where('id',$this->user->id)->get();
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

    //修改信息
    public function update(Request $request)
    {
        $data = $request->all();
        $this->getUserInfo($request);
        $v = $this->myValidate($request);
        if ($v->fails())
            return response()->json(['success' => 'N','msg' => $v->errors()->toArray()]);
        if(!$this->validateCode($data['mobile'],$data['code']))
            return response()->json(['success' => 'N','msg' => '验证码不正确']);
        unset($data['code']);
        if (isset($data['idcard']))
            $data['age'] = date('Y')-substr($data['idcard'],6,4);
        $this->user->update($data);
        return response()->json(['success' => 'Y','msg' => '修改成功','data'=>$data]);
    }

    public function uploadPic(Request $request)
    {
        $this->validate($request,
            [
                'avatar' => 'required'
            ],
            [
                'avatar.required' => '上传文件不得为空'
            ]
        );

        $url = Helper::uploadBinary($request['avatar']);
        if (empty($url)) {
            return response()->json(['msg' => '上传文件有误。']);
        }
        $user = Helper::getUserByToken($request);
        $user ->avatar = $url;
        if ($user->save()) {
            return response()->json(['msg' => 'success']);
        } else {
            return response()->json(['msg' => 'error']);
        }
    }
    
    /**
     * 私有验证
     * @param  [type]
     * @return [type]
     */
    private function myValidate($request)
    {
        return  Validator::make($request->all(), [
            'name' =>'required|between:2,10',
            'nickname' => 'between:2,10',
            'email' => 'email',
            'sex' =>'required|in:1,2',
            'mobile' => 'required|digits:11|unique:users,mobile,'.$request->get('mobile'),
            'idcard' => 'required|size:18|unique:users,idcard,'.$request->get('idcard'),
            'height' => 'numeric',
            'weight' => 'numeric',
            'code' => 'required',
            'linkman' => 'required',
            'contact_tel' => 'required',
            'rtype' => 'required'
        ],
        [
            'name.required' => '姓名不得为空',
            'name.between' => '姓名必须在2到10位之间',
            'sex.required' => '性别不得为空',
            'mobile.unique' => '电话号码已经绑定过',
            'mobile.required' => '手机不得为空',
            'mobile.digits' => '手机号格式不正确',
            'idcard.required' => '身份证账号不得为空',
            'idcard.size' => '身份证格式不正确',
            'idcard.unique' => '您输入的身份证件号已绑定其他微信号',
            'code.required' => '验证码不得为空',
            'linkman.required' => '紧急联系人不得为空',
            'contact_tel.required' => '联系人电话不得为空',
            'rtype.required' => '联系人关系不得为空',
        ]);
    }
    
    private function validateMobile($request)
    {
        return  Validator::make($request->all(), [
            'mobile' => 'required|digits:11'
        ], [
            'mobile.required' => '请填写您的手机号。',
            'mobile.digits' => '请输入一个正确的手机号。',
            'mobile.whether_auth_sms_sent' => '短信验证码已发送！',
        ]);
    }
    
    private function validateHasSend($mobile)
    {
        return Cache::get($mobile. LTM::SMS_TYPE_LOGIN_RESEND, false);
    }
    
    private function validateCode($mobile,$code)
    {
        return Cache::get($mobile. LTM::SMS_TYPE_LOGIN, false) == $code;
    }
    
    
}
