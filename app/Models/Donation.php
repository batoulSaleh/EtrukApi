<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Donationtype;
use App\Models\Casee;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'casee_id',
        'donationtype_id',
        'method',
        'name',
        'email',
        'amount',
        'amount_description',
        'description',
        'city',
        'address',
        'date_to_send',
        'user_id',
        'phone'
    ];


    public function casee(){
        return $this->belongsTo(Casee::class,'casee_id');
    }

    public function donationtype(){
        return $this->belongsTo(Donationtype::class,'donationtype_id');
    }

    public function donationitem()
    {
        return $this->hasMany(Donationitem::class);
    }
}
