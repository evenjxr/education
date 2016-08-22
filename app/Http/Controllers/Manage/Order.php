<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;

use App\Models\Active as AM;

class Order extends Controller
{

    public function lists()
    {
        $params = Input::all();
        $active = new AM();
        if (Session::get('admin.city_id')) 
            $active = $active->where('city_id',Session::get('admin.city_id'));

        if ( isset($params['keyword']) && $params['keyword'] ) {
            $active = $active->where('name','like','%'.$params['keyword'].'%');
        }
        if ( isset($params['start']) && $params['start'] ) 
            $active = $active->where('created_at','>=',$params['start']);

        if ( isset($params['end']) && $params['end'] ) 
            $active = $active->where('created_at','<=',$params['end']) ;

        if ( isset($params['status'])&&$params['status']!='' )
            $active = $active->where('status',$params['status']);
    	return view('active.lists',['lists'=>$active->get(),'channel'=>AM::channel(),'standard'=>$this->standard]);
    }

    public function add()
    {
        $channel = AM::channel();
    	return view('active.add')->with(['channel'=>$channel,'standard'=>$this->standard]);
    }

    public function store()
    {
    	$params = Input::all();
        $flag = AM::create($params);
        if ($flag) return $this->detail($flag->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        $active = AM::find($id);
        return view('active.detail',['active'=>$active,'channel'=>AM::channel(),'standard'=>$this->standard]);
    }

    public function update()
    {
        $params = Input::all();
        $active = AM::find($params['id']);
        $active->update($params);
        if ($active) return $this->detail($active->id)->with('success', '修改成功');
    }

    public function auth()
    {
        $params = Input::all();
        $active = AM::find($params['id']);
        if ( $active->update(['status'=>$params['status']]) ) {
            return 1;
        }
        return 0;
    }

    public function search()
    {
        $params = Input::all();
        $active = new AM();
        if ( isset($params['keyword']) && $params['keyword'] ) {
            $active = $active->where('name','like','%'.$params['keyword'].'%');
        }
        if ( isset($params['start']) && $params['start'] ) 
            $active = $active->where('created_at','>=',$params['start']);

        if ( isset($params['end']) && $params['end'] ) 
            $active = $active->where('created_at','<=',$params['end']) ;

        $lists = $active->where('status',2)->get();
        return view('active.search',['lists'=>$lists,'channel'=>AM::channel()]);
    }

    public function delete()
    {
        $ids = Input::all();
        if (is_array($ids)) {
            foreach ($ids as $key => $value) {
                AM::find($value)->delete();
            }
        } else {
            AM::find($ids)->delete();
        }
        return redirect('manage/active/lists/');
    }



}