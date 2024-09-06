<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = ['device_brand','device_model','issue','issue_description','customer_name','customer_contact','estimated_cost', 'final_cost', 'status' ];

    public function task()
    {
        return $this->hasMany(Task::class);
    }

}
