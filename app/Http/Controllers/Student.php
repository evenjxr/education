<?php

namespace App\Http\Controllers;

use Input;
use Session;
use Illuminate\Http\Request;

use App\Models\Teacher as TeacherM;

class Student extends Controller
{
    public function state()
    {
        return response()->json(['success'=>'Y','msg' => '','data'=>$this->state]);
    }

    public function grades()
    {
        return response()->json(['success'=>'Y','msg' => '','data'=>$this->grades]);
    }

    public function orderLists()
    {
        
    }






}