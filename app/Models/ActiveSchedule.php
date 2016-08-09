<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActiveSchedule extends Eloquent
{
    use SoftDeletes;
    protected $dates = ['create_at', 'update_at', 'deleted_at'];
    protected $guarded = ['id'];

    public function getActiveName()
    {
        return $this->belongsTo('App\Models\Active','active_id');
    }
}