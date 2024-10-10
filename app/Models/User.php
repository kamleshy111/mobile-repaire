<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'contact',
        'status',
        'engineer_id',
    ];

    protected static function booted()
    {

        static::creating(function ($customer) {
            do {
                $engineer_id = 'B-' . time() . rand(10, 99); // Example format
            } while (self::where('engineer_id', $engineer_id)->exists());
    
            $customer->engineer_id = $engineer_id;
        });


    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function repair()
    {
        return $this->hasMany(Repair::class);
    }

    public function customerHistory()
    {
        return $this->hasMany(CustomerHistory::class);
    }

}
