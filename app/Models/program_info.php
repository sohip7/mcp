<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class program_info extends Model
{
    use HasFactory;
    protected $table="program_info";
    protected $fillable = [
        'version_number'
    ];
    public $timestamps=false;
}
