<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;

use App\Models\Server as ServerM;
use App\Models\Teacher as TeacherM;
use App\Models\Manage as ManageM;
use App\Models\Equipment as EquipmentM;

class Server extends Controller
{

    public function lists()
    {
        $params = Input::all();
        $server = new ServerM();

        if ( isset($params['mobile']) && $params['mobile'] )
            $server = $server->where('mobile',$params['mobile']);
        if ( isset($params['start']) && $params['start'] ) 
            $server = $server->where('created_at','>=',$params['start']);
        if ( isset($params['end']) && $params['end'] ) 
            $server = $server->where('created_at','<=',$params['end']) ;
        if ( isset($params['status'])&&$params['status']!='' )
            $server = $server->where('status',$params['status']);
        $lists = $server->get();
        foreach ($lists as $key => $value){
            $lists[$key]->manage_name = ManageM::find($value->manage_id)['truename'];
            $teacher = EquipmentM::where('equipment.mobile',$value->mobile)
                ->leftjoin('teachers','teachers.id','=','equipment.teacher_id')
                ->select('truename','teachers.id','equipment.teacher_id','teacher_id')->get()[0]->truename;
            $lists[$key]->teacher_name = $teacher;
        }
        $amounts = ServerM::all(['total_fee','status']);
        $total = $has_pay = 0;
        foreach ($amounts as $key=>$value) {
            if ($value->status == 2) {
                $has_pay += $value->total_fee;
            }
            $total += $value->total_fee;
        }
        return view('server.lists',['lists'=>$lists,'total'=>$total,'has_pay'=>$has_pay]);
    }

    public function add()
    {
     //    $channel = AM::channel();
        // return view('server.add')->with(['channel'=>$channel,'standard'=>$this->standard]);
    }

    public function store()
    {
        // $params = Input::all();
     //    $flag = AM::create($params);
     //    if ($flag) return $this->detail($flag->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        // $server = AM::find($id);
        // return view('server.detail',['server'=>$server,'channel'=>AM::channel(),'standard'=>$this->standard]);
    }

    public function update()
    {
        // $params = Input::all();
        // $server = AM::find($params['id']);
        // $server->update($params);
        // if ($server) return $this->detail($server->id)->with('success', '修改成功');
    }

    public function auth()
    {
        $params = Input::all();
        $server = serverM::find($params['id']);
        if ( $server->update(['status'=>$params['status']]) ) {
            return 1;
        }
        return 0;
    }

    public function search()
    {
        // $params = Input::all();
        // $server = new AM();
        // if ( isset($params['keyword']) && $params['keyword'] ) {
        //     $server = $server->where('name','like','%'.$params['keyword'].'%');
        // }
        // if ( isset($params['start']) && $params['start'] ) 
        //     $server = $server->where('created_at','>=',$params['start']);

        // if ( isset($params['end']) && $params['end'] ) 
        //     $server = $server->where('created_at','<=',$params['end']) ;

        // $lists = $server->where('status',2)->get();
        // return view('server.search',['lists'=>$lists,'channel'=>AM::channel()]);
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
        // return redirect('manage/server/lists/');
    }



}