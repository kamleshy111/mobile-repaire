<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['brand_id','model','description','part_details'];

    protected $casts = [
        'part_details' => 'array',
    ];
    

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
