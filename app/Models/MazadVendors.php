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

    protected $appends =['user_name'];


    public function getUserNameAttribute(){
        $user = User::find($this->vendor_id);
        return $user->name_en;
    }

}