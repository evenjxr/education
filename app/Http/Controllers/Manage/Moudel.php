<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;

use App\Models\Moudel as MM;

class Moudel extends Controller
{
    public function lists()
    {
        $lists = MM::get();
    	return view('moudel.lists',['lists'=>$lists]);
    }

    public function add()
    {
    	return view('moudel.add');
    }

    public function store()
    {
    	$params = Input::all();
        $flag = MM::create($params);
        if ($flag) return $this->detail($flag->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        $moudel = MM::find($id);
        return view('moudel.detail',['moudel'=>$moudel]);
    }

    public function update()
    {
        $params = Input::all();
        $moudel = MM::find($params['id']);
        $moudel->update($params);
        if ($moudel) return $this->detail($moudel->id)->with('success', '修改成功');
    }

    public function delete()
    {
        $ids = Input::all();
        if (is_array($ids)) {
            foreach ($ids as $key => $value) {
                $flag = MM::find($value)->delete();
            }
        } else {
            $flag = MM::find($ids)->delete();
        }
        if ($flag) return 'ture';
        return "false";
    }

}