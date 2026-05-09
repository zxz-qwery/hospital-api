<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'content'
    ];
}