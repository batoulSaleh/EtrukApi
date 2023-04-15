<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Donationtype;
use App\Models\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

    Dontiontype::create([
        'name_en'=>'Financial',
        'name_ar'=>'مالي'
    ]);

    Dontiontype::create([
        'name_en'=>'Volunteer',
        'name_ar'=>'تطوع'
    ]);

    Dontiontype::create([
        'name_en'=>'Food',
        'name_ar'=>'طعام'
    ]);
    
    Dontiontype::create([
        'name_en'=>'clothes',
        'name_ar'=>'ملابس'
    ]);

    Dontiontype::create([
        'name_en'=>'furniture or devices',
        'name_ar'=>'أثاث او أجهزة'
    ]);

    Dontiontype::create([
        'name_en'=>'others',
        'name_ar'=>'أخري'
    ]);


    User::create([
        'name' =>'Etruk Athraa',
        'email' =>'Etruk@Etruk.com',
        'password' => bcrypt('12345678'),
        'user_type'=> 2,
        'address'=> 'address',
        'phone'=> 1234568,
    ]);


    }
}
