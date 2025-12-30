<?php

namespace Database\Seeders;

use App\Models\SectionOfLaw;
use Illuminate\Database\Seeder;

class SectionsOfLawsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['ar' => 'جنائى', 'en' => 'Criminal'],
            ['ar' => 'مدنى', 'en' => 'Civil'],
            ['ar' => 'الاسرة', 'en' => 'Family'],
            ['ar' => 'مجلس الدولة', 'en' => 'State Council'],
            ['ar' => 'تجارى وشركات', 'en' => 'Commercial & Corporate'],
            ['ar' => 'قضاء عسكرى', 'en' => 'Military Justice'],
            ['ar' => 'جرائم الالكترونية', 'en' => 'Cybercrime'],
            ['ar' => 'محاكم اقتصادية', 'en' => 'Economic Courts'],
            ['ar' => 'التعويضات', 'en' => 'Compensation'],
            ['ar' => 'قانون العمل', 'en' => 'Labor Law'],
            ['ar' => 'التحكيم الدولى', 'en' => 'International Arbitration'],
            ['ar' => 'أحوال شخصيه لغير المسلمين', 'en' => 'Personal Status for Non-Muslims'],
            ['ar' => 'صياغه العقود والتوثيق', 'en' => 'Contract Drafting & Documentation'],
            ['ar' => 'الترجمه القانوية', 'en' => 'Legal Translation'],
            ['ar' => 'الجنسيه و الهجرة', 'en' => 'Nationality & Immigration'],
            ['ar' => 'الملكيه الفكرية', 'en' => 'Intellectual Property'],
            ['ar' => 'الايجارات', 'en' => 'Rentals'],
            ['ar' => 'التامينات و المعاشات', 'en' => 'Insurance & Pensions'],
            ['ar' => 'خدمات حكومية', 'en' => 'Government Services'],
        ];

        foreach ($sections as $section) {
            $sectionOfLaw = SectionOfLaw::create([
                'active' => true,
            ]);

            // Set translations
            $sectionOfLaw->setTranslation('name', 'ar', $section['ar']);
            $sectionOfLaw->setTranslation('name', 'en', $section['en']);
            $sectionOfLaw->save();
        }

        $this->command->info('Sections of Laws seeded successfully!');
    }
}
