<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Donationitem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'donation_id',
        'amount',
    ];

    protected $appends =['item_details'];


    public function getItemDetailsAttribute(){
        $item = Item::find($this->item_id);
        return $item;
    }

}
