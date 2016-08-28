<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;

use App\Models\Manage as ManageM;
use App\Models\Teacher as TeacherM;
use App\Models\Institution as InstitutionM;


class Manage extends Controller
{
    public function authTeacher(Request $request)
    {
        $this->userInfo($request);
        $this->validateId($request);
        if ($this->type == 'manage') {
            $param = Input::all();
            TeacherM::where('id',$param['id'])->update(['status'=>'2']);
            return response()->json(['success' => 'Y','msg' => '审核成功']);
        } else {
            return response()->json(['success' => 'N','msg' => '只有管理员可以审核']);
        }
    }


    public function authInstitution(Request $request)
    {
        $this->userInfo($request);
        $this->validateId($request);
        if ($this->type == 'manage') {
            $param = Input::all();
            InstitutionM::where('id',$param['id'])->update(['status'=>'2']);
            return response()->json(['success' => 'Y','msg' => '审核成功']);
        } else {
            return response()->json(['success' => 'N','msg' => '只有管理员可以审核']);
        }
    }

    private function validateId($request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ], [
            'id.required' => 'id不得为空',
            'id.numeric' => 'id格式不正确'
        ]);
    }
}