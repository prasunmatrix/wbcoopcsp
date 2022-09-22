<?php


namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'users';
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /*****************************************************/
    # User
    # Function name : setPasswordAttribute
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Make password hash
    # Params        : $pass
    /*****************************************************/
    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = \Hash::make($pass);
    }

    /*****************************************************/
    # User
    # Function name : checkRolePermission
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Relation between userProfile table
    # Params        : 
    /*****************************************************/
    public function checkRolePermission() {
        return $this->belongsTo('\App\Role', 'role_id')->where('is_admin','1');
    }
    
    /*****************************************************/
    # User
    # Function name : allRolePermissionForUser
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Relation between userProfile table
    # Params        : 
    /*****************************************************/
    public function allRolePermissionForUser() {
        return $this->hasMany('\App\RolePermission', 'role_id', 'role_id');
    }
    
    /*****************************************************/
    # User
    # Function name : userProfile
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Relation between userProfile table
    # Params        : 
    /*****************************************************/
    public function userProfile()
    {
        return $this->hasOne('\App\UserDetails', 'user_id');
    }

    /*****************************************************/
    # User
    # Function name : userBankData
    # Author        :
    # Created Date  : 24-09-2020
    # Purpose       : Relation between UserDetails table
    # Params        : 
    /*****************************************************/

    public function userBankData()
    {
        return $this->belongsTo('\App\UserDetails', 'user_id');
    }

    /*****************************************************/
    # User
    # Function name : userBankData
    # Author        :
    # Created Date  : 25-09-2020
    # Purpose       : Relation between UserDetails table
    # Params        : 
    /*****************************************************/

    public function userRangeData()
    {
        return $this->belongsTo('\App\UserDetails', 'user_id');
    }


    

    
}