<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;


use App\Models\Manage as ManageM;
use App\Models\Teacher as TeacherM;
use App\Models\Student as StudentM;
use App\Models\Institution as InstitutionM;
use App\Models\User as UM;
use App\Models\LoginToken as LTM;


abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests, BuildFailedValidationResponse{
        BuildFailedValidationResponse::buildFailedValidationResponse insteadof ValidatesRequests;
    }

    public $userInfo;
    public $type;
    public $grades = [
        'primary_three' => '小学三年级',
        'primary_four' => '小学四年级',
        'primary_five' => '小学五年级',
        'primary_six' => '小学六年级',
        'junior_one' => '初一',
        'junior_two' => '初二',
        'junior_three' => '初三',
        'senior_one' => '高一',
        'senior_two' => '高二',
        'senior_three' => '高三'
    ];

    public $grade = [
        'primary' => '小学',
        'junior' => '初中',
        'senior' => '高中'
    ];

    public $addresses = [
        1 => '北京',
        2 => '南京',
        3 => '上海',
        4 => '其他'
    ];

    public $workTime = [
        'Mon' => '周一',
        'Tues' => '周二',
        'Wed' => '周三',
        'Thur' => '周四',
        'Fri' => '周五',
        'Sat' => '周六',
        'Sun' => '周日'
    ];

    public $subject = [
        'Chinese' => '语文',
        'math' => '数学',
        'English' => '英语',
        'politics' => '政治',
        'chemistry' => '化学',
        'physics' => '物理',
        'biology' => '生物',
        'geography' => '地理',
        'art' => '美术',
        'music' => '音乐',
        'PE' => '体育',
    ];

    public $subjects = [
        'Chinese' => '语文',
        'math' => '数学',
        'English' => '英语',
        'politics' => '政治',
        'chemistry' => '化学',
        'physics' => '物理',
        'biology' => '生物',
        'geography' => '地理',
        'art' => '美术',
        'music' => '音乐',
        'PE' => '体育',
    ];

    public $schoolwork = [
        'homework_server' => '课业辅导',
        'prepare_server' => '预习,复习',
        'extra_server' => '补习'
    ];

    public $state = [
        'improvement' => '培育拔高',
        'weak_foundation' => '基础薄弱',
        'inefficiency' => '效率低下',
        'careless' => '粗心马虎',
        'apply_mechanically' => '生搬硬套',
        'lose_active' => '眼高手低'
    ];
    
    public $status = ['认证失败','待认证','已认证'];


    public  function userInfo($request)
    {
        $token = $request->header('token');
        if ($token) {
            $user = LTM::where('token',$token)->first(['user_id','type']);
            if (count($user->user_id)<1)
                die(response()->json(['success' => 'N','msg' => 'token已失效请从新登录']));
            $this->type = $user->type;
            switch ($user->type) {
                case 'teacher':
                    $model = new TeacherM;
                    break;
                case 'manage':
                    $model = new ManageM;
                    break;
                case 'institution':
                    $model = new InstitutionM;
                    break;
                default:
                    $model = new StudentM;
                    break;
            }
            $this->userInfo = $model->find($user->user_id);
        } else {
            exit(response()->json(['success' => 'N','msg' => '请先登录']));
        }
    }

}
