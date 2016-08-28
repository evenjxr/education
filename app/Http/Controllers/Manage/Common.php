<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Illuminate\Http\Request;
use Excel;
use App\Models\Active as AM;
use App\Models\Schedule as SM;
use App\Models\ActiveSchedule as ASM;
use App\Models\Tournament as TMM;
use App\Models\Team as TM;
use App\Models\TeamScore as TSM;
use App\Models\User as UM;
use App\Models\Collection as CM;
use DB;

use App\Models\Score as SMM;

class Common extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $path = env('FILE_PATH') . '/';
        $dir = date('Y').'/'.date('m').'/'.date('d');
        $url = $path.$dir;
        if (is_dir($url)) @mkdir($url,0777,true);
        $new_name = date('His').rand(100,999).strstr($_FILES['file']['name'],'.');
        $file->move($url,$new_name);
        return '/uploads/'.$dir.'/'.$new_name;
    }

    public function uploadvideo($file)
    {
        $path = env('FILE_PATH') . '/';
        $dir = date('Y').'/'.date('m').'/'.date('d');
        $fullPath = $path.$dir;
        if (is_dir($fullPath)) @mkdir($fullPath,0777,true);
        $new_name = date('His').rand(100,999).'.'.$file ->getClientOriginalExtension();
        $file -> move($fullPath,$new_name);
        return '/uploads/'.$dir.'/'.$new_name;
    }
}