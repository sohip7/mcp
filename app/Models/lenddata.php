<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class lenddata extends Model
{
    use HasFactory,SoftDeletes;


    protected $table="lenddata";
    protected $fillable = [
        'id',
        'sales_foreign_id',
        'shaaf_sim_sales_foreign_id',
        'RecordType',
        'item',
        'amount',
        'quantity',
        'FirstPay',
        'notes',
        'debtorName',
        'created_at',
        'updated_at',
        'UserName',
        'updated_By',
        'deleted_by',
        'deleted_at',
        'total',
    ];
    public $timestamps = true;

}
