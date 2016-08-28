<?php

namespace App\Http\Controllers;

use Input;
use Session;
use Illuminate\Http\Request;



class Common extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $path = env('FILE_PATH') . '/';
        $dir = date('Y').'/'.date('m').'/'.date('d');
        $url = $path.$dir;
        if (is_dir($url)) @mkdir($url,0777,true);
        $new_name = date('His').rand(100,999).strstr($_FILES['file']['name'],'.');
        $file->move($url,$new_name);
        return '/uploads/'.$dir.'/'.$new_name;
    }

    public function getSchoolWork()
    {
        return response()->json(['success' => 'Y','msg' => '','schoolwork'=>$this->schoolwork]);
    }

    public function getGrade()
    {
        return response()->json(['success' => 'Y','msg' => '','grade'=>$this->grade]);
    }

    public function getAddress()
    {
        return response()->json(['success' => 'Y','msg' => '','addresses'=>$this->addresses]);
    }

    public function getWorkTime()
    {
        return response()->json(['success' => 'Y','msg' => '','workTime'=>$this->workTime]);
    }

    public function getSubjects()
    {
        return response()->json(['success' => 'Y','msg' => '','subjects'=>$this->subjects]);
    }

    public function getTeacherState()
    {
        return response()->json(['success' => 'Y','msg' => '','status'=>$this->status]);
    }

    public function getFeeList()
    {
        return response()->json(['success' => 'Y','msg' => '','status'=>$this->status]);
    }
    
    

}
