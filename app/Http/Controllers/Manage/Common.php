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

    public function output($schedule_id)
    {
        $schedule = SM::find($schedule_id,['title','channel','id','start']);
        if ($schedule->channel =='personal') {
            $this->personalPrint($schedule);
        } else {
            $this->teamPrint($schedule);
        }

    }

    private function personalPrint($schedule)
    {
        $data = [];
        Excel::create($schedule->title.'--'.$schedule->start, function($excel) use ($schedule){
            // Set the title
            $excel->setTitle($schedule->title);
            // Chain the setters
            $excel->setCreator('Maatwebsite')->setCompany('Maatwebsite');
            // Call them separately
            $excel->setDescription('A demonstration to change the file properties');


            $excel->sheet('活动详情', function($sheet)  use ($schedule) {
                $sheet->setWidth(array(
                    'A'     =>  5,
                    'B'     =>  10,
                    'C'     =>  20,
                    'D'     =>  5,
                    'E'     =>  5,
                    'F'     =>  10
                ));
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');


                $rowArr1 = ['序号','姓名','身份证号','年龄','性别','电话'];
                $active_id = ASM::where('schedule_id',$schedule->id)->lists('active_id');
                $active = AM::whereIn('id',$active_id)->get(['name','standard','id']);
                $rowArr = ['','','','','',''];
                foreach ($active as $key => $value) {
                    if ($value->standard =='best_of_three' ) {
                        array_push($rowArr1,$value->name);
                        array_push($rowArr1,'');
                        array_push($rowArr1,'');
                        array_push($rowArr,'第一次');
                        array_push($rowArr,'第二次');
                        array_push($rowArr,'第三次');

                    }
                    if($value->standard =='num_time') {
                        array_push($rowArr1,$value->name);
                        array_push($rowArr1,'');
                        array_push($rowArr,'数量');
                        array_push($rowArr,'时间');
                    }
                    if($value->standard =='num') {
                        array_push($rowArr1,$value->name);
                        array_push($rowArr,'数量');
                    }
                    if($value->standard =='time') {
                        array_push($rowArr1,$value->name);
                        array_push($rowArr,'时间');
                    }
                }
                $sheet->row(1,$rowArr1);
                $sheet->row(2,$rowArr);
                $sheet->cells('A1:'.chr(64+count($rowArr)).'1', function($cells) {
                    $cells->setBackground('#999');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontSize(14);
                    $cells->setFontWeight('bold');
                    //$cells->setBorder('solid', 'none', 'none', 'solid');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
                $sheet->cells('A:'.chr(64+count($rowArr)), function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $data = [];
                $score = SMM::where('schedule_id',$schedule->id)->get(['user_id','active_id','num','time','first','second','three']);
                foreach ($score as $key=>$value){
                    $data[$value->user_id][$value->active_id]['num'] = $value->num;
                    $data[$value->user_id][$value->active_id]['time'] = $value->time;
                    $data[$value->user_id][$value->active_id]['first'] = $value->first;
                    $data[$value->user_id][$value->active_id]['second'] = $value->second;
                    $data[$value->user_id][$value->active_id]['three'] = $value->three;
                }
                $user = DB::table('tournaments')
                    ->join('users', 'users.id', '=', 'tournaments.user_id')
                    ->where('tournaments.schedule_id',$schedule->id)
                    ->whereNull('tournaments.deleted_at')
                    ->orderBy('users.id')
                    ->select('users.id','users.name','users.idcard','users.age','users.sex','users.mobile')
                    ->get();
                foreach ($user as $key=>$value) {
                    $rowArr = [$key+1,$value->name,$value->idcard,$value->age,$value->sex?'男':'女',$value->mobile];
                    foreach ($active as $k => $v) {
                        if ($v->standard =='best_of_three' ) {
                            if (isset($data[$value->id][$v->id]['first'])) {
                                array_push($rowArr, $data[$value->id][$v->id]['first']);
                            } else {
                                array_push($rowArr, '');
                            }
                            if (isset($data[$value->id][$v->id]['second'])) {
                                array_push($rowArr, $data[$value->id][$v->id]['second']);
                            } else {
                                array_push($rowArr, '');
                            }
                            if (isset($data[$value->id][$v->id]['three'])) {
                                array_push($rowArr, $data[$value->id][$v->id]['three']);
                            } else {
                                array_push($rowArr, '');
                            }
                        }

                        if($v->standard =='num_time'||$v->standard =='num') {
                            if (isset($data[$value->id][$v->id]['num'])) {
                                array_push($rowArr, $data[$value->id][$v->id]['num']);
                            } else {
                                array_push($rowArr, '');
                            }
                        }
                        if($v->standard =='num_time'||$v->standard =='time') {
                            if (isset($data[$value->id][$v->id]['time'])) {
                                array_push($rowArr, $data[$value->id][$v->id]['time']);
                            } else {
                                array_push($rowArr, '');
                            }

                        }
                    }
                    $sheet->row($key+3,$rowArr);
                }
            });

        })->download('xlsx');
    }

    private function teamPrint($schedule)
    {
        Excel::create($schedule->title.'--'.$schedule->start, function($excel) use ($schedule){
            $excel->sheet('活动详情', function($sheet)  use ($schedule) {
                $sheet->cells('A1:G1', function($cells) {
                    $cells->setBackground('#999');
                    $cells->setFontColor('#ffffff');
                    $cells->setFontSize(14);
                    $cells->setFontWeight('bold');
                    //$cells->setBorder('solid', 'none', 'none', 'solid');
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });


                $rowArr1 = ['序号','队名','队员一','队员二','队员三','队员四','成绩'];
                $sheet->row(1,$rowArr1);
                $team_ids = TMM::where('schedule_id',$schedule->id)->limit(2)->lists('team_id')->toArray();
                foreach ($team_ids as $key=>$value) {
                    $rowArr = [$key+1];
                    $team = TM::find($value);
                    array_push($rowArr, $team->name);
                    array_push($rowArr, UM::find($team->player_one_id)->name);
                    if ($team->player_two_id) {
                        array_push($rowArr, UM::find($team->player_two_id)->name);
                    } else {
                        array_push($rowArr, '');
                    }
                    if ($team->player_three_id) {
                        array_push($rowArr, UM::find($team->player_three_id)->name);
                    } else {
                        array_push($rowArr, '');
                    }
                    if ($team->player_four_id) {
                        array_push($rowArr, UM::find($team->player_four_id)->name);
                    } else {
                        array_push($rowArr, '');
                    }
                    $score = TSM::where(['team_one_id'=>$team_ids[0],'team_two_id'=>$team_ids[1]])
                        ->orWhere(['team_one_id'=>$team_ids[1],'team_two_id'=>$team_ids[0]])
                        ->first();
                    if ($score->team_one_id == $value) {
                        array_push($rowArr, $score->team_one_score);
                    } else {
                        array_push($rowArr, $score->team_two_score);
                    }
                    $sheet->row($key+2,$rowArr);
                }
            });

        })->download('xlsx');
    }

    public function collection()
    {
        $params = Input::all();
        $user_ids = CM::where(['object_id'=>$params['id'],'moudel'=>$params['moudel']])->lists('user_id');
        $user = UM::whereIn('id',$user_ids->toArray())->get();
        return view('common.collection')->with('lists',$user);
    }

}