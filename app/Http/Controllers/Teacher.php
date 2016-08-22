<?php

namespace App\Http\Controllers;

use Input;
use Session;
use Illuminate\Http\Request;

use App\Models\Teacher as TeacherM;

class Teacher extends Controller
{
    public function lists()
    {
        $params = Input::all();
        $data = new TeacherM;
        if (isset($params['subject']) && !empty($params['subject']))
                $data = $data->where('subject',$params['subject']);
        if (isset($params['grade']) && !empty($params['grade']))
            $data = $data->where('grade',$params['grade']);
        if (isset($params['schoolwork']) && !empty($params['schoolwork']))
            $data = $data->where('schoolwork','like','%'.$params['schoolwork'].'%');
        if (isset($params['truename']) && !empty($params['truename']))
            $data = $data->where('schoolwork',$params['truename']);
        if (isset($params['mobile']) && !empty($params['mobile']))
            $data = $data->where('mobile',$params['mobile']);

        $data = $data->orderBy('hits','desc')
            ->orderBy('star','desc')
            ->paginate('10',['id','truename','star','school_name','worked_year','grade','subject','introduction','avatar'])->toArray();
        return response()->json(['success' => 'Y','msg' => '','data'=>$data['data']]);
    }

    public function detail($id)
    {
        $data = TeacherM::find($id);
        if($data) {
            $data->grade = $this->grade[$data->grade];
            $data->subject = $this->subject[$data->subject];
            $data->grades = unserialize($data->grades);
            $data->subjects = unserialize($data->subjects);
            $data->schoolwork = unserialize($data->schoolwork);
            $data->work_time = unserialize($data->work_time);
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

    public function recommend()
    {
        $data = TeacherM::orderBy('hits','desc')
            ->orderBy('star','desc')
            ->limit(6)
            ->get(['id','truename','star','school_name','worked_year','grade','subject','introduction','avatar'])->toArray();
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

    public function update(Request $request)
    {
        $this->userInfo($request);
        $params = Input::all();
        $teacher = $this->userInfo;
        $params['work_time'] = isset($params['work_time']) ? serialize($params['work_time']) : '';
        $params['schoolwork'] = isset($params['schoolwork']) ? serialize($params['schoolwork']) : '';
        $params['grades'] = isset($params['grades']) ? serialize($params['grades']) : '';
        $params['subjects'] = isset($params['subjects']) ? serialize($params['subjects']) : '';
        $teacher->update($params);
        return response()->json(['success' => 'Y','msg' => '修改成功']);
    }

    public function auth()
    {
        $params = Input::all();
        $teacher = TeacherM::find($params['id']);
        $teacher->update(['status'=>2]);
        return response()->json(['success' => 'Y','msg' => '审核成功']);
    }

    public function subjects()
    {
        return response()->json(['success' => 'Y', 'msg' => '', 'data' => $this->subjects]);
    }




}