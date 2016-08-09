<?php

namespace App\Http\Controllers;

use Input;
use Session;
use Illuminate\Http\Request;

use App\Models\Schedule as SM;
use App\Models\Tournament as TM;
use App\Models\Team as TTM;
use APP\Models\ActiveSchedule as ASM;
use DB;


class Tournament extends Controller
{
    public function lists(Request $request)
    {
        $this->getUserInfo($request);
        $tournament_t = [];
        $tournament_p = [];
        if(count($this->personal_tournament)) {
            foreach ($this->personal_tournament as $key=>$value) {
                $tournament_p[$key]['id'] = $value->id;
                $tournament_p[$key]['schedule_id'] = $value->schedule_id;
                $tournament_p[$key]['type'] = 'personal';
                $tournament_p[$key]['schedule'] = SM::find($value->schedule_id,['title','start','announcement','fee','check','thumbnail']);
            }
        }
        if(count($this->team_tournament)) {
            foreach ($this->team_tournament as $key=>$value) {
                $tournament_t[$key]['id'] = $value->id;
                $tournament_t[$key]['schedule_id'] = $value->schedule_id;
                $tournament_t[$key]['type'] = 'team';
                $tournament_t[$key]['schedule'] = SM::find($value->schedule_id,['title','start','announcement','fee','check','thumbnail']);
            }
        }
        $data = array_merge($tournament_t,$tournament_p);
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

    public function add(Request $request,$id)
    {
        $this->getUserInfo($request);
        $schedule = SM::find($id);
        $schedule['actives'] = DB::table('active_schedules')
            ->leftjoin('actives', 'actives.id', '=', 'active_schedules.active_id')
            ->where('active_schedules.schedule_id','=',$id)
            ->whereNull('active_schedules.deleted_at')
            ->whereNull('actives.deleted_at')
            ->select('actives.id','actives.name','actives.channel')
            ->get();
        $schedule->code = 0;
        $schedule_ids = [];
        if ($schedule->channel == 'personal') {
            foreach ($this->personal_tournament as $key=>$value) {
                $schedule_ids[$key] = $value->schedule_id;
            }
            if (in_array($id,$schedule_ids))
                $schedule->code = 1;
        }
        if ($schedule->channel == 'team') {
            foreach ($this->team_tournament as $key=>$value) {
                $schedule_ids[$key] = $value->schedule_id;
            }
            if (in_array($id,$schedule_ids))
                $schedule->code = 1;
        }
        $time = date('Y-m-d',strtotime($schedule->start)).' '.$schedule->check;
        if ( time()  < strtotime($time) ) {
            $schedule->state = 'waiting';
        } elseif(time()  < strtotime($schedule->start) ) {
            $schedule->state = 'check';
        } else {
            $schedule->state= 'over';
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$schedule]);
    }

    public function store(Request $request)
    {
        $params = Input::all();
        $this->getUserInfo($request);
        if (!$this->can)  return response()->json(['success' => 'N','msg' => '您报名的赛程尚未结束，请在该赛程结束24小时后再来报名']);
        $schedule= SM::find($params['schedule_id'],['id','channel']);

        if ($schedule->channel == 'team') {
            $actives = ASM::where('schedule_id',$params['schedule_id'])->get(['active_id']);
            $data = [
                'team_id' => $this->user->team_id,
                'schedule_id' => $params['schedule_id'],
                'actives' => json_decode($actives)
            ];
        } elseif ($schedule->channel == 'personal') {
            $actives = json_encode($params['actives']);
            $data = [
                'user_id' => $this->user->id,
                'schedule_id' => $params['schedule_id'],
                'actives' => $actives
            ];
        }
        TM::firstOrCreate($data);
        $schedule->increment('sign_num');
        return response()->json(['success' => 'Y','msg' => '报名成功']);
    }

    public function delete(Request $request,$id)
    {
        $this->getUserInfo($request);
        $tournament = TM::where('schedule_id',$id)->where('user_id',$this->user->id)->orWhere('team_id',$this->user->team_id)->first();
        $schedule = SM::find($id);
        $time = strtotime($schedule->start)-env('time',24)*60*60;
        if (time()  < $time ) {
            if ($schedule->channel == 'personal') {
                $tournament->delete();
                if ($schedule->sign_num>0) $schedule->decrement('sign_num');
                return response()->json(['success' => 'Y','msg' => '取消成功']);
            } elseif($schedule->channel == 'team') {
                $team = TTM::find($tournament->team_id);
                if ($team->palyer_one_id == $this->user->id) {
                    if ($schedule->sign_num>0)  $schedule->decrement('sign_num');
                    $tournament->delete();
                    return response()->json(['success' => 'Y','msg' => '取消成功']);
                } else {
                    return response()->json(['success' => 'N','msg' => '只有队长允许取消']);
                }
            }
        } else {
            return response()->json(['success' => 'N','msg' => '检录开始或者结束不得取消']);
        }
    }


}