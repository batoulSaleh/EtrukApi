<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Donationtype;
use App\Models\Donation;
use App\Models\Category;
use App\Models\Item;
use App\Models\Caseimage;

class Casee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'type_en',
        'type_ar',
        'gender_en',
        'gender_ar',
        'file',
        'donationtype_id',
        'category_id',
        'initial_amount',
        'paied_amount',
        'remaining_amount',
        'status',
        'user_id',
        'reason_reject_en',
        'reason_reject_ar'
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

    public function donation(){
        return $this->hasMany(Donation::class,'donation_id');
    }

    public function item()
    {
        return $this->hasMany(Item::class);
    }

    public function caseimage()
    {
        return $this->hasMany(Caseimage::class);
    }
}
