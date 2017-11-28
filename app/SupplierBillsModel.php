<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierBillsModel extends Model
{
    protected  $table = "ys_supplier_bills";
    protected  $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = false;
   
}
