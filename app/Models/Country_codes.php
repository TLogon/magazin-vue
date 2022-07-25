<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country_codes extends Model
{
    protected $table = 'country_codes';
    public $timestamps = false;
    use HasFactory;
}
