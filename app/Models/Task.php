<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'tag_id',
        'title',
        'deadline_date',
        'memo',
        'unfinished',
        'status',
    ];
}
