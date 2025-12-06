<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        
        // Seed languages first (required for translations)
        // $this->call(LanguageSeeder::class);
        
        // Seed permissions with translations
        // $this->call(PermessionSeeder::class);
        
        // Seed roles with permissions
        // $this->call(RoleSeeder::class);
        
        // Seed users types
        // $this->call(UserTypeSeeder::class);
        
        // Seed countries (required for user profiles)
        // $this->call(CountrySeeder::class);
        
        // Seed users
        // $this->call(UserSeeder::class);

        $this->call(RegisterationGradeSeeder::class);
    }
}
