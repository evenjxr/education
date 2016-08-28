<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;
use DB;
use Illuminate\Support\Facades\URL;
use App\Extra\SMS;


use App\Models\Student as Student_M;

class schedule extends Controller
{
    public function lists()
    {
        $params = Input::all();
        $schedule = new SM();
        if (Session::get('admin.city_id'))
            $schedule = $schedule->where('city_id',Session::get('admin.city_id'));

        if ( isset($params['keyword']) && $params['keyword'] ) {
            $schedule = $schedule->where('title','like','%'.$params['keyword'].'%');
        }
        if ( isset($params['start']) && $params['start'] ) 
            $schedule = $schedule->where('start','>=',$params['start']);

        if ( isset($params['end']) && $params['end'] )
            $schedule = $schedule->where('end','<=',$params['end']) ;
        
        if ( isset($params['status'])&&$params['status']!='' ) 
                $schedule = $schedule->where('status',$params['status']);

    	return view('schedule.lists',['lists'=>$schedule->get(),'grade'=>$this->grade]);
    }

    public function add()
    {
    	return view('schedule.add',['channel'=>SM::channel()]);
    }

    public function store()
    {
    	$params = Input::all();
        if ($params['channel'] !='other') {
            if (!isset($params['active_id']))
                return redirect()->back()->withErrors(['请选择相应活动']);
            $active_id = $params['active_id']; unset($params['active_id']);
            $schedule = SM::create($params);
            foreach ($active_id  as $key => $value) {
                ASM::create([
                    'active_id' => $active_id[$key],
                    'schedule_id' => $schedule->id,
                ]);
            }
        } else {
            $schedule = SM::create($params);
        }
        if ($schedule) return $this->detail($schedule->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        $schedules = SM::find($id);
        $actives = ASM::where('schedule_id',$id)->get();
        return view('schedule.detail',['schedules'=>$schedules,'actives'=>$actives,'channel'=>SM::channel()]);
    }

    public function update()
    {
        $actives = [];
        $params = Input::all();
        $schedule = SM::find($params['id']);
        if ($params['channel'] !='other') {
            if (!isset($params['active_id']))
                return redirect()->back()->withErrors(['请选择相应活动']);
            ASM::where('schedule_id',$params['id'])->delete();
            $active_id = $params['active_id']; unset($params['active_id']);
            foreach ($active_id as $key => $value) {
                ASM::firstOrCreate([
                    'active_id' => $active_id[$key],
                    'schedule_id' => $params['id']
                ]);
            }
            $actives = ASM::where('schedule_id',$params['id'])->get();
            unset($params['active_id']);
        }
        $schedule->update($params);
        $schedules = SM::find($params['id']);
        return view('schedule.detail',['schedules'=>$schedules,'actives'=>$actives,'success'=>'修改成功','channel'=>SM::channel()]);
    }

    public function auth()
    {
        $params = Input::all();
        $schedule = SM::find($params['id']);
        if ( $schedule->update(['status'=>$params['status']]) ) {
            return 1;
        }
        return 0;
    }

    public function delete()
    {
        $ids = Input::all();
        if (is_array($ids)) {
            foreach ($ids as $key => $value) {
                SM::find($value)->delete();
            }
        } else {
            SM::find($ids)->delete();
        }
        return redirect('manage/schedule/lists/');
    }

//    public function signUp()
//    {
//        $params = Input::all();
//        $schedules = new SM();
//        $schedules = $schedules->where('status',2)->where('city_id',Session::get('admin.city_id'));
//        if (isset($params['start']) && $params['start'])
//            $schedules = $schedules->where('start','like',$params['start'].'%');
//        if (isset($params['title']) && $params['title'])
//            $schedules = $schedules->where('title','like','%'.$params['title'].'%');
//
//        $schedules = $schedules->get(['id','title','start','sign_num','max_num','channel']);
//        foreach ($schedules as $key => $value) {
//            $actives = DB::table('active_schedules')
//                ->leftjoin('actives', 'actives.id', '=', 'active_schedules.active_id')
//                ->where('active_schedules.schedule_id','=',$value['id'])
//                ->whereNull('active_schedules.deleted_at')
//                ->whereNull('actives.deleted_at')
//                ->select('actives.id','actives.name','actives.channel')
//                ->get();
//            $schedules[$key]['actives'] = $actives;
//        }
//        return view('schedule.signup',['schedules'=>$schedules]);
//    }

    //个人报名
    public function signUp()
    {
        $params = Input::all();
        $schedule = SM::find($params['id'],['id','title','start','sign_num','max_num','channel']);
        $actives = DB::table('active_schedules')
                ->leftjoin('actives', 'actives.id', '=', 'active_schedules.active_id')
                ->where('active_schedules.schedule_id','=',$params['id'])
                ->whereNull('active_schedules.deleted_at')
                ->whereNull('actives.deleted_at')
                ->select('actives.id','actives.name')
                ->get();
        $schedule->actives = $actives;
        return view('schedule.sign',['schedule'=>$schedule]);
    }

    //个人报名
    public  function doSignUp()
    {
        $params = Input::all();
        $schedule= SM::find($params['id'],['id','max_num','sign_num']);
        if ($schedule->max_num <= $schedule->sign_num)
            return redirect()->back()->withErrors(['msg' => '你选择的赛程已满']);
        $schedule->increment('sign_num');
        $data = [
            'user_id' => $params['user_id'],
            'schedule_id' => $params['id'],
            'actives' => json_encode($params['active']),
            'channel' => 'personal'
        ];
        $flag = TM::firstOrCreate($data);
        if ($flag) return redirect(URL::route('manage.invite.personaldetail',['id'=>$params['id']]));
    }

    //个人删除报名
    public function delSignUp()
    {
        $params = Input::all();
        TM::where(['schedule_id'=>$params['schedule_id'],'user_id'=>$params['user_id']])->delete();
        SSM::where(['schedule_id'=>$params['schedule_id'],'user_id'=>$params['user_id']])->delete();
        SM::where('id',$params['schedule_id'])->decrement('sign_num');
        return redirect(URL::route('manage.invite.personaldetail',['id'=>$params['schedule_id']]));
    }

//    public function signUpUpdate()
//    {
//        $params = Input::all();
//        if (!isset($params['schedule_id']))
//            return redirect()->back()->withErrors(['请选择报名赛程']);
//
//        if ($params['channel'] == 'personal') {
//            if (empty($params['user_id']))
//                return redirect()->back()->withErrors(['请选择参赛会员']);
//        } else {
//            if (empty($params['team_id']))
//                return redirect()->back()->withErrors(['请选择参赛团队']);
//        }
//        $tournament = TM::find($params['id']);
//        if($tournament->schedule_id != $params['schedule_id']){
//            $schedul= SM::find($params['schedule_id'],['id','max_num','sign_num']);
//            if($schedul->max_num <= $schedul->sign_num)
//                return redirect()->back()->withErrors(['msg' => '你选择的赛程已满']);
//
//            SM::where('id',$tournament->schedule_id)->decrement('sign_num');
//            SM::where('id',$params['schedule_id'])->increment('sign_num');
//        }
//        $data = [
//            'user_id' => $params['user_id'],
//            'team_id' => $params['team_id'],
//            'schedule_id' => $params['schedule_id'],
//            'actives' => json_encode($params['active'])
//        ];
//        TM::find($params['id'])->update($data);
//        return $this->signUpDetail($params['id'])->with('success', '修改成功');
//    }


    public function signList()
    {
        $tournaments = DB::table('schedules')
            ->rightjoin('tournaments', 'tournaments.schedule_id', '=', 'schedules.id')
            ->where('schedules.city_id','=',Session::get('admin.city_id'))
            ->where('schedules.status','=',2)
            ->whereNull('schedules.deleted_at')
            ->whereNull('tournaments.deleted_at')
            ->select('schedules.city_id','schedules.id as schedules_id','schedules.status','schedules.title','tournaments.id','tournaments.user_id','tournaments.team_id','tournaments.actives','tournaments.created_at')
            ->get();
        foreach ($tournaments as $key=>$value) {
            if($value->user_id > 0) {
                $tournaments[$key]->players = UM::where('id',$value->user_id)->get(['id','name'])->toArray();
            }
            if($value->team_id > 0) {
                $team = TMM::find($value->team_id);
                $tournaments[$key]->team_name = $team->name;
                $tournaments[$key]->players = UM::whereIn('id',[
                    $team->player_one_id,
                    $team->player_two_id,
                    $team->player_three_id,
                    $team->player_four_id
                ])->get(['id','name']);
            }
            $tournaments[$key]->actives = json_decode($value->actives,true);
        }
        return view('schedule.signlists',['lists'=>$tournaments]);
    }

    public function signUpDetail($id)
    {
        $tournament = TM::find($id);
        if($tournament->user_id>0){
            $tournament->user_name = UM::find($tournament->user_id)->name;
        }
        if($tournament->team_id>0){
            $tournament->team_name = TMM::find($tournament->team_id)->name;
        }
        $tournament->actives = json_decode($tournament->actives,true);
        $tournament->channel = SM::find($tournament->schedule_id)->channel;
        $schedules = SM::get(['id','title','start','channel']);
        foreach ($schedules as $key => $value) {
            $schedules[$key]['actives'] = DB::table('active_schedules')
                ->leftjoin('actives', 'actives.id', '=', 'active_schedules.active_id')
                ->where('active_schedules.schedule_id', '=', $value['id'])
                ->whereNull('active_schedules.deleted_at')
                ->whereNull('actives.deleted_at')
                ->select('actives.id', 'actives.name')
                ->get();
        }
        if(!$tournament->actives){
            $tournament->actives = [];
        }
        return view('schedule.signupdetail',['schedules'=>$schedules,'tournament'=>$tournament]);
    }


    public function sendSms()
    {
        $id = Input::get('id');
        $schedule = SM::find($id);
        if ($schedule->channel == 'personal') {
            $tournaments = TM::where('schedule_id',$id)
                ->join('users','users.id','=','tournaments.user_id')
                ->join('schedules','schedules.id','=','tournaments.schedule_id')
                ->select('schedules.start','users.mobile','schedules.channel')
                ->get();
            foreach ($tournaments as $key=>$value) {
                $mobile = SMS::sendTime(SM::SMS_SCHEDULE_CHECK, $value->mobile, $value->start);
            }
        } else if ($schedule->channel == 'team') {
            $tournaments = TM::where('schedule_id',$id)->lists('team_id');
            foreach ($tournaments as $key=>$value) {
                $team = TMM::find($value);

                if (isset($team->player_one_id)) {
                    $user = UM::find($team->player_one_id);
                    $mobile = SMS::sendTime(SM::SMS_SCHEDULE_CHECK, $user->mobile, $schedule->start);
                }
                if (isset($team->player_two_id)) {
                    $user = UM::find($team->player_two_id);
                    $mobile = SMS::sendTime(SM::SMS_SCHEDULE_CHECK, $user->mobile, $schedule->start);
                }
                if (isset($team->player_three_id)) {
                    $user = UM::find($team->player_three_id);
                    $mobile = SMS::sendTime(SM::SMS_SCHEDULE_CHECK, $user->mobile, $schedule->start);
                }
                if (isset($team->player_four_id)) {
                    $user = UM::find($team->player_four_id);
                    $mobile = SMS::sendTime(SM::SMS_SCHEDULE_CHECK, $user->mobile, $schedule->start);
                }
            }
        }

        return $this->lists()->with('success', '发送成功');
    }

}