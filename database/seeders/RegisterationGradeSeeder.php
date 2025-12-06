<?php

namespace Database\Seeders;

use App\Models\RegisterationGrades;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegisterationGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['en' => 'general schedule', 'ar' => 'جدول عام'],
            ['en' => 'primary', 'ar' => 'ابتدائى'],
            ['en' => 'appeal', 'ar' => 'استئناف'],
            ['en' => 'break', 'ar' => 'نقض'],
        ];
        foreach($data as $one_data) {
            $regiserGrade = RegisterationGrades::create([]);
            $regiserGrade->setTranslation('name', 'en', $one_data['en']);
            $regiserGrade->setTranslation('name', 'ar', $one_data['ar']);
        }
    }
}
