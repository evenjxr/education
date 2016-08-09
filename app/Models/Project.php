<?php
/**
 * Created by 朱士亚.
 * Date: 15/3/8
 * @author 朱士亚<i@imzsy.com>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Eloquent
{
    use SoftDeletes;

    protected $dates = ['create_at', 'update_at', 'deleted_at'];
    protected $guarded = ['id'];


    public function manager()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function director()
    {
        return $this->belongsTo('App\Models\User', 'owner');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    /**
     * 和已投项目表关联   
    **/ 

    public function invested()
    {
        return $this->hasOne('App\Models\InvestedProjects', 'project_id');
    }
      

    public function process()
    {
        return $this->hasMany('App\Models\ProjectProcess', 'project_id');
    }

    public function status()
    {
        return $status = ProjectProcess::where('project_id', $this->id)->orderBy('id', 'desc')->first()->status;
    }

    public function nextProcess($code = false)
    {
        $status = $this->status();
        if ($status == 'Grant') {
            return false;
        }
        $arr = ['Filing', 'Visit', 'Report', 'Sign', 'Grant',];
        $offset = array_search($status, $arr);
        if ($code) {
            return $arr[$offset + 1];
        }
        return ProjectProcess::statusMap()[$arr[$offset + 1]];
    }

    public function note()
    {
        return $this->hasMany('App\Models\ProjectNote', 'project_id');
    }
    
    /**
     * 关联项目否决
     **/
    public function processes(){
        return $this->hasMany('App\Models\ProjectProcess', 'project_id');   
    }
    
    public function level(){
        return array(
            "b1"=>'b1',
            "b2"=>'b2',
            "b3"=>'b3',
            "a1"=>'a1',
            "a2"=>'a2',
            "a3"=>'a3',
            "a4"=>'a4'
        );
    }

    public function getLevel(){
        return isset($this->level()[$this->level]) ? $this->level()[$this->level] : '';
    }

    public function employee(){
        return $this->hasOne('App\Models\Employee','user_id');
    }
}