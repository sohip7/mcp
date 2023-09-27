<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealersBuy extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="dealersbuy";
    protected $fillable = [
        'id',
        'RecordType',
        'item',
        'amount',
        'SellerName',
        'notes',
        'created_at',
        'updated_at',
        'UserName',
        'updated_By',
        'deleted_by',
        'deleted_at',
    ];
    public $timestamps = true;

}
