<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Rhumsaa\Uuid\Uuid;

class LoginToken extends Eloquent
{
    use SoftDeletes;

    protected $dates = ['create_at', 'update_at', 'deleted_at'];
    protected $guarded = ['id'];

    const SMS_TYPE_REGIST = 'sms-regist';
    const SMS_TYPE_REGIST_RESEND = 'sms-regist-resend';
    const SMS_AUTH_ID = 'auth_mobile';
    const SMS_REGISTER = 'sms_register';
    
    

    public static function makeToken()
    {
        return Uuid::uuid4()->toString();
    }
    
    public static function saveToken($user,$type,$token)
    {
        $res = self::where('user_id',$user->id)->first();
        if ($res) $res->delete();
        return self::create(['token'=>$token,'type'=>$type,'user_id'=>$user->id]);
    }
}