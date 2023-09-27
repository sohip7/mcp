<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class balance_sale extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="balance_sales";
    protected $fillable = [
        'id',
        'ooredoo',
        'ooredooin',
        'jawwal',
        'jawwalin',
        'jawwalpay',
        'jawwalpayin',
        'ooredoobills',
        'ooredoobillsin',
        'electricity',
        'electricityin',
        'firstpay',
        'bop',
        'bopin',
        'bankquds',
        'bankqudsin',
        'updated_at',
        'updated_By',
        'deleted_by',
        'deleted_at',
        'notes',
    ];

    public $timestamps = true;

}
