<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OuiData extends Model
{
    use HasFactory;

    protected $fillable = [
        'registry',
        'assignment',
        'organisation_name',
        'organisation_address',
    ];
    protected $table = 'oui_data';
}
