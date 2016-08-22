<?php

namespace App\Http\Controllers\Manage;

use Input;
use Session;
use App\Extra\SMS;
use App\Models\Teacher as TeacherM;
use App\Models\Institution as InstitutionM;

class Teacher extends Base
{
    public function add()
    {
        return view('teacher.add',[
            'grade'=>$this->grade,
            'grades'=>$this->grades,
            'addresses'=>$this->addresses,
            'workTime'=>$this->workTime,
            'subject'=>$this->subject,
            'subjects'=>$this->subjects,
            'schoolwork'=>$this->schoolwork,
            'institution' => InstitutionM::where('status',2)->lists('name','id')
            ]);
    }

    public function store()
    {
        $params = Input::all();
        $params['subject'] = $params['subject'];
        $params['grade'] = $params['grade'];
        $params['subjects'] = $params['subjects'] ? serialize($params['subjects']) : '';
        $params['grades'] = $params['grades'] ? serialize($params['grades']) : '';
        $params['work_time'] = $params['work_time'] ? serialize($params['work_time']) : '';
        $params['schoolwork'] = serialize($params['schoolwork']);
        $params['password'] = md5(substr($params['mobile'], -4));
        $teacher = TeacherM::create($params);
        if ($teacher) return $this->detail($teacher->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        $teacher = TeacherM::find($id);
        $teacher->subjects = $teacher->subjects ? unserialize($teacher->subjects) : [];
        $teacher->grades =   $teacher->grades ? unserialize($teacher->grades) : [];
        $teacher->work_time = $teacher->work_time ? unserialize($teacher->work_time) :[];
        $teacher->schoolwork = $teacher->schoolwork ? unserialize($teacher->schoolwork) : [];
        return view('teacher.detail',['teacher'=>$teacher,'addresses'=>$this->addresses,'grade'=>$this->grade,'grades'=>$this->grades,'workTime'=>$this->workTime,'subjects'=>$this->subjects,'subject'=>$this->subject,'schoolwork'=>$this->schoolwork]);
    }

    public function update()
    {
        $params = Input::all();
        $user = TeacherM::find($params['id']);
        $params['work_time'] = $params['work_time'] ? serialize($params['work_time']) : '';
        $params['schoolwork'] = $params['schoolwork'] ? serialize($params['schoolwork']) : '';
        unset($params['password']);
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
        return view('user.show',['user'=>$user,'addresses'=>$this->addresses,'grade'=>$this->teacher_grade]);
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