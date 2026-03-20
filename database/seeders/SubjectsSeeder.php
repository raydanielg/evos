<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // Common O-Level subjects (Tanzania)
            'Civics',
            'History',
            'Geography',
            'Kiswahili',
            'English Language',
            'Biology',
            'Basic Mathematics',
            'Physics',
            'Chemistry',
            'Mathematics',
            'English Literature',
            'Commerce',
            'Book Keeping',
            'Accounts',
            'Business Studies',
            'Economics',
            'French',
            'Arabic',
            'Chinese Language',
            'Fine Art',
            'Music',
            'Theatre Arts',
            'Physical Education',
            'Information and Computer Studies',
            'Computer Science',
            'Agriculture',
            'Home Economics',
            'Food and Nutrition',
            'Nutrition',
            'Clothing and Textiles',
            'Human and Family Life Education',
            'BAM (Basic Applied Mathematics)',
            'Engineering Science',
            'Technical Drawing',
            'Workshop Technology',
            'Building Construction',
            'Electrical Installation',
            'Electronics',
            'Automobile Engineering',
            'Carpentry and Joinery',
            'Plumbing',
            'Tailoring',
            'Typing',
            'Office Practice',
            'Stenography',
            'Entrepreneurship',
            'Maritime Transport',
            'Navigation',
            'Swimming',
            'Sports',

            // Common A-Level subjects (Tanzania)
            'General Studies',
            'Advanced Mathematics',
            'Applied Mathematics',
            'Further Mathematics',
            'Physics (A-Level)',
            'Chemistry (A-Level)',
            'Biology (A-Level)',
            'Geography (A-Level)',
            'History (A-Level)',
            'Economics (A-Level)',
            'Commerce (A-Level)',
            'Accountancy',
            'Computer Science (A-Level)',
            'Information and Computer Studies (A-Level)',
            'Kiswahili (A-Level)',
            'English (A-Level)',
            'French (A-Level)',
            'Arabic (A-Level)',
            'Chinese (A-Level)',
            'Literature in English',
            'Fine Art (A-Level)',
            'Music (A-Level)',
            'Theatre Arts (A-Level)',
            'Nutrition (A-Level)',
            'Food and Nutrition (A-Level)',
            'Agriculture (A-Level)',
            'Divinity',
            'Islamic Knowledge',
            'Christian Knowledge',
            'Sociology',
            'Psychology',
            'Philosophy',
        ];

        foreach ($subjects as $subjectName) {
            \App\Models\Subject::updateOrCreate(
                ['name' => $subjectName],
                ['name' => $subjectName]
            );
        }
    }
}
