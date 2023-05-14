<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MazadVendors extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendor_id', 'mazad_id', 'vendor_paid',
        'vendor_paid_time',
    ];
    public function mazads()
    {
        return $this->hasMany(Mazad::class);
    }
    public function volunteers()
    {
        return $this->hasMany(Volunteer::class);
    }
}