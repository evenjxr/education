<?php
/**
 * Created by 朱士亚.
 * Date: 15/3/18
 * @author 朱士亚<i@imzsy.com>
 */

namespace App\Http\Controllers;


use App\Extra\SMS;
use App\Models\User;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Main extends Base
{
    public function wechat($debug = null)
    {
        if ($debug === null) {
            $debug = config('app.debug');
        }
        return new \Wechat([
            'token'     => md5('txcap'),
            'appid'     => $debug ? 'wx168587404794bc3a' : env('authappid'),
            'appsecret' => $debug ? '8c6cfc3467c03d727002d534f7f67af1' : env('authappsecret'),
            'debug'     => $debug,
        ]);
    }

    public function bind()
    {
        $_title = '绑定手机';
        return view('bind', compact('_title'));
    }

    public function authSms(Request $request)
    {
        $mobile = $request->get('mobile');
        if (!is_numeric($mobile) || strlen($mobile) != 11) {
            return response()->json(['status' => 0, 'msg' => '手机号格式错误']);
        }
        $user = User::where('mobile', $mobile)->first();
        if (!empty($user)) {
            if ($user->wechat_fake_id != '') {
                return response()->json(['status' => 0, 'msg' => '您的手机号已绑定过微信号.']);
            }
            if ($user->name != $request->get('name')) {
                return response()->json(['status' => 0, 'msg' => '您填写的姓名与系统中的姓名不符.']);
            }
        }
        //发送短信验证码
        $code = rand('10000', '99999');
        SMS::send('bind', $mobile, ['code' => $code]);
        $expiresAt = Carbon::now()->addMinutes(5);
        Cache::put('sms_auth_' . $mobile, $code, $expiresAt);

        return response()->json(['status' => 1, 'msg' => '短信验证码已成功发送到您的手机上,请耐心查收.']);
    }

    public function doBind(Request $request)
    {
        //检查验证码
        $mobile = $request->get('mobile');
        if (Cache::get('sms_auth_' . $mobile, false) === false || Cache::get('sms_auth_' . $mobile != $request->get('code'))) {
            return response()->json(['status' => 0, 'msg' => '手机短信验证错误!']);
        }
        if (empty($request->get('name'))) {
            return response()->json(['status' => 0, 'msg' => '请填写您的真实姓名!']);
        }

        $user = User::where('mobile', $request->get('mobile'))->first();
        if (empty($user)) {
            $user = User::create(['name' => $request->get('name'), 'mobile' => $request->get('mobile')]);
        }
        $user->wechat_fake_id = $request->get('openid');
        $user->save();
        $token = $user->login();
        return response()->json(['status' => 1, 'msg' => '手机号绑定成功.', 'return' => $request->get('return')])->withCookie(cookie()->forever('access_token', $token->token));
    }
}