<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierCashApplyModel extends Model
{
    protected  $table = "ys_supplier_cash_apply";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
}
