<?php

namespace Database\Seeders;

use App\Models\ExamType;
use Illuminate\Database\Seeder;

class ExamTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Pre-Mock',
            'Mock',
            'Test',
            'Series',
            'Midterm',
            'End-of-Term',
            'Annual',
            'Practical',
            'Oral',
        ];

        foreach ($types as $type) {
            ExamType::firstOrCreate(['name' => $type]);
        }
    }
}
