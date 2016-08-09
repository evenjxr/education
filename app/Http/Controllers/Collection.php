<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection as CM;
use App\Models\Active as AM;
use DB;

class Collection extends Controller
{
    public  function add(Request $request)
    {
        $this->getUserInfo($request);
        CM::firstOrCreate(['user_id'=>$this->user->id,'object_id'=>$request->get('id'),'moudel'=>$request->get('moudel')]);
        return response()->json(['success' => 'Y','msg' => '收藏成功','data'=>'']);
    }

    public function delete(Request $request)
    {
        $this->getUserInfo($request);
        CM::where('user_id',$this->user->id)
            ->where('object_id',$request->get('id'))
            ->where('moudel',$request->get('moudel'))
            ->delete();
        return response()->json(['success' => 'Y','msg' => '删除成功','data'=>'']);
    }

    public function lists(Request $request)
    {
        $data = [];
        $this->getUserInfo($request);
        $collections = CM::where('user_id',$this->user->id)->orderBy('id' ,'Desc')->get(['id','moudel','object_id']);
        foreach ($collections as $value) {
            if ($value->moudel) {
                switch ($value->moudel) {
                    case 'video':
                        $object = DB::table('videos')->find($value->object_id,['id as object_id','title','thumbnail','description','url','tag']);
                        break;
                    case 'figure':
                        $object = DB::table('figures')->find($value->object_id,['id as object_id','title','thumbnail','description','hits']);
                        break;
                    case 'camp':
                        $object = AM::find($value->object_id,['id as object_id','name','thumbnail','announcement']);
                        break;
                    case 'show':
                        $object = AM::find($value->object_id,['id as object_id','name','thumbnail','announcement']);
                        break;
                    case 'other':
                        $object = AM::find($value->object_id,['id as object_id','name','thumbnail','announcement']);
                        break;
                }
                if (isset($object)) {
                    $object->id = $value->id;
                    $object->channel = $value->moudel;
                    $data[] = $object;
                }
            }
        }
        return response()->json(['success' => 'Y','msg' => '','data'=>$data]);
    }

}
