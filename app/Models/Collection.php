<?php
/**
 * Created by 朱士亚.
 * Date: 15/2/7
 * @author 朱士亚<i@imzsy.com>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Eloquent
{

    use SoftDeletes;

    protected $dates = ['create_at', 'update_at', 'deleted_at'];
    protected $guarded = ['id'];

}