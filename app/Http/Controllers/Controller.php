<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

use App\Models\City as CM;
use App\Models\Collection as COM;
use App\Models\Role as RM;
use App\Models\User as UM;
use App\Models\LoginToken as LTM;
use App\Models\Tournament as TM;


class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    public $user;
    public $city_id;
    public $personal_tournament;
    public $team_tournament;
    public $collection;
    public $can=true;
    

    public function addCity($lists)
    {
    	$cityArr = CM::lists('name','id')->toArray();
    	$roleArr = RM::lists('name','id')->toArray();

    	 foreach ($lists as $key => $value) {
            $lists[$key]['city'] = isset($cityArr[$value->city_id]) ? $cityArr[$value->city_id] : '城市已删除';
           	$lists[$key]['role'] = isset($roleArr[$value->role_id]) ? $roleArr[$value->role_id] : '角色已删除';
        }
        return $lists;
    }
    
    public function getUserInfo ($request)
    {
        $token = $request->header('token');
        if ($token) {
            $user_id = LTM::where('token',$token)->lists('user_id');
            if (count($user_id)<1)
                die(response()->json(['success' => 'N','msg' => 'token已失效请从新登录']));
            $this->user = UM::find($user_id);
            $this->getCityId($request);
            $this->getMyCollection();
            $this->getMyTournament();
        } else {
             exit(response()->json(['success' => 'N','msg' => '请先登录']));
        }
    }

    private function getCityId($request)
    {
        $city_id = $request->header('cityId');
        $this->city_id = $city_id ? : env('CITY_ID');
    }

    private function getMyCollection()
    {
        $collection = COM::where('user_id',$this->user->id)->lists('moudel','object_id');
        if(empty($collection)){
            $collection=[];
        }else{
            $collection=$collection->toArray();
        }
        $this->collection = $collection;
    }


    private function getMyTournament()
    {
        $this->personal_tournament = TM::where('user_id',$this->user->id)->get();
        $this->user->team_id ? $this->team_tournament = TM::where('team_id',$this->user->team_id)->get() : [];

        $tournaments = TM::where('user_id',$this->user->id)
                        ->orWhere('team_id',$this->user->team_id)
                        ->join('schedules','schedules.id','=','tournaments.schedule_id')
                        ->select('end')
                        ->get();
        foreach ($tournaments as $key=>$value) {
            if (strtotime($value->end)+24*60*60 > time()) {
                $this->can = false;
                break;
            }
        }
    }
}
