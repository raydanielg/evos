<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['name' => 'Form I', 'code' => 'F1', 'sort_order' => 1],
            ['name' => 'Form II', 'code' => 'F2', 'sort_order' => 2],
            ['name' => 'Form III', 'code' => 'F3', 'sort_order' => 3],
            ['name' => 'Form IV', 'code' => 'F4', 'sort_order' => 4],
        ];

        foreach ($classes as $c) {
            SchoolClass::firstOrCreate(
                ['code' => $c['code']],
                ['name' => $c['name'], 'sort_order' => $c['sort_order']]
            );
        }
    }
}
