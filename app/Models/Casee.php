<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'image',
        'Donantiontype_id',
        'Category_id',
        'initial_amount',
        'paied_amount',
        'remaining_amount',
        'status'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function donationType(){
        return $this->belongsTo(Donationtype::class);
    }
}
