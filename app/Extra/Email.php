<?php

namespace App\Extra;

use Config;
use Illuminate\Http\Exception\HttpResponseException as Exception;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendReminderEmail;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Email
{
    
    use DispatchesJobs;

    /**
     * @param $pro 业务
     * @param $to 发送邮件
     * @param $vars 变量
     * @return bool|string
     */
    public static function send($project, $title, $emails)
    {
        $Email = new Email();
        $Email->dispatch((new SendReminderEmail($project, $title, $emails)));
    }

}