<?php

namespace App\Models;

use App\Models\Event;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvenntVolunteer extends Model
{
    use HasFactory;
    protected $fillable = [
        'joined', 'event_id',
        'volunteer_id'
    ];
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    public function volunteers()
    {
        return $this->hasMany(Volunteer::class);
    }
}
