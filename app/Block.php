<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Block extends Model
{
    protected $table = 'block';

    public $timestamps = false;
    use Sortable;
    public $sortable = ['id',
                        'block_name'];
    
    //protected $hidden = ['id'];
    /*****************************************************/
    # Cities
    # Function name : districtDetails
    # Author        :
    # Created Date  : 10-08-2020
    # Purpose       : Relation between District table
    # Params        : 
    /*****************************************************/
    public function districtDetails() {
        return $this->belongsTo('\App\District', 'district_id');
    }
}
