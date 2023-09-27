<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerPay extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="customerpay";
    protected $fillable = [
        'id',
        'CustomerName',
        'amount',
        'PayMethod',
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
