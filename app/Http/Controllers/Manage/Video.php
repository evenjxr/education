<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;

use App\Models\Video as VM;

class Video extends Controller
{
    public function lists()
    {
        $params = Input::all();
        $video = new VM();
        if (Session::get('admin.city_id'))
            $video = $video->where('city_id',Session::get('admin.city_id'));

        if ( isset($params['keyword']) && $params['keyword'] ) {
            $video = $video->where('title','like','%'.$params['keyword'].'%')
                         ->orWhere('tag','like','%'.$params['keyword'].'%');
        }
        if ( isset($params['start']) && $params['start'] )
            $video = $video->where('created_at','>=',$params['start']);

        if ( isset($params['end']) && $params['end'] )
            $video = $video->where('created_at','<=',$params['end']) ;

        if ( isset($params['status'])&&$params['status']!='' )
            $video = $video->where('status',$params['status']);

    	return view('video.lists',['lists'=>$video->get()]);
    }

    public function add()
    {
    	return view('video.add');
    }

    public function store()
    {
    	$params = Input::all();
        unset($params['file']);
        $flag = VM::create($params);
        if ($flag) return $this->detail($flag->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        $video = VM::find($id);
        return view('video.detail',['video'=>$video]);
    }

    public function update()
    {
        $params = Input::all();
        $video = VM::find($params['id']);
        unset($params['file']);
        $video->update($params);
        if ($video) return $this->detail($video->id)->with('success', '修改成功');
    }

    public function auth()
    {
        $params = Input::all();
        $video = VM::find($params['id']);
        if ( $video->update(['status'=>$params['status']]) ) {
            return 1;
        }
        return 0;
    }

    public function delete()
    {
        $ids = Input::all();
        if (is_array($ids)) {
            foreach ($ids as $key => $value) {
                VM::find($value)->delete();
            }
        } else {
            VM::find($ids)->delete();
        }
        return redirect('manage/video/lists/');
    }


}