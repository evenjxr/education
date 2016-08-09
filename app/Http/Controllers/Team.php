<?php

namespace App\Http\Controllers;

use Input;
use Illuminate\Http\Request;
use Validator;
use App\Models\User as UM;
use App\Models\Team as TM;

class Team extends Controller
{
    public function store(Request $request)
    {
        $this->getUserInfo($request);
        $v = $this->myValidate($request);
        if ($v->fails())
            return response()->json(['success' => 'N','msg' => $v->errors()->toArray()]);
        $team = TM::find($this->user->team_id);
        if (count($team))
            return response()->json(['success' => 'N','msg' => '你已经添加过团队']);

        $team = TM::firstOrCreate(['name'=>$request->name,'player_one_id'=>$this->user->id,'city_id'=>$this->city_id]);
        $this->user->update(['team_id'=>$team->id]);
        if ($team) return response()->json(['success' => 'Y','msg' => '新建成功','data'=>$team]);
    }

    public function detail(Request $request,$id=null)
    {
        $this->getUserInfo($request);
        if ($id) {
            $team = TM::find($id);
        } else {
            $team = TM::find($this->user->team_id);
        }
        if ($team) {
            $player_one = UM::find($team->player_one_id,['id','name','mobile','idcard']);
            $player_two = UM::find($team->player_two_id,['id','name','mobile','idcard']);
            $player_three = UM::find($team->player_three_id,['id','name','mobile','idcard']);
            $player_four = UM::find($team->player_four_id,['id','name','mobile','idcard']);
            $team = [
                'name' => $team->name,
                'team_id' => $team->id,
                'player_one'=>$player_one,
                'player_two'=>$player_two,
                'player_three'=>$player_three,
                'player_four'=>$player_four
            ];
            return response()->json(['success' => 'Y','msg' => '','data'=>$team]);
        } else {
            return response()->json(['success' => 'N','msg' => '你还没有组队']);
        }
    }

    public function update(Request $request)
    {
        $this->getUserInfo($request);
        if(count($this->team_tournament)) {
            return response()->json(['success' => 'N','msg' => '你已经报名不得删除团队']);
        }
        $v = $this->myValidate($request);
        if ($v->fails())
            return response()->json(['success' => 'N','msg' => $v->errors()->toArray()]);
        $team = TM::find($this->user->team_id);
        $team->update(['name'=>$request->all()['name']]);
        return response()->json(['success' => 'Y','msg' => '修改成功']);
    }

    public function delete(Request $request)
    {
        $this->getUserInfo($request);
        $team = TM::find($this->user->team_id);
        
        if ($team->player_one_id != $this->user->id) {
            return response()->json(['success' => 'N','msg' => '只有团队创建者能删除团队']);
        }

        if ( $team->player_two_id ||$team->player_three_id||$team->player_four_id) {
            return response()->json(['success' => 'N','msg' => '请先删除队员']);
        }

        $this->user->update(['team_id'=>'']);
        $team->delete();
        return response()->json(['success' => 'Y','msg' => '删除成功']);
    }



    public function deletePlayer(Request $request)
    {
        $this->getUserInfo($request);
        $params = $request->all();
        $team = TM::find($this->user->team_id);
        if (!$team)
            return response()->json(['success' => 'N','msg' => '你还没有团队']);
        if (count($this->team_tournament))
            return response()->json(['success' => 'N','msg' => '报名之后不能删除队员']);
        
        $allow_id = [$this->user->id,$team->player_one_id];

        if (isset($params['player_two_id']) && in_array($params['player_two_id'],$allow_id)) {
            UM::find($params['player_two_id'])->update(['team_id'=>'']);
            $team->update(['player_two_id'=>'']);
        } elseif(isset($params['player_three_id']) && in_array($params['player_three_id'],$allow_id)){
            UM::find($params['player_three_id'])->update(['team_id'=>'']);
            $team->update(['player_three_id'=>'']);
        } elseif(isset($params['player_four_id']) && in_array($params['player_three_id'],$allow_id)){
            UM::find($params['player_four_id'])->update(['team_id'=>'']);
            $team->update(['player_four_id'=>'']);
        }
        return response()->json(['success' => 'Y','msg' => '删除成功']);
    }

    public function addPlayer(Request $request)
    {
        $this->getUserInfo($request);
        $this->playerValidate($request);
        if (!isset($request->all()['team_id']))
            return response()->json(['success' => 'N','msg' => 'team_id不得为空']);
        $team = TM::find($request->all()['team_id']);

        if ($this->user->team_id == 0 || empty($this->user->team_id)) {
            return response()->json(['success' => 'N','msg' => '你已经又团队信息']);
        }
        if (in_array($this->user->id,[$team->player_one_id,$team->player_two_id,$team->player_three_id,$team->player_four_id])) {
            return response()->json(['success' => 'N','msg' => '不能重复添加']);
        }
        if (empty($team->player_two_id)) {
            $team->update(['player_two_id'=>$this->user->id]);
        } elseif(empty($team->player_three_id)){
            $team->update(['player_three_id'=>$this->user->id]);
        } elseif(empty($team->player_four_id)){
            $team->update(['player_four_id'=>$this->user->id]);
        } else {
            return response()->json(['success' => 'N','msg' => '团队已满']);
        }
        $this->user->update(['team_id'=>$team->id]);
        return response()->json(['success' => 'Y','msg' => '添加成功']);
    }

    private function playerValidate($request)
    {
        return  Validator::make($request->all(), [
            'team_id' =>'required|numeric',
            'user_id' =>'required'
        ],
            [
                'team_id.required' => '团队名称id',
                'team_id.numeric' => '团队id格式不正确',
                'user_id.required' => '用户id不得为空'
            ]);
    }

    /**
     * 私有验证
     * @param  [type]
     * @return [type]
     */
    private function myValidate($request)
    {
        return  Validator::make($request->all(), [
            'name' =>'required|between:2,10',
        ],
            [
                'name.required' => '团队名称不得为空',
                'name.between' => '团队名称必须在2到10位之间',
            ]);
    }

}