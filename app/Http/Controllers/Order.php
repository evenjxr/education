<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Validator;

use App\Models\Server as ServerM;


class Order extends Controller
{
    public function serverAdd(Request $request)
    {
        $this->userInfo($request);
        $this->validateServer($request);
        $param = Input::all();
        dd($param);
    }

    public function equipmentAdd(Request $request)
    {

    }

    private function validateServer($request)
    {
        $this->validate($request, [
            'equipment_status' => 'required|numeric',
            'homework_server' => 'required|numeric',
            'prepare_server' => 'required|numeric',
            'extra_server' => 'required|numeric',
            'total_fee' => 'required|numeric',
        ], [
            'equipment_status.required' => '请核实设备数量',
            'homework_server.required' => '请核实作业辅导服务',
            'prepare_server.required' => '请核实预习复习服务',
            'extra_server.required' => '请核实补习服务',
            'total_fee.required' => '请核实总费用',
        ]);
    }

}