<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    protected $fillable = [
        'id_country',
        'id_lead',
        'id_lastmile',
        'error',
        'code',
    ];
}

