<?php
/*****************************************************/
# UserDetails
# Page/Class name   : UserDetails
# Author            :
# Created Date      : 23-07-2020
# Functionality     : Table declaration
# Purpose           : Table declaration
/*****************************************************/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class UserDetails extends Model
{
    protected $table = 'user_details';

    public $sortable = ['id'];

    public $timestamps = false;
    use Sortable;
    
    // protected $hidden = ['id'];


    /*****************************************************/
    # User
    # Function name : userBank
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Relation between User table
    # Params        : 
    /*****************************************************/

    public function userBank() {
		return $this->belongsTo('\App\User', 'bank_id', 'id');
  }
  public function userZone() {
		return $this->belongsTo('\App\User', 'zone_id', 'id');
  }
  public function userRangeOne() {
		return $this->belongsTo('\App\User', 'range_id', 'id');
  }
  public function userDistrict() {
		return $this->belongsTo('\App\District', 'district_id', 'id');
  }
  public function userBlock() {
		return $this->belongsTo('\App\Block', 'block_id', 'id');
    }
    
    public function userSoftware() {
		return $this->belongsTo('\App\Software', 'software_using', 'id');
    }
    public function userSocietie() {
      return $this->belongsTo('\App\Societie', 'socity_type', 'id');
      }
    
    public function userDetail() {
		return $this->belongsTo('\App\User', 'user_id', 'id');
	}
  


    /*****************************************************/
    # User
    # Function name : userRange
    # Author        :
    # Created Date  : 25-09-2020
    # Purpose       : Relation between User table
    # Params        : 
    /*****************************************************/

    public function userRange() {
        return $this->belongsTo('\App\User', 'zone_id', 'id');
    }

    
}