<?php

namespace App\Http\Controllers;

use Input;
use Session;
use Illuminate\Http\Request;

use App\Models\Active as AM;

class Active extends Controller
{
    public function lists(Request $request)
    {
        $this->getUserInfo($request);
        $data = AM::where('city_id',$this->city_id)
            ->where('status','2')
            ->whereIn('channel',['personal','team'])
            ->orderBy('sort','desc')
            ->orderBy('updated_at','desc')
            ->paginate('10',['id','name','sort','channel','thumbnail','announcement','standard','updated_at'])->toArray();
        return response()->json(['success' => 'Y','msg' => '','data'=>$data['data']]);
    }

    public function detail(Request $request,$id)
    {
        $this->getUserInfo($request);
        $data = AM::find($id);
        if($data) {
            if( in_array($id,array_keys($this->collection,$data->channel)) ){
                $data->flag = 'has';
            } else {
                $data->flag = 'no';
            }
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }
    
    public function themeDetail(Request $request)
    {
        $this->getUserInfo($request);
        $data = AM::where('city_id',$this->city_id)
            ->where('status','2')
            ->where('channel','theme')
            ->orderBy('updated_at','desc')
            ->first();
        if ($data) {
            $data->start = date('Y.m.d',strtotime($data->start));
            $data->end = date('Y.m.d',strtotime($data->end));
        }
        $data = $data ? :'';
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

    public function getListByChannel(Request $request,$channel='')
    {
        $this->getUserInfo($request);
        $data = AM::where('city_id',$this->city_id)
            ->where('status','2')
            ->where('channel',$channel)
            ->paginate('10',['id','name','thumbnail','announcement'])->toArray();
        $data = $data['data'];
        foreach ( $data as $key=>$value ){
            if(in_array($value['id'],array_keys($this->collection,$channel))) {
                $data[$key]['flag'] = 'has';
            } else {
                $data[$key]['flag'] = 'no';
            }
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }
}