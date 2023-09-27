<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class end_daily_outs extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="end_daily_outs";
    protected $fillable = [
        'id',
        'amount_usd',
        'amount_jod',
        'amount_ils',
        'amount_daily',
        'notes',
        'created_at',
        'updated_at',
        'created_by',
        'updated_By',
        'deleted_by',
        'deleted_at',
    ];
    public $timestamps = true;
}
