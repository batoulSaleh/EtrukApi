<?php

namespace App\Models;

use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'image',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'user_id'
    ];
    // public function volunteers()
    // {
    //     return $this->belongsToMany(Volunteer::class);
    // }
}
