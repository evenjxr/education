<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Input;
use Session;

use App\Models\Library as LM;
use App\Models\Moudel as MM;

class library extends Controller
{
    public function picLists()
    {
         $params = Input::all();
         $piclists = new LM();
//         if (Session::get('admin.city_id'))
//             $piclists = $piclists->where('city_id',Session::get('admin.city_id'));

         if ( isset($params['start']) && $params['start'] )
             $piclists = $piclists->where('created_at','>=',$params['start']);

         if ( isset($params['end']) && $params['end'] )
             $piclists = $piclists->where('created_at','<=',$params['end']) ;

         if ( isset($params['group_name']) && $params['group_name'])
             $piclists = $piclists->where('group_name',$params['group_name']);
        $piclists = $piclists->where('type','IMG')->groupBy('group_name')->get();
        $moudels = MM::lists('name','id');
        foreach($piclists as $key=>$value){
            $piclists[$key]['moudel_name'] = isset($moudels[$value->moudel_id]) ? $moudels[$value->moudel_id] : '其他';
        }
    	return view('library.piclists')->with('piclists',$piclists);
    }

    public function videoLists()
    {
        $params = Input::all();
        $videolists = new LM();
//         if (Session::get('admin.city_id'))
//             $piclists = $piclists->where('city_id',Session::get('admin.city_id'));

        if ( isset($params['start']) && $params['start'] )
            $videolists = $videolists->where('created_at','>=',$params['start']);

        if ( isset($params['end']) && $params['end'] )
            $videolists = $videolists->where('created_at','<=',$params['end']) ;

        $videolists = $videolists->where('type','VIDEO')->get();
 
        return view('library.videolists')->with('videos',$videolists);
    }

    public function picSearch()
    {
        $params = Input::all();
        $piclists = new LM();
        if ( isset($params['moudel_id']) && $params['moudel_id'] )
            $piclists = $piclists->where('moudel_id',$params['moudel_id']);

        if ( isset($params['start']) && $params['start'] )
            $piclists = $piclists->where('created_at','>=',$params['start']);

        if ( isset($params['end']) && $params['end'] )
            $piclists = $piclists->where('created_at','<=',$params['end']) ;

        if ( isset($params['group_name']) && $params['group_name'])
            $piclists = $piclists->where('group_name',$params['group_name']);
        $piclists = $piclists->where('type','IMG')->groupBy('group_name')->get();
        $moudels = MM::lists('name','id');
        return view('library.picsearch',['piclists'=>$piclists,'moudels'=>$moudels]);
    }

    public function videoSearchLists()
    {
        $params = Input::all();
        $videolists = new LM();
//         if (Session::get('admin.city_id'))
//             $piclists = $piclists->where('city_id',Session::get('admin.city_id'));

        if ( isset($params['start']) && $params['start'] )
            $videolists = $videolists->where('created_at','>=',$params['start']);

        if ( isset($params['end']) && $params['end'] )
            $videolists = $videolists->where('created_at','<=',$params['end']) ;

        $videolists = $videolists->where('type','VIDEO')->get();

        return view('library.videosearch')->with('videos',$videolists);
    }

    public function picAdd()
    {
        $moudels = MM::where('name','like','%版块')->lists('name','id');
    	return view('library.picadd')->with('moudels',$moudels);
    }

    public function videoAdd()
    {
        return view('library.videoadd');
    }

    public function picStore()
    {
    	$params = Input::all();
        if ($params['pic']) {
            foreach ($params['pic'] as $key => $value) {
                LM::create(['moudel_id'=>$params['moudel_id'],'url'=>$value,'type'=>'IMG','group_name'=>$params['group_name']]);
            }
            return redirect()->back()->with(['success'=>'你还没有添加图片']);
        } else {
            return redirect()->back()->withErrors(['你还没有添加图片']);
        }
    }

    public function videoStore()
    {
        $name = Input::get('name');
        $upload = new Common();
        $url = $upload->uploadvideo(Input::file('video'));
        if ($name&&$url) {
           $flag = LM::create(['url'=>$url,'type'=>'VIDEO','name'=>$name]);
            if ($flag) return $this->videoLists()->with('success', '新增成功');
        } else {
            return redirect()->back()->withErrors(['你还没有添加图片']);
        }
    }

    public function picDetail($group_name)
    {   
        $pictures = LM::where('group_name',$group_name)->get();
        return view('library.picdetail')->with('pictures',$pictures);
    }

    public function picSearchDetail($group_name)
    {
        $pictures = LM::where('group_name',$group_name)->get();
        return view('library.picsearchdetail')->with('pictures',$pictures);
    }

    public function update()
    {
        $params = Input::all();
        $video = VM::find($params['id']);
        unset($params['file']);
        $video->update($params);
        return redirect('manage/video/detail/'.$video->id);
    }

    public function picDelete()
    {
       $ids = Input::all();
       if (is_array($ids['id'])) {
            foreach ($ids['id'] as $key => $value) {
                $flag = LM::find($value)->delete();
            }
        } else {
                LM::find($ids)->delete();
        }
        return redirect()->back();
    }
    public function videoDelete()
    {
        $ids = Input::all();
        if (is_array($ids['id'])) {
            foreach ($ids['id'] as $key => $value) {
               LM::find($value)->delete();
            }
        } else {
            LM::find($ids['id'])->delete();
        }
        return redirect()->back();
    }

    public function picDeleteGroup()
    {
        $flag = LM::where('group_name',Input::get('group_name'))->delete();
        if ($flag) {
            return 1;
        } else {
            return 0;
        }

    }

    public function picEditGroup()
    {
        $group_name = Input::all();
        $pictures = LM::where('group_name',$group_name)->get();
        $moudels = MM::where('name','like','%版块')->lists('name','id');
        return view('library.picedit')->with(['pictures'=>$pictures,'moudels'=>$moudels]);
    }


}