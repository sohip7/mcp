<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class balance_inout extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="balance_inout";
    protected $fillable = [
        'id',
        'record_type',
        'platform_name',
        'jawwalpay_account_type',
        'outs_foreign_id',
        'sales_foreign_id',
        'salescomssion_foreign_id',
        'cuspay_foreign_id',
        'purchases_foreign_id',
        'merchantpay_foreign_id',
        'loans_foreign_id',
        'service_number',
        'amount',
        'notes',
        'created_at',
        'created_by',
        'updated_at',
        'updated_By',
        'deleted_at',
        'deleted_by',
    ];
    public $timestamps = true;
}
