<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Video as VM;

class Video extends Controller
{
    public function lists(Request $request)
    {
        $this->getUserInfo($request);
        $data = VM::where('city_id',$this->city_id)->where('status','2')->orderBy('sort','desc')->orderBy('updated_at','desc')->paginate('10',['id','sort','title','tag','thumbnail','url','updated_at'])->toArray();
        $data = $data['data'];
        if($data){
            foreach ($data as $key=>$value){
                if ( date('Y-m-d',time()) <= $value['updated_at']) {
                    $data[$key]['updated_at'] = '今天';
                } elseif (date('Y-m-d',strtotime("1 days ago")) <= $value['updated_at']) {
                    $data[$key]['updated_at'] = '昨天';
                }
                if(in_array($value['id'],array_keys($this->collection,'video'))) {
                    $data[$key]['flag'] = 'has';
                } else {
                    $data[$key]['flag'] = 'no';
                }
            }
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

}