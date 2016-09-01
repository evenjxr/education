<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;

use App\Models\Equipment as EquipmentM;
use App\Models\Address as AddressM;
use App\Models\Student as StudentM;

class Equipment extends Controller
{

    public function lists()
    {
        $params = Input::all();
        $equipment = new EquipmentM();

        if ( isset($params['mobile']) && $params['mobile'] ) {
            $equipment = $equipment->where('mobile',$params['mobile']);
        }
        if ( isset($params['start']) && $params['start'] ) 
            $equipment = $equipment->where('created_at','>=',$params['start']);

        if ( isset($params['end']) && $params['end'] ) 
            $equipment = $equipment->where('created_at','<=',$params['end']) ;

        if ( isset($params['status'])&&$params['status']!='' )
            $equipment = $equipment->where('status',$params['status']);

        $lists = $equipment->get();
        foreach ($lists as $key=>$value) {
            $lists[$key]->city = AddressM::find($value->address_id) ['city'];
            if ($value->recommend_type == 1) {
                $lists[$key]->recommend_type = '平台推荐';
            } else {
                $lists[$key]->recommend_type = '指定老师';
            }
        }
    	return view('equipment.lists',['lists'=>$lists]);
    }

    public function add()
    {
     //    $channel = AM::channel();
    	// return view('equipment.add')->with(['channel'=>$channel,'standard'=>$this->standard]);
    }

    public function store()
    {
    	// $params = Input::all();
     //    $flag = AM::create($params);
     //    if ($flag) return $this->detail($flag->id)->with('success', '新增成功');
    }

    public function detail()
    {
        $id = Input::get('id');
        $equipment = EquipmentM::find($id);
        $address = AddressM::find($equipment->address_id);
        $equipment->truename = StudentM::find($equipment->user_id)['truename'];
        return view('equipment.detail',['equipment'=>$equipment,'address'=>$address]);
    }

    public function update()
    {
        // $params = Input::all();
        // $equipment = AM::find($params['id']);
        // $equipment->update($params);
        // if ($equipment) return $this->detail($equipment->id)->with('success', '修改成功');
    }

    public function auth()
    {
        $params = Input::all();
        $equipment = EquipmentM::find($params['id']);
        if ( $equipment->update(['status'=>$params['status']]) ) {
            return 1;
        }
        return 0;
    }

    public function search()
    {
        // $params = Input::all();
        // $equipment = new AM();
        // if ( isset($params['keyword']) && $params['keyword'] ) {
        //     $equipment = $equipment->where('name','like','%'.$params['keyword'].'%');
        // }
        // if ( isset($params['start']) && $params['start'] ) 
        //     $equipment = $equipment->where('created_at','>=',$params['start']);

        // if ( isset($params['end']) && $params['end'] ) 
        //     $equipment = $equipment->where('created_at','<=',$params['end']) ;

        // $lists = $equipment->where('status',2)->get();
        // return view('equipment.search',['lists'=>$lists,'channel'=>AM::channel()]);
    }

    public function delete()
    {
        // $ids = Input::all();
        // if (is_array($ids)) {
        //     foreach ($ids as $key => $value) {
        //         AM::find($value)->delete();
        //     }
        // } else {
        //     AM::find($ids)->delete();
        // }
        // return redirect('manage/equipment/lists/');
    }



}