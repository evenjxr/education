<?php
namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;
use App\Models\InviteRecord as InviteRecordM;
use App\Models\InviteCode as InviteCodeM;
use App\Models\Manage as ManageM;
use App\Models\Teacher as TeacherM;
use App\Models\Student as StudentM;
use App\Models\Institution as InstitutionM;


class InviteRecord extends Controller
{
    public function lists(Request $request)
    {
        $this->userInfo($request);
        $code = InviteCodeM::where('user_id',$this->userInfo->id)->first()['code'];
        $invites = InviteRecordM::where('code',$code)->get();
        $teacher = new TeacherM;
        $manage = new ManageM;
        $student = new StudentM;
        $institution = new InstitutionM;


        foreach ($invites as $key => $value) {
            switch ($value->type) {
                case 'teacher':
                    $invites[$key]->name =$teacher->find($value->user_id)['truename'];
                    break;
                case 'manage':
                    $invites[$key]->name =$manage->find($value->user_id)['truename'];
                    break;
                case 'institution':
                    $invites[$key]->name =$institution->find($value->user_id)['truename'];
                    break;
                default:
                    $invites[$key]->name =$student->find($value->user_id)['truename'];
                    break;
            }

            return response()->json(['success' => 'Y', 'msg' => '', 'data' => $invites]);

        }



//        $list = DB::table('invite_record')
//                ->leftjoin('actives', 'actives.id', '=', 'active_schedules.active_id')
//                ->where('active_schedules.schedule_id','=',$id)
//                ->whereNull('active_schedules.deleted_at')
//                ->whereNull('actives.deleted_at')
//                ->select('actives.id','actives.name','actives.channel')
//                ->get();


    }


}
