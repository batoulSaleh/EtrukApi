<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donationtype extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_en',
        'name_ar',
    ];

    public function casees(){
        return $this->hasMany(Casee::class);
    }
}
