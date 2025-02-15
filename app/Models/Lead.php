<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile_no',
        'city',
        'source',
        'disposition',
        'lead_type',
        'attempted',
        'remark',
        'date'
    ];
}
