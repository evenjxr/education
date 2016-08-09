<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Session;

use App\Models\Score as SM;
use App\Models\Active as AM;
use App\Models\User as UM;
use App\Models\TeamScore as TSM;
use App\Models\TeamRank as TAM;
use App\Models\Team as TM;
use App\Models\Schedule as SMM;


class Rank extends Controller
{
    public $users;
    
    public function lists(Request $request,$active_id=null,$flag=true)
    {
        $this->getUserInfo($request);
        $mydata=[];
        $otherdata=[];
        if ($active_id) {
            $active = AM::find($active_id);
            if ($active) {
                if ($active->channel == 'personal') {
                    $otherdata = $this->rankPersonal($active);
                    if (count($otherdata)>0) {
                        foreach ($otherdata as $key=>$value){
                            $otherdata[$key]->rank = $key+1;
                            if ($value->user_id == $this->user->id){
                                $mydata = $otherdata[$key];
                            }
                        }
                    }
                    if(!$mydata) {
                        $mydata = [
                            'user_id' => $this->user->id,
                            'num' => 0,
                            'time' => 0,
                            'name' => $this->user->name,
                            'rank' => 0,
                            'avatar' => $this->user->avatar,
                            'standard' => $active->standard,
                            'honour' => ''
                        ];
                    }

                } elseif($active->channel == 'team') {
                    $otherdata = $this->rankTeam($active);
                    if($this->user->team_id) {
                        if (count($otherdata)>0) {
                            foreach ($otherdata as $key=>$value){
                                $otherdata[$key]->rank = $key+1;
                                if ($value->team_id == $this->user->team_id){
                                    $mydata = $otherdata[$key];
                                }
                            }
                        }
                    }
                    if(!$mydata) {
                        $mydata = [
                            'team_id' => $this->user->team_id,
                            'victory' => 0,
                            'lose' => 0,
                            'name' => $this->user->team_id ? TM::find($this->user->team_id)->name : '',
                            'rank' => 0,
                            'avatar' => $this->user->avatar,
                            'start' => '',
                            'player_one_name' => $this->user->team_id ? UM::find(TM::find($this->user->team_id)->player_one_id)->name  : ''
                        ];
                    }
                }
            }
        } else {
            $data = $this->rankAll();
            if (count($data)>0) {
                $time = SM::where('user_id',array_keys($data)[0])->join('schedules','schedules.id','=','scores.schedule_id')->first()->start;
                $users = UM::whereIn('id',$this->users)->get(['id','name','avatar','age']);
                foreach ($users as $key=>$value){
                    $userArr[$value->id] = $value;
                }
                $i = 1;
                foreach ($data as $key=>$value){
                    $otherdata[$i]['user_id'] = $key;
                    $otherdata[$i]['name'] = $userArr[$key]->name;
                    $otherdata[$i]['avatar'] = $userArr[$key]->avatar;
                    $otherdata[$i]['rank'] = $i;
                    $otherdata[$i]['num'] = '';
                    if ($userArr[$key]->age<19) {
                        $otherdata[$i]['type'] = '19周岁以下';
                    } else {
                        $otherdata[$i]['type'] = '成人组';
                    }
                    $otherdata[$i]['standard'] = 'num';
                    $otherdata[$i]['start'] = date('Y-m-d',strtotime($time));
                    if ($this->user->id == $key) $mydata = $otherdata[$i];
                    $i++;
                }
            }

            if(!$mydata) {
                if ($this->user->age<19) {
                    $type = '19周岁以下';
                } else {
                    $type = '成人组';
                }
                $mydata = [
                    'user_id' => $this->user->id,
                    'num' => '',
                    'time' => '',
                    'type' => $type,
                    'name' => $this->user->name,
                    'rank' => 0,
                    'avatar' => $this->user->avatar,
                    'standard' => 'num',
                    'honour' => ''
                ];
            }
        }
        if ($flag) {
            return response()->json(['success' => 'Y','msg' => '','data'=>['mydata'=>$mydata,'otherdata'=>$otherdata]]);
        } else {
            return $mydata;
        }
    }

