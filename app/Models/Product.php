<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name','model','part_type','price','stock_quantity','description'];

    public function task()
    {
        return $this->hasMany(Task::class);
    }
}
