<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;

use App\Models\City as BM;

class Banner extends Controller
{
    public function lists()
    {
        $lists = BM::get();
    	return view('banner.lists',['lists'=>$lists]);
    }

    public function add()
    {
    	return view('banner.add');
    }

    public function store()
    {
    	$params = Input::all();
        $flag = BM::create($params);
        if ($flag) return $this->detail($flag->id);
    }

    public function detail($id)
    {
        $banner = BM::find($id);
        return view('banner.detail',['banner'=>$banner]);
    }

    public function update()
    {
        $params = Input::all();
        $banner = BM::find($params['id']);
        $banner->update($params);
        return view('banner.detail',['banner'=>$banner]);
    }

    public function delete()
    {
       $ids = Input::all();
       if (is_array($ids)) {
            foreach ($ids as $key => $value) {
                BM::find($value)->delete();
            }
        } else {
                BM::find($ids)->delete();
        }
        return redirect('manage/banner/lists/');
    }

}