<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformBalance extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="platformbalance";
    protected $fillable = [
        'id',
        'OoredooBalance',
        'JawwalBalance',
        'JawwalPayBalance',
        'ElectricityBalance',
        'OoredooBillsBalance',
        'BankOfPalestineBalance',
        'BankAlQudsBalance',
        'BalanceType',
        'notes',
        'created_at',
        'updated_at',
        'deleted_at',
        'userName',
        'updated_By',
        'deleted_by',
    ];
    public $timestamps = true;
}


