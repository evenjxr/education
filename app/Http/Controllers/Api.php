<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Extra\Wechat;
use Input;

class Api extends Controller
{

    const DEFAULT_REPLY = 'basketball';

    public function getJsSign()
    {
        $url =Input::get('url') ?  : 'http://101.200.131.30/dist/main.html';
        return $this->wechat(true)->getJsSign($url);
    }

    public function wechat($debug = false)
    {
        return new Wechat([
            'token'     => 'weixin',
            'appid'     => $debug ? env('appid') : 'wxbe15845159a390b0',
            'appsecret' => $debug ? env('appsecret') : '718689bbc17209e6626315fa2ab569c1',
            'debug'     => $debug,
        ]);
    }

    public function handOut()
    {
        $wechat = $this->wechat(true)->getRev();
        $wechat->valid();
        $type = $wechat->getRevType();
        switch ($type) {
            case Wechat::MSGTYPE_TEXT:
                $wechat->text(self::DEFAULT_REPLY)->reply();
                break;
            case Wechat::MSGTYPE_EVENT:
                $event = $wechat->getRevEvent();
                if ($event['event'] == 'CLICK') {
                    $click = new Click();
                    if (!isset($_COOKIE['user']['openid'])) {
                        call_user_func_array([$click, $event['key']], [$wechat]);
                    }
                }
                break;
            case Wechat::MSGTYPE_IMAGE:
                break;
            default:
                $wechat->text(self::DEFAULT_REPLY)->reply();
        }
        exit;
    }

    

    public function handOutDebug()
    {
        $wechat = $this->wechat(true)->getRev();
        //$wechat -> valid();
        $type = $wechat->getRevType();
        switch ($type) {
            case Wechat::MSGTYPE_TEXT:
                $wechat->text(self::DEFAULT_REPLY)->reply();
                break;
            case Wechat::MSGTYPE_EVENT:
                $event = $wechat->getRevEvent();
                if ($event['event'] == 'CLICK') {
                    $click = new Click();
                    if (!isset($_COOKIE['user']['openid'])) {
                        call_user_func_array([$click, $event['key']], [$wechat]);
                    }
                }
                break;
            case Wechat::MSGTYPE_IMAGE:
                break;
            default:
                $wechat->text(self::DEFAULT_REPLY)->reply();
        }
        exit;
    }

    public function menu()
    {
        dd($this->wechat(true)->createMenu([
            'button' => [
                [
                    'name'       => '篮球新闻',
                    'sub_button' => [
                        [
                            'name' => 'NBA',
                            'type' => 'view',
                            'url'  => 'http://101.200.131.30/v1/nba',
                        ],
                        [
                            'name' => 'CBA',
                            'type' => 'view',
                            'url'  => 'http://101.200.131.30/v1/cba',
                        ]
                    ],
                ],
                [
                    'name'       => '个人中心',
                    'sub_button' => [
                        [
                            'name' => '我的资料',
                            'type' => 'view',
                            'url'  => 'http://101.200.131.30/v1/me',
                        ],
                        [
                            'name' => '我的收藏',
                            'type' => 'view',
                            'url'  => 'http://101.200.131.30/v1/favorites',
                        ],
                        [
                            'name' => '我的成绩',
                            'type' => 'view',
                            'url'  => 'http://101.200.131.30/v1/score',
                        ]
                    ],
                ],
                [
                    'name'       => '更多功能',
                    'sub_button' => [
                        [
                            'name' => '测试中',
                            'type' => 'click',
                            'key'  => 'CUSTOMER_MANAGER',
                        ],

                    ],
                ],
            ],
        ]));
    }



    public function auth()
    {
        return $this->wechat(true)->getOauthRedirect('http://101.200.131.30/dist/wx.html');
    }

    public function getUserData()
    {
        $wechat = $this->wechat(true);
        $res = $wechat->getOauthAccessToken();
        return $wechat->getOauthUserinfo($res['access_token'],$res['openid']);
    }

}
