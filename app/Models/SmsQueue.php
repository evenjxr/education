<?php
/**
 * Created by 朱士亚.
 * Date: 15/3/6
 * @author 朱士亚<i@imzsy.com>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use HttpClient;
use App\Extra\SMS;

class SmsQueue extends Eloquent
{
    use SoftDeletes;

    protected $dates = ['create_at', 'update_at', 'deleted_at', 'sended_at'];
    protected $guarded = ['id'];


    public function send()
    {
        $result = HttpClient::post([
            'url'    => SMS::XSEND_URL,
            'params' => [
                'appid'     => SMS::APP_ID,
                'signature' => SMS::APP_KEY,
                'to'        => $this->to,
                'project'   => $this->project,
                'vars'      => $this->vars,
            ],
        ]);
        $resRaw = json_decode($result->content(), true);
        if (isset($resRaw['status']) && $resRaw['status'] == 'success') {
            //success
            $project = config('sms.projects.add_employee');
            $content = $project['content'];

            foreach (json_decode($this->vars) as $k => $v) {
                $content = str_replace('@var(' . $k . ')', $v, $content);
            }
            SmsLog::create([
                'to'      => $this->to,
                'project' => $this->project,
                'content' => $content
            ]);
            $this->status = 1;
            $this->save();
            return true;
        }elseif (isset($resRaw['status']) && $resRaw['status'] == 'error') {
            return false;
        }
        return false;

    }
}