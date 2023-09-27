<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class dailydata extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="dailydata";
    protected $fillable = [
        'id',
        'lend_foreign_id',
        'sim_place_of_sale',
        'RecordType',
        'item',
        'amount',
        'quantity',
        'osap',
        'notes',
        'created_at',
        'updated_at',
        'user_name',
        'updated_By',
        'deleted_by',
        'deleted_at',
        'total',
    ];
     public $timestamps = true;


}
