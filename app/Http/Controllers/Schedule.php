<?php

namespace App\Http\Controllers;

use Input;
use Session;
use Illuminate\Http\Request;

use App\Models\Schedule as SM;
use App\Models\Tournament as TMM;
use App\Models\Team as TM;
use DB;


class Schedule extends Controller
{
    public function lists(Request $request,$time='')
    {
        $this->getUserInfo($request);
        $time = isset($time) ? strtotime($time) : time();
        $time = date('Y-m-d',$time);
        $data = SM::where('city_id',$this->city_id)
            ->where('status','2')
            ->where('start','like',$time.'%')
            ->orderBy('start')
            ->get(['id','title','start','thumbnail','fee','check','channel','title','announcement','max_num']);
        foreach ($data as $key=>$value){
            $data[$key]['code'] = 0;
            $time = date('Y-m-d',strtotime($value->start)).' '.$value->check;
            if ( time()  < strtotime($time) ) {
                $data[$key]['state'] = 'waiting';
            } elseif(time()  < strtotime($value->start) ) {
                $data[$key]['state'] = 'check';
            } else {
                $data[$key]['state'] = 'over';
            }
            if ($value->channel == 'team') {
                $team_ids = TMM::where('schedule_id',$value->id)->lists('team_id');
                $data[$key]['team_name_one'] = '';
                $data[$key]['team_name_two'] = '';
                if (count($team_ids)>0) {
                    $team_ids = $team_ids->toArray();
                    $names = TM::whereIn('id',$team_ids)->lists('name')->toArray();
                    $data[$key]['team_name_one'] = $names[0];
                    $data[$key]['team_name_two'] = isset($names[1]) ? $names[1] : "";
                    if (in_array($this->user->team_id,$team_ids))  $data[$key]['code'] = 1;
                }
            }elseif($value->channel == 'personal'){
                $user_ids = TMM::where('schedule_id',$value->id)->lists('user_id');
                if (count($user_ids)>0) {
                    $user_ids = $user_ids->toArray();
                    if (in_array($this->user->id,$user_ids))  $data[$key]['code'] = 1;
                }
            }
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

    public function detail($id)
    {
        $data = SM::find($id);
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }


    public function dateLists(Request $request)
    {
        $datas = [];
        $this->getUserInfo($request);
        $data = SM::where('city_id',$this->city_id)
            ->where('status','2')
            ->lists('date_tag','start');
        if ($data) {
            foreach ($data as $key=>$value){
                $value && $datas[strtotime(date('Y-m-d',strtotime($key)))] = $value;
            }
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$datas]);
    }
}