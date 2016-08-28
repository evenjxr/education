<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Eloquent
{
    use SoftDeletes;
    protected $dates = ['create_at', 'update_at', 'deleted_at'];
    protected $guarded = ['id'];

    // public function typeMap(){
    // 	return [
    // 		'case' => '案例',
    // 		'job' => '招聘',
    // 		'article' => '文章',
    // 		'team' => '团队',
    //         'activity' => '活动',
    //         'report' => '战报',
    // 	];
    // }

    // public function getType(){
    // 	return isset($this->typeMap()[$this->type]) ? $this->typeMap()[$this->type] :'';
    // }
}