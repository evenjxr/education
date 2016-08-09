<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;

use App\Models\Role as RM;
use App\Models\Moudel as MM;
use App\Models\RoleMoudel as RMM;

class Role extends Controller
{


    public function lists()
    {
        $lists = RM::get();
    	return view('role.lists',['lists'=>$lists]);
    }

    public function add()
    {
        $models = MM::all();
    	return view('role.add',['models'=>$models]); 
    }

    public function store()
    {
    	$params = Input::all();
        $flag = RM::firstOrCreate(['name'=>$params['name'],'short_name'=>$params['short_name'],'info'=>$params['info']]);
        foreach ($params['moudel'] as $key => $value) {
            RMM::firstOrCreate(['role_id'=>$flag->id,'moudel_id'=>$value]);
        }
        if ($flag) return $this->detail($flag->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        $role = RM::find($id);
        $moudels = MM::all('id','name','short_name');
        $moudelArr = RMM::where('role_id',$id)->lists('moudel_id')->toArray();
        return view('role.detail',['role'=>$role,'moudels'=>$moudels,'moudelArr'=>$moudelArr]);
    }

    public function update()
    {
        $params = Input::all();
        $role = RM::find($params['id']);
        $role->update(['name'=>$params['name'],'short_name'=>$params['short_name'],'info'=>$params['info']]);
        $hasChecked = RMM::where('role_id',$params['id'])->get();
        foreach ($hasChecked as $key => $value) {
            if (!in_array($value->moudel_id,$params['moudel'])) {
                RMM::find($value->id)->delete();
            }
        }

        foreach ($params['moudel'] as $key => $value) {
            RMM::firstOrCreate(['role_id'=>$role->id,'moudel_id'=>$value]);
        }
        if ($role) return $this->detail($role->id)->with('success', '修改成功');
        //if ($role) return redirect('manage/role/detail/'.$role->id)->with('success', '修改成功');
    }

    public function delete()
    {
       $ids = Input::all();
       if (is_array($ids)) {
            foreach ($ids as $key => $value) {
                $flag = RM::find($value)->delete();
            }
        } else {
                $flag = RM::find($ids)->delete();
        }
        if ($flag) return 'ture';
        return "false";
    }

}