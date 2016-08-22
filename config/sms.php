<?php
/**
 * Created by yantao.
 * User: tao
 * Date: 2015/5/20
 * Time: 14:34
 */
return [
    'api'=>[
        'auth_mobile'=>[
            'id'      => 'TrGZd2',
            'vars'    => ['code'],
            'desc'    => '手机号认证',
            'content' => '【场上见】您的验证码为@var(code)，请于10分钟内正确输入验证码。如非本人操作，请忽略本短信。'
        ],
        'schedule_check'=>[
            'id'      => 'TmmIZ3',
            'vars'    => ['time'],
            'desc'    => '检录提醒',
            'content' => '【场上见】您的比赛将于@var(time)开始，请您及时到检录处检录。'
        ],
        'sms_register'=>[
            'id'      => 'rxiqX',
            'vars'    => ['code'],
            'desc'    => '手机号认证',
            'content' => '【京南教育】您的注册验证码为@var(code)，请于10分钟内正确输入验证码。如非本人操作，请忽略本短信。'
        ],
        'sms_reset_password'=>[
            'id'      => 'rxiqX',
            'vars'    => ['code'],
            'desc'    => '手机号认证',
            'content' => '【京南教育】您的验证码为@var(code)，请于10分钟内正确输入验证码。如非本人操作，请忽略本短信。'
        ]
    ],
];
