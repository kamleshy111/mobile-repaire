<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['product_id','repair_id','user_id','quantity','amount','task_description','status','start_time','end_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
