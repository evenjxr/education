<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;

use App\Models\News as NM;


class News extends Controller
{

    public $type = [
        'news' => '资讯新闻',
        'achieved' => '教学成果'
    ];

    public function lists()
    {
        $params = Input::all();
        $news = new NM();
        if ( isset($params['keyword']) && $params['keyword'] ) {
            $news = $news->where('title','like','%'.$params['keyword'].'%');
        }
        if ( isset($params['start']) && $params['start'] ) 
            $news = $news->where('created_at','>=',$params['start']);

        if ( isset($params['end']) && $params['end'] ) 
            $news = $news->where('created_at','<=',$params['end']) ;

        if ( isset($params['status'])&&$params['status']!='' )
            $news = $news->where('status',$params['status']);

        $lists = $news->get();

    	return view('news.lists',['lists'=>$lists,'type'=>$this->type]);
    }

    public function add()
    {
    	return view('news.add',['type'=>$this->type]);
    }

    public function store()
    {
    	$params = Input::all();
        unset($params['file']);
        $flag = NM::create($params);
        if ($flag) return $this->detail($flag->id)->with('success', '新增成功');
    }

    public function detail($id)
    {
        $news = NM::find($id);
        return view('news.detail',['news'=>$news,'type'=>$this->type]);
    }

    public function update()
    {
        $params = Input::all();
        $news = NM::find($params['id']);
        unset($params['file']);
        $news->update($params);
        if ($news) return $this->detail($news->id)->with('success', '修改成功');
    }

    public function auth()
    {
        $params = Input::all();
        $news = NM::find($params['id']);
        if ( $news->update(['status'=>$params['status']]) ) {
            return 1;
        }
        return 0;
    }

    public function delete()
    {
        $ids = Input::all();
        if (is_array($ids)) {
            foreach ($ids as $key => $value) {
                NM::find($value)->delete();
            }
        } else {
            NM::find($ids)->delete();
        }
        return redirect('manage/news/lists/');
    }


}