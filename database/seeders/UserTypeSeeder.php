<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users_types = [
            'super_admin',
            'admin',
            'lawyer',
            'customer'
        ];

        foreach ($users_types as $user_type) {
            UserType::updateOrCreate(
                ['name' => $user_type], 
                ['name' => $user_type]
            );
        }
    }
}
