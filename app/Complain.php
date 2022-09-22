<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    protected $table = 'complain';

    public $timestamps = false;

    public function userDetails() {
        return $this->belongsTo('\App\User',  'reported_to','id');
    }
    public function userDetail() {
        return $this->belongsTo('\App\User',  'reported_by','id');
    }
    public function userServiceProvider() {
        return $this->belongsTo('\App\Software',  'reported_to','id');
    }

    public function userDetailReply() {
        return $this->belongsTo('\App\User', 'reply_id','id');
    }
}
