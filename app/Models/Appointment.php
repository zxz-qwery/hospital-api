<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';

    public $timestamps = true;

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'doctor_id',
        'schedule_id',
        'appointment_no',
        'status',
        'cancel_reason'
    ];

    // 用户
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // 医生
    public function doctor()
    {
        return $this->belongsTo(\App\Models\Doctor::class);
    }

    // 排班
    public function schedule()
    {
        return $this->belongsTo(\App\Models\Schedule::class);
    }
}