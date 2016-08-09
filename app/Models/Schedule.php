<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Eloquent
{
    use SoftDeletes;
    protected $dates = ['create_at', 'update_at', 'deleted_at'];
    protected $guarded = ['id'];


    const SMS_SCHEDULE_CHECK = 'schedule_check';

    static public function channel()
    {
        return [
            'team'  => '团队类活动',
            'personal' => '个人类活动',
            'show'=> '表演活动',
            'other' => '其他'
        ];
    }
}