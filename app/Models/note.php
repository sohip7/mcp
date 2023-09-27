<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class note extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="notes";
    protected $fillable = [
        'id',
        'notes',
        'user_name',
        'updated_By',
        'updated_at',
        'created_at',
        'deleted_by',
        'deleted_at',

    ];
    public $timestamps = true;

}
