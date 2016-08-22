<?php

namespace App\Http\Controllers;

use Input;
use Session;

use App\Models\News as NewsM;


class News extends Controller
{
    public function lists()
    {
        $type = Input::get('type') ? : 'news';
        $data = NewsM::where('type',$type)->orderBy('sort','desc')->orderBy('updated_at','desc')->where('status','2')->paginate('10',['id','title','thumbnail','description','sort','created_at'])->toArray();
        $data = $data['data'];
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }


    public function detail($id)
    {
        $data = NewsM::find($id);
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }
}