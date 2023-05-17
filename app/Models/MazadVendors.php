<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MazadVendors extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'mazad_id', 'vendor_paid',
        'vendor_paid_time',
    ];

    public function users(){
        return $this->hasMany(User::class);
    }
}