    private function rankPersonal($active)
    {
        if ($active->standard == 'time') {
            $scores = SM::where('active_id',$active->id)
                ->join('users','scores.user_id','=','users.id')
                ->join('schedules','schedules.id','=','scores.schedule_id')
                ->orderBy('scores.time')
                ->orderBy('users.age')
                ->select('scores.user_id','scores.honor','scores.time','scores.schedule_id','scores.honor','users.name','users.age','schedules.start','users.avatar')
                ->get();
        }elseif ($active->standard == 'num'){
            $scores = SM::where('active_id',$active->id)
                ->join('users','scores.user_id','=','users.id')
                ->join('schedules','schedules.id','=','scores.schedule_id')
                ->orderBy('scores.num','desc')
                ->orderBy('users.age')
                ->select('scores.user_id','scores.num','scores.honor','scores.schedule_id','scores.honor','users.name','schedules.start','users.age','users.avatar')
                ->get();
        }elseif ($active->standard == 'num_time'){
            $scores = SM::where('active_id',$active->id)
                ->join('users','scores.user_id','=','users.id')
                ->join('schedules','schedules.id','=','scores.schedule_id')
                ->orderBy('scores.num','desc')
                ->orderBy('scores.time')
                ->orderBy('users.age')
                ->select('scores.user_id','scores.time','scores.honor','scores.num','scores.schedule_id','scores.honor','users.name','schedules.start','users.age','users.avatar')
                ->get();
        }elseif($active->standard =='best_of_three'){
            $score = SM::where('active_id',$active->id)
                ->join('users','scores.user_id','=','users.id')
                ->join('schedules','schedules.id','=','scores.schedule_id')
                ->orderBy('scores.time')
                ->orderBy('users.age')
                ->select('scores.user_id','scores.first','scores.honor','scores.second','scores.three','scores.schedule_id','scores.honor','users.age','users.name','schedules.start','users.avatar')
                ->get();
            foreach ($score as $key=>$value){
                $bestScore = $value->first > $value->second ? ($value->first>$value->three ? $value->first : $value->three) :($value->second>$value->three ? $value->second : $value->three);
                $score[$key]->best =  $bestScore;
                $scoresort[$bestScore] = $score[$key];
            }
            if(isset($scoresort)&&count($scoresort)>0) {
                krsort($scoresort);
                foreach ($scoresort as $key=>$value){
                    $scores[] = $scoresort[$key];
                }
            }
        }
        if (isset($scores)&&count($scores)>0) {
            foreach ($scores as $k=>$v){
                $scores[$k]->standard = $active->standard;
            }
        } else {
            $scores = [];
        }
        return $scores;
    }

    private function rankTeam($active)
    {
        $data = TAM::where('active_id',$active->id)
                    ->join('teams','team_ranks.team_id','=','teams.id')
                    ->join('users','teams.player_one_id','=','users.id')
                    ->join('schedules','schedules.id','=','team_ranks.schedule_id')
                    ->select('team_ranks.team_id','victory','lose','teams.name','start','users.name as player_one_name','users.avatar')
                ->orderBy('victory','desc')->orderBy('lose')->get();
        return $data;
    }

    private function rankAll()
    {
        $rank = [];
        $schedule_ids = SMM::where('city_id',$this->city_id)->lists('id');
        $unRankUser = SM::whereIn('schedule_id',$schedule_ids)->whereNull('num')->whereNull('time')->whereNull('first')->whereNull('second')->whereNull('three')->lists('user_id')->toArray();
        $rankUser = SM::whereIn('schedule_id',$schedule_ids)->groupBy('user_id')->whereNotIn('user_id',$unRankUser)->lists('user_id')->toArray();
        //保存用户数组,返回用户信息
        $this->users = $rankUser;
        foreach ($rankUser as $key=>$value){
            $rank[$value]= 0;
        }
        $actives = SM::whereIn('schedule_id',$schedule_ids)->groupBy('active_id')->join('actives','actives.id','=','scores.active_id')->select('actives.id as id','standard')->get();

        if (count($rankUser)>0) {
            foreach ($actives as $value) {
                $scores = $this->rankPersonal($value);
                foreach ($scores as $k=>$v) {
                    $rank[$v->user_id] += $k+1;
                }
            }
        }
        asort($rank);
        return $rank;
    }

    public function myRank(Request $request)
    {
        $personal = [];
        $team = [];
        $this->getUserInfo($request);

        $actives = SM::where('user_id',$this->user->id)->get();
        if(count($actives)>0){
            $actives = $actives->toArray();
            foreach($actives as $key => $value) {
                $act = AM::where('channel','personal')->where('id',$value['id'])->first(['channel','name']);
                if (count($act)>0) {
                    $obj = $this->lists($request,$value['id'],false);
                    $obj->active_name = $act->name;
                    $personal[] = $obj;
                }
            }
        }

        $myteam = TM::find($this->user->team_id);
        $myteamscore = TSM::where('team_one_id',$this->user->team_id)->orWhere('team_two_id',$this->user->team_id)->get();
        if (count($myteamscore)>0){
            foreach ($myteamscore as $key=>$value) {
                $rank = TAM::where('team_id',$this->user->team_id)->first();
                if ($value->team_one_id != $this->user->team_id) {
                    $team[$key]['opponent_team_name'] = TM::find($value->team_one_id)->name;
                    $team[$key]['my_team_name'] = $myteam->name;
                    $team[$key]['opponent_team_score'] = $value->team_one_score;
                    $team[$key]['my_team_score'] = $value->team_two_score;
                    $team[$key]['my_victory_num'] = $rank->victory;
                    $team[$key]['opponent_victory_num'] = TAM::where('team_id',$value->team_one_id)->first()->victory;
                } else {
                    $team[$key]['opponent_team_name'] = TM::find($value->team_two_id)->name;
                    $team[$key]['my_team_name'] = $myteam->name;
                    $team[$key]['opponent_team_score'] = $value->team_two_score;
                    $team[$key]['my_team_score'] = $value->team_one_score;
                    $team[$key]['my_victory_num'] = $rank->victory;
                    $team[$key]['opponent_victory_num'] = TAM::where('team_id',$value->team_two_id)->first()->victory;
                }
                $team[$key]['start'] = SMM::find($rank->schedule_id)->start;
            }
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>['team'=>$team,'personal'=>$personal]]);
    }

}