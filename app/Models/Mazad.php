<?php

namespace App\Models;

use App\Models\Mazadimage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mazad extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'starting_price',
        'current_price',
        'end_time',
        'end_date',
        'owner_id',
        'status',
        'mazad_amount'
    ];
    public function mazadimage()
    {
        return $this->hasMany(Mazadimage::class);
    }
}