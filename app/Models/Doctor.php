<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Doctor extends Model
{
    protected $table = 'doctors';

    public $timestamps = false;
protected $fillable = [
    'name',
    'department_id',
    'title',
    'avatar',
    'introduction',
    'status'
];

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class,'doctor_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class,'doctor_id');
    }
}