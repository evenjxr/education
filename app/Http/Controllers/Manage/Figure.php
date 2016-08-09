<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;

use App\Models\Figure as FM;

class Figure extends Controller
{
    public function lists()
    {
        $params = Input::all();
        $figure = new FM();
        if (Session::get('admin.city_id'))
            $figure = $figure->where('city_id',Session::get('admin.city_id'));

        if ( isset($params['keyword']) && $params['keyword'] ) {
            $figure = $figure->where('title','like','%'.$params['keyword'].'%')
                         ->orWhere('keyword','like','%'.$params['keyword'].'%');
        }
        if ( isset($params['start']) && $params['start'] ) 
            $figure = $figure->where('created_at','>=',$params['start']);

        if ( isset($params['end']) && $params['end'] ) 
            $figure = $figure->where('created_at','<=',$params['end']) ;

        if ( isset($params['status'])&&$params['status']!='' )
            $figure = $figure->where('status',$params['status']);

    	return view('figure.lists',['lists'=>$figure->get()]);
    }

    public function add()
    {
    	return view('figure.add');
    }

    public function store()
    {
    	$params = Input::all();
        unset($params['file']);
        $flag = FM::create($params);
        if ($flag) return $this->detail($flag->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        $figure = FM::find($id);
        return view('figure.detail',['figure'=>$figure]);
    }

    public function update()
    {
        $params = Input::all();
        $figure = FM::find($params['id']);
        unset($params['file']);
        $figure->update($params);
        if ($figure) return $this->detail($figure->id)->with('success', '修改成功');
    }

    public function auth()
    {
        $params = Input::all();
        $figure = FM::find($params['id']);
        if ( $figure->update(['status'=>$params['status']]) ) {
            return 1;
        }
        return 0;
    }

    public function delete()
    {
        $ids = Input::all();
        if (is_array($ids)) {
            foreach ($ids as $key => $value) {
                FM::find($value)->delete();
            }
        } else {
            FM::find($ids)->delete();
        }
        return redirect('manage/figure/lists/');
    }


}