<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;
use DB;

use App\Models\Active as AM;
use App\Models\Schedule as SM;
use App\Models\ActiveSchedule as ASM;
use App\Models\Score as SMM;
use App\Models\TeamScore as TSM;
use App\Models\TeamRank as TAM;
use App\Models\Team as TM;
use App\Models\Tournament as TTM;

class Score extends Controller
{
    public function lists()
    {
        $params = Input::all();
        $schedule = new SM();
        if (Session::get('admin.city_id'))
            $schedule = $schedule->where('city_id',Session::get('admin.city_id'))
                ->whereIn('channel',['team','personal']);

        if ( isset($params['keyword']) && $params['keyword'] ) {
            $schedule = $schedule->where('title','like','%'.$params['keyword'].'%')
                ->orWhere('keyword','like','%'.$params['keyword'].'%');
        }
        $schedule = $schedule->where('status',2)->get(['id','title','sign_num','channel']);
        foreach ($schedule as $key=>$value) {
            $active_id = ASM::where('schedule_id',$value->id)->lists('active_id');
            $schedule[$key]->active = AM::whereIn('id',$active_id)->lists('name','id');
        }
        return view('invite.lists',['lists'=>$schedule]);
    }

    public function addTeamScore()
    {
        $channel = AM::channel();
    	return view('active.add')->with(['channel'=>$channel,'standard'=>$this->standard]);
    }

    public function store()
    {
    	$params = Input::all();
        unset($params['file']);
        $flag = AM::create($params);
        if ($flag) return $this->detail($flag->id)->with('success', '新增成功');
    }

    public function personalDetail($id)
    {
        $data = [];
        $schedule = SM::find($id,['title','channel','id']);
        $active_id = ASM::where('schedule_id',$id)->lists('active_id');
        $active = AM::whereIn('id',$active_id)->get(['name','id','standard']);
        $score = SMM::where('schedule_id',$id)->get(['user_id','active_id','num','time','first','second','three']);
        foreach ($score as $key=>$value){
            $data[$value->user_id][$value->active_id]['num'] = $value->num;
            $data[$value->user_id][$value->active_id]['time'] = $value->time;
            $data[$value->user_id][$value->active_id]['first'] = $value->first;
            $data[$value->user_id][$value->active_id]['second'] = $value->second;
            $data[$value->user_id][$value->active_id]['three'] = $value->three;
        }
        $user = DB::table('tournaments')
            ->join('users', 'users.id', '=', 'tournaments.user_id')
            ->where('tournaments.schedule_id',$id)
            ->whereNull('tournaments.deleted_at')
            ->orderBy('users.id')
            ->select('users.id','users.name','users.idcard','users.age','users.sex','users.mobile','users.id','tournaments.actives')
            ->get();
        return view('invite.personal')->with(['schedule'=>$schedule,'active'=>$active,'user'=>$user,'data'=>$data]);
    }


    public function teamDetail($id)
    {
        $team = DB::table('tournaments')
            ->join('teams', 'teams.id', '=', 'tournaments.team_id')
            ->leftjoin('team_ranks',function($join) {
                $join->on('team_ranks.team_id','=','teams.id')->on('team_ranks.schedule_id','=','tournaments.schedule_id');
            })
            ->where('tournaments.schedule_id',$id)
            ->orderBy('team_ranks.victory','desc')
            ->select('teams.id','teams.name','tournaments.schedule_id','tournaments.actives','team_ranks.victory','team_ranks.lose')
            ->get();

        foreach ($team as $key=>$value){
            if ($value->actives) {
                $active = array_keys(json_decode($value->actives,true));
                $team[$key]->active_id = $active[0];
            }
        }
        return view('invite.team')->with(['team'=>$team,'schedule_id'=>$id]);
    }

    public function teamHistory()
    {
        $params = Input::all();
        $history = TSM::where('team_one_id',$params['team_id'])->orWhere('team_two_id',$params['team_id'])
                ->get(['id','team_one_id','team_two_id','team_one_score','team_two_score','created_at']);
        foreach ($history as $key=>$value){
            $history[$key]->team_one_name = TM::find($value->team_one_id)->name;
            $history[$key]->team_two_name = TM::find($value->team_two_id)->name;
        }
        return view('invite.teamhistory',['history'=>$history,'team_id'=>$params['team_id'],'team_name'=>TM::find($params['team_id'])->name,'schedule_id'=>$params['schedule_id']]);
    }

    public function vsTeams()
    {
        $id = Input::get('id');
        $team_ids = TAM::where('schedule_id',$id)->limit(2)->lists('team_id')->toArray();
        if (count($team_ids)>1) {
            $team_scores = TSM::where(['team_one_id'=>$team_ids[0],'team_two_id'=>$team_ids[1]])
                        ->orWhere(['team_one_id'=>$team_ids[1],'team_two_id'=>$team_ids[0]])
                        ->first();
        }
        if (isset($team_scores)) {
            $team_scores->team_one_name = TM::find($team_scores->team_one_id)->name;
            $team_scores->team_two_name = TM::find($team_scores->team_two_id)->name;
        }
        $team_one = isset($team_ids[0]) ? TM::find($team_ids[0]) : null;
        $team_two = isset($team_ids[1]) ? TM::find($team_ids[1]) : null;
        $team_scores = isset($team_scores) ? $team_scores : null;
        $schedule_id = $id;
        return view('invite.vsteams',compact('team_one','team_two','team_scores','schedule_id'));
    }

