<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Active extends Eloquent
{
    use SoftDeletes;
    protected $dates = ['create_at', 'update_at', 'deleted_at'];
    protected $guarded = ['id'];

    static public function channel()
    {
    	return [
			'theme' => '主题类活动',
			'show'  => '表演类活动',
			'team'  => '团队类活动',
			'personal' => '个人类活动',
            'camp' => '特训营',
            'other' => '其他'
    	];
    }
}