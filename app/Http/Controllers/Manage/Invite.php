<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Input;
use Session;

use App\Models\InviteRecord as InviteRecordM;
use App\Models\InviteCode as InviteCodeM;
use App\Models\Manage as ManageM;
use App\Models\Teacher as TeacherM;
use App\Models\Student as StudentM;
use App\Models\Institution as InstitutionM;
use App\Models\Server as ServerM;

class Invite extends Controller
{
    public function lists()
    {
        $params = Input::all();

        $code = InviteCodeM::where('user_id',$params['user_id'])->first()['code'];
        $invites = InviteRecordM::where('code',$code)->get();
        $teacher = new TeacherM;
        $manage = new ManageM;
        $student = new StudentM;
        $institution = new InstitutionM;

        foreach ($invites as $key => $value) {
            switch ($value->type) {
                case 'teacher':
                    $invites[$key]->name =$teacher->find($value->user_id)['truename'];
                    ServerM::
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
        }
        $this->view('invite.record',['lists'=>$invites]);
    }

}