<?php

namespace App\Http\Controllers;


class Click
{
    protected $map = [
        'CUSTOMER_MANAGER' => 'customerManager'
    ];

    function __call($name, $arguments)
    {
        if(!isset($this->map[$name])){
            throw new \Exception(404);
        }
        call_user_func_array([$this,$this->map[$name]], $arguments);
    }

    /**
     * @param $wechat \Wechat
     */
    public function customerManager($wechat)
    {
        $wechat->text(Api::DEFAULT_REPLY)->reply();

    }


}