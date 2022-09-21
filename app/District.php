<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;


class District extends Model
{
    use SoftDeletes;
    use Sortable;
    protected $table = 'district';
    public $sortable = ['id',
                        'district_name'];
    
}
