<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Validator;

use App\Models\Server as ServerM;
use App\Models\Teacher as TeacherM;
use App\Models\Equipment as EquipmentM;



class Order extends Controller
{

    public function serverList(Request $request)
    {
        $this->userInfo($request);
        $user_id = $this->userInfo->id;
        if ($this->type == 'student') {
            $servers = ServerM::where('user_id',$user_id);
        } else if($this->type == 'manage') {
            $servers = ServerM::where('manage_id',$user_id);
        }
        $servers = $servers->get(['id','created_at','total_fee','status']);
        foreach ($servers as $key => $value){
            $value->status && $servers[$key]->status = $this->orderStatus[$value->status];
        }
        return response()->json(['success'=>'Y','msg'=>'','data'=>$servers]);
    }

    public function serverDetail(Request $request)
    {
        $this->userInfo($request);
        $id = Input::get('id');
        $server = ServerM::find($id);
        return response()->json(['success'=>'Y','msg'=>'','data'=>$server]);
    }

    public function serverAuth(Request $request)
    {
        $this->userInfo($request);
        $param = Input::all();
        $flag = ServerM::find($param['id'])->update(['status'=>$param['status']]);
        if ($flag) {
            return response()->json(['success'=>'Y','msg'=>'修改成功']);
        } else {
            return response()->json(['success'=>'N','msg'=>'修改失败']);
        }
    }



    public function equipmentList(Request $request)
    {
        $this->userInfo($request);
        $user_address_id = $this->userInfo->address_id;
        $equipment = EquipmentM::where('address_id',$user_address_id)
            ->get(['id','created_at','link_name','status','mobile','sn']);
        foreach ($equipment as $key => $value){
            $value->status && $equipment[$key]->status = $this->orderStatus[$value->status];
        }
        return response()->json(['success'=>'Y','msg'=>'','data'=>$equipment]);
    }

    public function equipmentDetail(Request $request)
    {
        $this->userInfo($request);
        $id = Input::all('id');
        $equipment = EquipmentM::find($id);
        return response()->json(['success'=>'Y','msg'=>'','data'=>$equipment]);
    }

    public function equipmentAuth(Request $request)
    {
        $this->userInfo($request);
        $param = Input::all();
        $flag = EquipmentM::find($param['id'])->update(['status'=>$param['status']]);
        if ($flag) {
            return response()->json(['success'=>'Y','msg'=>'修改成功']);
        } else {
            return response()->json(['success'=>'N','msg'=>'修改失败']);
        }
    }



    public function serverAdd(Request $request)
    {
        $this->userInfo($request);
        $this->validateServer($request);
        $param = Input::all();
        $param['user_id'] = $this->userInfo->id;
        $flag = ServerM::firstOrCreate($param);
        if ($flag) {
            return response()->json(['success'=>'Y','msg'=>'下单成功']);
        } else {
            return response()->json(['success'=>'N','msg'=>'下单失败']);
        }
    }

    public function equipmentAdd(Request $request)
    {
        $this->userInfo($request);
        $this->validateEquipment($request);
        $param = Input::all();
        $param['sn'] = $this->build_order_no();
        $param['user_id'] = $this->userInfo->id;
        unset($param['city']); unset($param['province']);
        $flag = EquipmentM::firstOrCreate($param);
        if ($flag) {
            return response()->json(['success'=>'Y','msg'=>'预约成功']);
        } else {
            return response()->json(['success'=>'N','msg'=>'预约失败']);
        }
    }


    private function build_order_no(){
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }



    public function hasEquipment(Request $request)
    {
        $this->userInfo($request);
        $user_id = $this->userInfo->id;
        $flag = EquipmentM::where('user_id',$user_id)->first();
        if ($flag) {
            return response()->json(['success'=>'Y','msg'=>'已经预约过']);
        } else {
            return response()->json(['success'=>'N','msg'=>'还未预约过']);
        }
    }

    public function teacherFee(Request $request)
    {
        $this->userInfo($request);
        $mobile = Input::get('mobile');
        $teacher_id = EquipmentM::where('mobile',$mobile)->first()['teacher_id'];
        $teacher = TeacherM::find($teacher_id,['extra_server_fee']);
        if ($teacher_id && $teacher) {
            return response()->json(['success'=>'Y','msg'=>'','data'=>$teacher]);
        } else {
            return response()->json(['success'=>'N','msg'=>'未找到指定老师']);
        }
    }

    public function serverFee(Request $request)
    {
        $this->userInfo($request);
        return response()->json(['success'=>'Y','msg'=>'','data'=>$this->fee]);
    }


    private function validateServer($request)
    {
        $this->validate($request, [
            'equipment_status' => 'required|numeric',
            'homework_server' => 'required|numeric',
            'prepare_server' => 'required|numeric',
            'extra_server' => 'required|numeric',
            'total_fee' => 'required|numeric',
            'status' => 'required|in:1,2,3,4',
            'mobile' => 'required|digits:11'
        ], [
            'equipment_status.required' => '请核实设备数量',
            'homework_server.required' => '请核实作业辅导服务',
            'prepare_server.required' => '请核实预习复习服务',
            'extra_server.required' => '请核实补习服务',
            'total_fee.required' => '请核实总费用',
            'status.required' => '订单状态不得为空',
            'status.in' => '订单参数有误',
            'mobile.required' => '手机号不得为空',
            'mobile.digits' => '手机号格式不正确'
        ]);
    }

    private function validateEquipment($request)
    {
        $this->validate($request, [
            'member_type' => 'required|in:teacher,student',
            'time' => 'required',
            'address_id' => 'required|numeric',
            'address_detail' => 'required|min:5',
            'link_name' => 'required|min:2',
            'recommend_type' => 'required|in:1,2',
            'mobile' => 'required|digits:11'
        ], [
            'member_type.required' => '账号类型不得为空',
            'member_type.in' => '账号类型不正确',
            'time.required' => '安装时间不得为空',
            'address_id.required' => '请核实地址',
            'address_detail.required' => '请核实详细地址',
            'link_name.required' => '请核实联系人姓名',
            'recommend_type.in' => '请核实推荐类型',
            'mobile.required' => '手机号不得为空',
            'mobile.digits' => '手机号格式不正确'
        ]);
    }

    public function lists(Request $request)
    {

        $this->userInfo($request);
        if ($this->type == 'teacher') {

        }

//        switch ($this->type) {
//            case 'teacher':
//                StudentM::where('teacher_id','')->find();
//                ServerM::where('mobile',$this->userInfo->mobile)->get(['']);
//                break;
//            case 'student':
//
//                break;
//            case 'institution':
//
//                break;
//            default:
//                $lists = ServerM::where()
//                break;
//        }
    }

}