<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use App\Models\Institution as InstitutionM;
use App\Models\Address as AddressM;


class Institution extends Controller
{
    public $grade = [
        'primary_one' => '小学一年级',
        'primary_two' => '小学二年级',
        'primary_three' => '小学三年级',
        'primary_four' => '小学四年级',
        'primary_five' => '小学五年级',
        'primary_six' => '小学六年级',
        'junior_one' => '初中一年级',
        'junior_two' => '初中二年级',
        'junior_three' => '初中三年级',
        'senior_one' => '高中一年级',
        'senior_two' => '高中二年级',
        'senior_three' => '高中三年级'
    ];

    public $addresses = [
        1 => '北京',
        2 => '南京',
        3 => '上海',
        4 => '其他'
    ];

    public function add()
    {
        return view('institution.add',['addresses'=>$this->addresses]);
    }

    public function store()
    {
        $params = Input::all();
        unset($params['file']);
        $institution = InstitutionM::create($params);
        if ($institution) return $this->detail($institution->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        $institution = InstitutionM::find($id);
        $address = AddressM::find($institution->address_id);
        return view('institution.detail',['institution'=>$institution,'address'=>$address]);
    }

    public function update()
    {
        $params = Input::all();
        $institution = InstitutionM::find($params['id']);
        $institution->update($params);
        if ($institution) return $this->detail($institution->id)->with('success', '修改成功');
    }

    public function auth()
    {
        $params = Input::all();
        $institution = InstitutionM::find($params['id']);
        if ( $institution->update(['status'=>intval($params['status'])]) ) {
            return 1;
        }
        return 0;
    }

    public function lists()
    {
        $keyword = Input::get('keyword');
        if (isset($keyword) && !empty($keyword)){
            $lists = InstitutionM::where('username','like','%'.$keyword.'%')
                        ->orWhere('mobile','like','%'.$keyword.'%')
                        ->get();
        } else {
            $lists = InstitutionM::get();
        }
        return view('institution.lists',['lists'=>$lists]);
    }

    public function show($id)
    {
        $user = UM::find($id);
        $cityArr = CM::lists('name','id')->toArray();
        isset($user->city_id) && $user->city = $cityArr[$user->city_id];
        return view('user.show',['user'=>$user,'addresses'=>$this->addresses,'grade'=>$this->grade]);
    }

    public function search()
    {
        $keyword = Input::get('keyword');
        if (isset($keyword) && !empty($keyword)){
            $lists = UM::where('name','like','%'.$keyword.'%')
                ->orWhere('nickname','like','%'.$keyword.'%')
                ->orWhere('mobile','like','%'.$keyword.'%')
                ->orWhere('idcard','like','%'.$keyword.'%')
                ->get();
        } else {
            $lists = UM::get();
        }
        return view('user.search',['lists'=>$lists]);
    }

    public function  score()
    {
        echo "暂无";
    }

    public function  sms()
    {
        $user_id = Input::get('id');
        $user = UM::find($user_id);
        return true;
        SMS::send(LTM::SMS_AUTH_ID, $user->mobile,'111');
    }


}