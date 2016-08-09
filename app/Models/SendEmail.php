<?php

/**
 * 发送邮件的Model
 **/

namespace App\Models;

use Config;
use Illuminate\Mail\Mailer as Mailers;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Eloquent{
	
	public static function send($message,$subject){
		$emailArr = Config::get('emaillist',false);
        //闭包调用参数
		$aa = Mail::raw($message,function($email) use(&$subject,&$emailArr)  
		{
			$subject = &$subject;
			$userList = &$emailArr; 
		    $email->to($userList);
		    $email->subject($subject);
		   // $email->to($emailArr);
		});
		return $aa;
	}	
}