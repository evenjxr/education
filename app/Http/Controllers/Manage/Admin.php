<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;
use App\Models\Administrator as AM;
use App\Models\City as CM;
use App\Models\Role as RM;
use App\Models\RoleMoudel as RMM;
use App\Models\Moudel as MM;

class Admin extends Controller
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
        $cityArr = CM::lists('name','id')->toArray();
        $roleArr = RM::lists('name','id')->toArray();
        return view('admin.add',['cityArr'=>$cityArr,'roleArr'=>$roleArr]);
    }

    public function store()
    {
        $params = Input::all();
        unset($params['password2']);
        $flag = AM::create($params);
        if ($flag) return $this->detail($flag->id);
    }

    public function detail($id)
    {
        $admin = AM::find($id);
        $cityArr = CM::lists('name','id')->toArray();
        $roleArr = RM::lists('name','id')->toArray();
        return view('admin.detail',['admin'=>$admin,'cityArr'=>$cityArr,'roleArr'=>$roleArr]);   
    }

    public function update()
    {
        $params = Input::all();
        $admin = AM::find($params['id']);
        unset($params['password2']);
        $admin->update($params);
        if ($admin) return $this->detail($admin->id)->with('success', '修改成功');
    }

    public function auth()
    {
        $params = Input::all();
        $admin = AM::find($params['id']);
        if ( $admin->update(['status'=>$params['status']]) ) {
            return 1;
        }
        return 0;
    }

    public function lists()
    {
        $params = Input::all();
        if (isset($params['name']) && !empty($params['name'])){
            $lists = AM::where('name','like','%'.$params['name'].'%')->get();
        } else {
            $lists = AM::get();
        }
        return view('admin.lists',['lists'=>$this->addCity($lists)]);
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