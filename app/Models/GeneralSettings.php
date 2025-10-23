<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'logo',
        'favicon',
        'tagline',
        'email',
        'phone',
        'address',
    ];
}
