<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'repair_id', 'issue'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repair()
    {
        return $this->belongsTo(Repair::class);
    }
}
