<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;
use App\Extra\SMS;
use App\Models\Teacher as TeacherM;
use App\Models\Institution as InstitutionM;

class Teacher extends Controller
{
    public $grade = [
        'primary_one' => '小学一年级',
        'primary_two' => '小学二年级',
        'primary_three' => '小学三年级',
        'primary_four' => '小学四年级',
        'primary_five' => '小学五年级',
        'primary_six' => '小学六年级',
        'junior_one' => '初中一年级',
        'junior_two' => '初中二年级',
        'junior_three' => '初中三年级',
        'senior_one' => '高中一年级',
        'senior_two' => '高中二年级',
        'senior_three' => '高中三年级'
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


    public function add()
    {
        return view('teacher.add',[
            'grade'=>$this->grade,
            'addresses'=>$this->addresses,
            'workTime'=>$this->workTime,
            'subject'=>$this->subject,
            'institution' => InstitutionM::where('status',2)->lists('name','id')
            ]);
    }

    public function store()
    {
        $params = Input::all();
        $params['subject'] = serialize($params['subject']);
        $params['grade'] = serialize($params['grade']);
        $params['work_time'] = serialize($params['work_time']);
        $params['password'] = md5(substr($params['mobile'], -4));
        $teacher = TeacherM::create($params);
        if ($teacher) return $this->detail($teacher->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        $teacher = TeacherM::find($id);
        $teacher->subject = unserialize($teacher->subject);
        $teacher->grade = unserialize($teacher->grade);
        $teacher->work_time = unserialize($teacher->work_time);
        return view('teacher.detail',['teacher'=>$teacher,'addresses'=>$this->addresses,'grade'=>$this->grade,'workTime'=>$this->workTime,'subject'=>$this->subject]);
    }

    public function update()
    {
        $params = Input::all();
        $user = TeacherM::find($params['id']);
        $user->update($params);
        if ($user) return $this->detail($user->id)->with('success', '修改成功');
    }

    public function lists()
    {
        $keyword = Input::get('keyword');
        if (isset($keyword) && !empty($keyword)){
            $lists = teacherM::where('username','like','%'.$keyword.'%')
                        ->orWhere('mobile','like','%'.$keyword.'%')
                        ->get();
        } else {
            $lists = teacherM::get();
        }
        return view('teacher.lists',['lists'=>$lists,'addresses'=>$this->addresses,'grade'=>$this->grade]);
    }

    public function show($id)
    {
        $user = UM::find($id);
        $cityArr = CM::lists('name','id')->toArray();
        isset($user->city_id) && $user->city = $cityArr[$user->city_id];
        return view('user.show',['user'=>$user,'addresses'=>$this->addresses,'grade'=>$this->grade]);
    }

    public function search()
    {
        $keyword = Input::get('keyword');
        if (isset($keyword) && !empty($keyword)){
            $lists = UM::where('name','like','%'.$keyword.'%')
                ->orWhere('nickname','like','%'.$keyword.'%')
                ->orWhere('mobile','like','%'.$keyword.'%')
                ->orWhere('idcard','like','%'.$keyword.'%')
                ->get();
        } else {
            $lists = UM::get();
        }
        return view('user.search',['lists'=>$lists]);
    }

    public function  score()
    {
        echo "暂无";
    }

    public function  sms()
    {
        $user_id = Input::get('id');
        $user = UM::find($user_id);
        return true;
        SMS::send(LTM::SMS_AUTH_ID, $user->mobile,'111');
    }


}