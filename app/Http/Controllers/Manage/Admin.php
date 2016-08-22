<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;
use App\Models\Manage as ManageM;

class Admin extends Base
{
    public function index()
    {
        //$moudel_id = RMM::where('role_id',Session::get('admin.role_id'))->lists('moudel_id')->toArray();
        //$moudel_name = MM::whereIn('id',$moudel_id)->lists('short_name')->toArray();
        //Session::put('admin.moudel_name',$moudel_name);
    	//return view('admin.index')->with('moudel_name',$moudel_name);
        return view('admin.index');
    }

    public function desktop()
    {
        return view('admin.desktop');
    }

    public function add()
    {
        return view('admin.add',['addresses'=>$this->addresses]);
    }

    public function store()
    {
        $params = Input::all();
        unset($params['password2']);
        if (empty($params['password'])) {
            $params['password'] = md5(substr($params['mobile'], -4));
        } else {
            $params['password'] = md5($params['password']);
        }
        $flag = ManageM::create($params);
        if ($flag) return $this->detail($flag->id);
    }

    public function detail($id)
    {
        $admin = ManageM::find($id);
        return view('admin.detail',['admin'=>$admin,'addresses'=>$this->addresses]);   
    }

    public function update()
    {
        $params = Input::all();
        $admin = ManageM::find($params['id']);
        if(empty($params['password'])){
             $params['password'] = md5(substr($params['mobile'], -4));
         } else {
            $params['password'] = md5($params['password']);
         }       
        unset($params['password2']);
        $admin->update($params);
        if ($admin) return $this->detail($admin->id)->with('success', '修改成功');
    }

    public function auth()
    {
        $params = Input::all();
        $admin = ManageM::find($params['id']);
        if ( $admin->update(['status'=>$params['status']]) ) {
            return 1;
        }
        return 0;
    }

    public function lists()
    {
        $params = Input::all();
        if (isset($params['name']) && !empty($params['name'])){
            $lists = ManageM::where('name','like','%'.$params['name'].'%')->get();
        } else {
            $lists = ManageM::get();
        }
        return view('admin.lists',['lists'=>$lists,'addresses'=>$this->addresses]);
    }

    public function delete()
    {
        $ids = Input::all();
        if (is_array($ids)) {
            foreach ($ids as $key => $value) {
                $flag = AM::find($value)->delete();
            }
        } else {
            $flag = AM::find($ids)->delete();
        }
        if ($flag) return 'ture';
        return "false";
    }

}