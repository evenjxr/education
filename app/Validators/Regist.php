<?php

namespace App\Validators;

use App\Models\LoginToken;
use Cache;

class Regist
{
    /**
     * 校验登录短信是否重复发送
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function whetherRegistSMSSent($attribute, $value, $parameters)
    {
        return !Cache::get($value . LoginToken::SMS_TYPE_REGIST_RESEND, false);
    }

    /**
     * 校验登录短息验证码是否正确
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function checkSMSCode($attribute, $value, $parameters)
    {
        $request = app('request');
        return Cache::get($request['mobile'] . LoginToken::SMS_TYPE_REGIST, false) == $value;
    }
}