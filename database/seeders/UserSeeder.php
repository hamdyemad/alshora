<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user_type = UserType::where('name', 'super_admin')->first();

        $user = User::updateOrCreate(
            ['user_type_id' => $user_type->id], 
            ['email' => 'super_admin@gmail.com', 'password' => bcrypt('123456789')]
        );
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],[
            'gender' => 'male',
            'phone' => '1152059120',
            'phone_country_id' => 1,
        ]);
        $profile->setTranslation('name', 'en', 'super admin');
        $profile->setTranslation('name', 'ar', 'ادمن');
    }
}
