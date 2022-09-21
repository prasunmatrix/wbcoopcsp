<?php
/*****************************************************/
# Bank
# Page/Class name   : Bank
# Author            :
# Created Date      : 23-09-2020
# Functionality     : Table declaration, get local (englis and arabic) details
# Purpose           : Table declaration, get local (englis and arabic) details
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'bank';

    public $timestamps = false;
    
    // //protected $hidden = ['id'];
    // /*****************************************************/
    // # Cities
    // # Function name : stateDetails
    // # Author        :
    // # Created Date  : 10-08-2020
    // # Purpose       : Relation between state table
    // # Params        : 
    // /*****************************************************/
    // public function stateDetails() {
    //     return $this->belongsTo('\App\State', 'state_id');
    // }
}