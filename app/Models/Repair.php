<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','brand_id','device_model','issue','issue_description','customer_name','customer_contact',
    'estimated_cost', 'patern_lock', 'date_time', 'deliver_date', 'received_amount', 'final_cost', 'status' ];

    protected static function booted()
    {
        static::created(function ($repair) {
      
                $customeHistory = new \App\Models\CustomerHistory();
                $customeHistory->user_id = $repair->user_id;
                $customeHistory->repair_id = $repair->id;
                $customeHistory->issue = $repair->issue;
                $customeHistory->save();

        });

        static::updating(function ($repair) {
            $userHistory = new \App\Models\CustomerHistory();
            $userHistory->user_id = $repair->user_id;
            $userHistory->repair_id = $repair->id;
            $userHistory->issue = $repair->issue;
            $userHistory->save();
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customerHistory()
    {
        return $this->hasMany(CustomerHistory::class);
    }

}
