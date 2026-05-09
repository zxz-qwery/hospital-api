<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = [
        'doctor_id',
        'schedule_date',
        'time_period',
        'max_number',
        'current_number'
    ];

    // 开启时间（推荐）
	public $timestamps = false;

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}