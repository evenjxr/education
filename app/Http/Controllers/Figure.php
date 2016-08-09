<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Figure as FM;


class Figure extends Controller
{
    public function lists(Request $request)
    {
        $this->getUserInfo($request);
        $data = FM::where('city_id',$this->city_id)->where('status','2')->orderBy('sort','desc')->orderBy('updated_at','desc')->paginate('10',['id','title','thumbnail','hits','sort','updated_at'])->toArray();
        $data = $data['data'];
        foreach ( $data as $key=>$value ){
            if(in_array($value['id'],array_keys($this->collection,'figure'))) {
                $data[$key]['flag'] = 'has';
            } else {
                $data[$key]['flag'] = 'no';
            }
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

    public function detail(Request $request,$id)
    {
        $this->getUserInfo($request);
        $data = FM::find($id);
        if($data) {
            if( in_array($id,array_keys($this->collection,'figure')) ){
                $data->flag = 'has';
            } else {
                $data->flag = 'no';
            }
            $this->update($id);
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);

    }

    private function update($id)
    {
        $figure = FM::find($id);
        $figure->update(['hits' => ($figure->hits)+1]);
    }

}