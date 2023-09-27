<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outs extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="outs";
    protected $fillable = [
        'id',
        'item',
        'amount',
        'RecordType',
        'beneficiary',
        'cuspay_foreign_id',
        'balanceRec_id',
        'service_number',
        'notes',
        'created_at',
        'updated_at',
        'userName',
        'updated_By',
        'deleted_by',
        'deleted_at',
    ];
    public $timestamps = true;

}
