<?php

namespace App\Http\Controllers;

use Input;
use Illuminate\Http\Request;
use Validator;

use App\Models\Institution as InstitutionM;
use App\Models\Teacher as TeacherM;



class Institution extends Controller
{
    public function lists()
    {
        $params = Input::all();
        $data = new InstitutionM;
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

    public function unAuthLists()
    {
        $data = InstitutionM::where('status','<>','2')->orderBy('id','desc')->paginate('20',['id','name','status','created_at'])->toArray();
        foreach ($data['data'] as $key=>$value) {
            if ($value['status'] == 1) {
                $data['data'][$key]['status'] = '待审核';
            } elseif($value['status'] == 3) {
                $data['data'][$key]['status'] = '已发布';
            } else {
                $data['data'][$key]['status'] = '审核失败';
            }
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data['data']]);
    }

    public function detail($id)
    {
        $data = InstitutionM::find($id);
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }


    public function update(Request $request)
    {
        $this->userInfo($request);
        $params = Input::all();
        $institution = $this->userInfo;
        $institution->update($params);
        return response()->json(['success' => 'Y','msg' => '修改成功']);
    }

    public function auth()
    {
        $params = Input::all();
        $teacher = TeacherM::find($params['id']);
        $teacher->update(['status'=>2]);
        return response()->json(['success' => 'Y','msg' => '审核成功']);
    }

    public function teachers(Request $request)
    {
        $this->userInfo($request);
        $teachers = TeacherM::where('institution_id',$this->userInfo->id)->get(['id','truename','worked_year','sex','mobile','status']);
        foreach ($teachers as $key=>$value) {
            $teachers[$key]->status = $this->status[$value->status];
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$teachers]);
    }

    public function addTeacher(Request $request)
    {
        $param = Input::all();
        $this->userInfo($request);
        $this->validateInstitutionAddTeacher($request);
        $teacher = TeacherM::firstOrCreate([
            'institution_id'=>$this->userInfo->id,
            'truename'=>$param['truename'],
            'mobile'=>$param['mobile'],
            'sex'=>$param['sex'],
            'password'=>md5($param['password'])
        ]);
        if ($teacher) {
            return response()->json(['success' => 'Y','msg' => '新增成功','data'=>$teacher]);
        } else {
            return response()->json(['success' => 'N','msg' => '新增失败']);
        }
    }

    public function delTeacher(Request $request)
    {
        $this->validateInstitutionTeacher($request);
        $this->userInfo($request);
        $teacher_id = Input::get('teacher_id');
        $teacher = TeacherM::where('institution_id',$this->userInfo->id)->where('id',$teacher_id)->update(['institution_id'=>'']);
        if ($teacher) {
            return response()->json(['success' => 'Y','msg' => '删除成功']);
        } else {
            return response()->json(['success' => 'N','msg' => '删除失败']);
        }
    }

    private function validateInstitutionTeacher($request)
    {
        $this->validate($request, [
            'teacher_id' => 'required'
        ], [
            'teacher_id.required' => 'teacher_id 不得为空'
        ]);
    }

    private function validateInstitutionAddTeacher($request)
    {
        $this->validate($request, [
            'truename' => 'required|between:2,5',
            'mobile' => 'required|digits:11',
            'password' => 'required|between:4,10'
        ], [
            'truename.required' => '姓名不得为空',
            'truename.between' => '姓名格式不2到10位',
            'mobile.required' => '电话不得我空',
            'mobile.digits' => '电话格式不正确',
            'password.required' => '密码不得为空',
            'password.between' => '密度格式4到10位'

        ]);
    }




}