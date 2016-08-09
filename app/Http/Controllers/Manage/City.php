<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;

use App\Models\City as CM;

class City extends Controller
{
    public function lists()
    {
        $lists = CM::get();
    	return view('city.lists',['lists'=>$lists]);
    }

    public function add()
    {
    	return view('city.add');
    }

    public function store()
    {
    	$params = Input::all();
        $flag = CM::create($params);
        if ($flag) return $this->detail($flag->id);
    }

    public function detail($id)
    {
        $city = CM::find($id);
        return view('city.detail',['city'=>$city]);
    }

    public function update()
    {
        $params = Input::all();
        $city = CM::find($params['id']);
        $city->update($params);
        return view('city.detail',['city'=>$city]);
    }

    public function delete()
    {
       $ids = Input::all();
       if (is_array($ids)) {
            foreach ($ids as $key => $value) {
                CM::find($value)->delete();
            }
        } else {
                CM::find($ids)->delete();
        }
        return redirect('manage/city/lists/');
    }

}