<?php
/**
 * Created by yantao.
 * User: tao
 * Date: 2015/5/20
 * Time: 14:34
 */
return [
    'api'=>[
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
        ],
        'sms_server_confirm'=>[
            'id'      => 'tVT0Y4',
            'vars'    => ['teacher','amount'],
            'desc'    => '支付确认',
            'content' => '【京南教育】您在我平台的预约的老师 @var(teacher) ,支付费用@var(amount) 元，请确认。如果不对，请联系工作人员。'
        ]
    ]
];
