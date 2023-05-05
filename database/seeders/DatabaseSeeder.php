<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Donationtype;
use App\Models\User;
use App\Models\Category;

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

    Category::create([
        'name_en'=>'Sponsoring an orphan',
        'name_ar'=>'كفالة اليتيم'
    ]);

    Category::create([
        'name_en'=>'medical cases',
        'name_ar'=>'حالات طبية'
    ]);

    Category::create([
        'name_en'=>'Humanitarian cases',
        'name_ar'=>'حالات انسانية'
    ]);
    
    Category::create([
        'name_en'=>'Education',
        'name_ar'=>'التعليم'
    ]);

    Category::create([
        'name_en'=>'Al Gharemeen',
        'name_ar'=>'الغارمين'
    ]);

    Donationtype::create([
        'name_en'=>'Financial',
        'name_ar'=>'مالي'
    ]);

    Donationtype::create([
        'name_en'=>'Volunteer',
        'name_ar'=>'تطوع'
    ]);

    Donationtype::create([
        'name_en'=>'Food',
        'name_ar'=>'طعام'
    ]);
    
    Donationtype::create([
        'name_en'=>'clothes',
        'name_ar'=>'ملابس'
    ]);

    Donationtype::create([
        'name_en'=>'furniture or devices',
        'name_ar'=>'أثاث او أجهزة'
    ]);

    Donationtype::create([
        'name_en'=>'others',
        'name_ar'=>'أخري'
    ]);


    User::create([
        'name' =>'Etruk Athraa',
        'email' =>'Etruk@Etruk.com',
        'password' => bcrypt('12345678'),
        'user_type'=> '2',
        'address'=> 'address',
        'phone'=> 1234568,
    ]);

    User::create([
        'name' =>'Admin',
        'email' =>'admin@admin.com',
        'password' => bcrypt('admin'),
        'user_type'=> '0',
        'address'=> 'address',
        'phone'=> 1234568,
    ]);
    }
}
