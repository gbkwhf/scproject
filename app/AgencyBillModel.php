<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgencyBillModel extends Model
{
    protected  $table = "ys_agency_bills";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
}
