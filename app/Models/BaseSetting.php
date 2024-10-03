<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseSetting extends Model
{
    use HasFactory;

    protected $fillable = [ 'logo', 'favicon_icon', 'title'];
}
