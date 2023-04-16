<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Donationtype;
use App\Models\Donation;
use App\Models\Category;

class Casee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'image',
        'donationtype_id',
        'category_id',
        'initial_amount',
        'paied_amount',
        'remaining_amount',
        'status',
        'user_id'
    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function donationtype(){
        return $this->belongsTo(Donationtype::class,'donationtype_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

 
}