    public function updateTeamHistory()
    {
        $params = Input::all();

        $active_id = ASM::where('schedule_id',$params['schedule_id'])->first()->active_id;
        $schedule = SM::find($params['schedule_id']);
        $schedule->update(['sign_num'=>0]);
        //删除之前报名
        TAM::where('schedule_id',$params['schedule_id'])->delete();
        TTM::where('schedule_id',$params['schedule_id'])->delete();
        $params['team_one_id'] && TTM::create(['schedule_id'=>$params['schedule_id'],'team_id'=>$params['team_one_id'],'actives'=>json_decode($active_id)]);
        $params['team_two_id'] && TTM::create(['schedule_id'=>$params['schedule_id'],'team_id'=>$params['team_two_id'],'actives'=>json_decode($active_id)]);

        //新增报名
        $params['team_one_id'] && TAM::create( ['team_id'=>$params['team_one_id'],'active_id'=>$active_id,'schedule_id'=>$params['schedule_id']] );
        $params['team_two_id'] && TAM::create( ['team_id'=>$params['team_two_id'],'active_id'=>$active_id,'schedule_id'=>$params['schedule_id']] );
        $num = count( array_filter([$params['team_one_id'],$params['team_two_id']]));
        $schedule->update(['sign_num'=>$num]);

        $history1 = TSM::where('team_one_id',$params['team_one_id'])->where('team_two_id',$params['team_two_id'])->first();
        $history2 = TSM::where('team_one_id',$params['team_two_id'])->where('team_two_id',$params['team_one_id'])->first();
        $history = $history1 ? : $history2;
        if ($history) {
            $this->teamUpdate($params['schedule_id'],$params['team_one_id'],$params['team_two_id']);
            $history = $history->update(['team_one_score'=>$params['team_one_score'],'team_two_score'=>$params['team_two_score']]);
        } else {
            $num2 = count( array_filter([$params['team_one_score'],$params['team_two_score']]));
            if ($num == 2 && $num2 == 2) {
                $history = TSM::firstOrCreate([
                    'team_one_id' => $params['team_one_id'],
                    'team_one_score' => $params['team_one_score'],
                    'team_two_id' => $params['team_two_id'],
                    'team_two_score' => $params['team_two_score']
                ]);
            }
        }
        if ($num == 2 && $num2 == 2) {
            $this->teamUpdate($params['schedule_id'],$params['team_one_id'],$params['team_two_id']);
        }
        echo $history ? 1 : 0;
    }
    
    public function delTeamHistory()
    {
        $params = Input::all();
        $ranks = TAM::where('schedule_id',$params['schedule_id'])->limit(2)->get();
        TTM::where('schedule_id',$params['schedule_id'])->delete();
        $rank = $ranks->lists('team_id')->toArray();
        if(count($rank)==2) {
            TSM::where(['team_one_id'=>$rank[0],'team_two_id'=>$rank[1]])->orWhere(['team_one_id'=>$rank[1],'team_two_id'=>$rank[2]])->delete();
            $this->teamUpdate($params['schedule_id'],$rank[0],$rank[1]);
        }
        $ranks->delete();
        echo $ranks ? 1 : 0;
    }

    private function teamUpdate($schedule_id,$one_team,$two_team)
    {
        $victory_one_num  = 0;
        $lose_one_num = 0;
        $victory_two_num  = 0;
        $lose_two_num = 0;

        $team_one_history = TSM::where('team_one_id',$one_team)->orWhere('team_two_id',$one_team)->get();
        foreach ($team_one_history as $key => $value) {
            if ($value->team_one_id == $one_team ) {
                $value->team_one_score > $value->team_two_score ? $victory_one_num++ :  $lose_one_num++;
            }
            if ($value->team_two_id == $one_team ) {
                $value->team_two_score > $value->team_one_score ? $victory_one_num++ :  $lose_one_num++;
            }
        }
        $team_two_history = TSM::where('team_one_id',$two_team)->orWhere('team_two_id',$two_team)->get();
        foreach ($team_two_history as $key => $value) {
            if ($value->team_one_id == $two_team ) {
                $value->team_one_score > $value->team_two_score ? $victory_two_num++ :  $lose_two_num++;
            }
            if ($value->team_two_id == $two_team ) {
                $value->team_two_score > $value->team_one_score ? $victory_two_num++ :  $lose_two_num++;
            }
        }
        $one =  TAM::where(['schedule_id'=>$schedule_id, 'team_id'=>$one_team])->first();
        $two =  TAM::where(['schedule_id'=>$schedule_id, 'team_id'=>$two_team])->first();
        $one->update(['victory'=>$victory_one_num,'lose'=>$lose_one_num]);
        $two->update(['victory'=>$victory_two_num,'lose'=>$lose_two_num]);
    }

    //个人
    public function personalupdate()
    {
        $params = Input::all();
        $active = explode('|',$params['data']);
        foreach ($active as $key=>$value) {
            if ($value) {
                $arr1 = explode(',' ,$value);
                $user[$arr1[0]][$arr1[1]][$arr1[2]] = $arr1[3];
            }
        }
        foreach ($user as $key=>$value){
            foreach ($value as $k=>$v){
                $flag = SMM::where('schedule_id',$params['schedule_id'])
                    ->where('user_id',$key)
                    ->where('active_id',$k)
                    ->first();
                if ($flag) {
                    $flag->update($v);
                } else {
                    $flag = SMM::create(array_merge(['user_id'=>$key,'active_id'=>$k,'schedule_id'=>$params['schedule_id']],$v));
                }
            }
        }
        if ($flag) {
            return 1;
        } else {
            return 0;
        }
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