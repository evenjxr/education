<?php

namespace App\Http\Controllers;

use Input;
use Session;
use Illuminate\Http\Request;

use App\Models\News as NM;


class News extends Controller
{
    public function lists(Request $request)
    {
        $this->getUserInfo($request);
        $data = NM::where('city_id',$this->city_id)->orderBy('sort','desc')->orderBy('updated_at')->where('status','2')->paginate('10',['id','title','keyword','source','thumbnail','hits','sort','updated_at','created_at'])->toArray();
        $data = $data['data'];
        foreach ($data as $key=>$value) {
            $data[$key]['created_at'] = date('Y-m-d',strtotime($value['created_at']));
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }


    public function detail($id)
    {
        $data = NM::find($id);
        if($data) {
            $this->update($id);  
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

    private function update($id)
    {
        $news = NM::find($id);
        $news->update(['hits' => ($news->hits)+1]);
    }

}