<?php

namespace Database\Seeders;

use App\Models\GlobalSubject;
use Illuminate\Database\Seeder;

class GlobalSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            // Common O-Level subjects (Tanzania)
            ['name' => 'Civics', 'code' => 'CIV'],
            ['name' => 'History', 'code' => 'HIST'],
            ['name' => 'Geography', 'code' => 'GEO'],
            ['name' => 'Kiswahili', 'code' => 'KISW'],
            ['name' => 'English Language', 'code' => 'ENGL'],
            ['name' => 'Biology', 'code' => 'BIOL'],
            ['name' => 'Basic Mathematics', 'code' => 'BMATH'],
            ['name' => 'Physics', 'code' => 'PHYS'],
            ['name' => 'Chemistry', 'code' => 'CHEM'],
            ['name' => 'Mathematics', 'code' => 'MATH'],
            ['name' => 'English Literature', 'code' => 'LIT'],
            ['name' => 'Commerce', 'code' => 'COMM'],
            ['name' => 'Book Keeping', 'code' => 'BK'],
            ['name' => 'Accounts', 'code' => 'ACC'],
            ['name' => 'Business Studies', 'code' => 'BSTD'],
            ['name' => 'Economics', 'code' => 'ECON'],
            ['name' => 'French', 'code' => 'FREN'],
            ['name' => 'Arabic', 'code' => 'ARAB'],
            ['name' => 'Chinese Language', 'code' => 'CHIN'],
            ['name' => 'Fine Art', 'code' => 'FART'],
            ['name' => 'Music', 'code' => 'MUSI'],
            ['name' => 'Theatre Arts', 'code' => 'TART'],
            ['name' => 'Physical Education', 'code' => 'PE'],
            ['name' => 'Information and Computer Studies', 'code' => 'ICS'],
            ['name' => 'Computer Science', 'code' => 'CSCI'],
            ['name' => 'Agriculture', 'code' => 'AGRIC'],
            ['name' => 'Home Economics', 'code' => 'HECON'],
            ['name' => 'Food and Nutrition', 'code' => 'FNUT'],
            ['name' => 'Nutrition', 'code' => 'NUTR'],
            ['name' => 'Clothing and Textiles', 'code' => 'CTEX'],
            ['name' => 'Human and Family Life Education', 'code' => 'HFLE'],
            ['name' => 'BAM (Basic Applied Mathematics)', 'code' => 'BAM'],
            ['name' => 'Engineering Science', 'code' => 'ESCI'],
            ['name' => 'Technical Drawing', 'code' => 'TDRA'],
            ['name' => 'Workshop Technology', 'code' => 'WTEC'],
            ['name' => 'Building Construction', 'code' => 'BCON'],
            ['name' => 'Electrical Installation', 'code' => 'EINS'],
            ['name' => 'Electronics', 'code' => 'ELEC'],
            ['name' => 'Automobile Engineering', 'code' => 'AENG'],
            ['name' => 'Carpentry and Joinery', 'code' => 'CJON'],
            ['name' => 'Plumbing', 'code' => 'PLUM'],
            ['name' => 'Tailoring', 'code' => 'TAIL'],
            ['name' => 'Typing', 'code' => 'TYP'],
            ['name' => 'Office Practice', 'code' => 'OPRA'],
            ['name' => 'Stenography', 'code' => 'STEN'],
            ['name' => 'Entrepreneurship', 'code' => 'ENTR'],
            ['name' => 'Maritime Transport', 'code' => 'MTRN'],
            ['name' => 'Navigation', 'code' => 'NAV'],
            ['name' => 'Swimming', 'code' => 'SWIM'],
            ['name' => 'Sports', 'code' => 'SPOR'],

            // Common A-Level subjects (Tanzania)
            ['name' => 'General Studies', 'code' => 'GS'],
            ['name' => 'Advanced Mathematics', 'code' => 'AMATH'],
            ['name' => 'Applied Mathematics', 'code' => 'APMATH'],
            ['name' => 'Further Mathematics', 'code' => 'FMATH'],
            ['name' => 'Physics (A-Level)', 'code' => 'PHYS-A'],
            ['name' => 'Chemistry (A-Level)', 'code' => 'CHEM-A'],
            ['name' => 'Biology (A-Level)', 'code' => 'BIOL-A'],
            ['name' => 'Geography (A-Level)', 'code' => 'GEO-A'],
            ['name' => 'History (A-Level)', 'code' => 'HIST-A'],
            ['name' => 'Economics (A-Level)', 'code' => 'ECON-A'],
            ['name' => 'Commerce (A-Level)', 'code' => 'COMM-A'],
            ['name' => 'Accountancy', 'code' => 'ACCT-A'],
            ['name' => 'Computer Science (A-Level)', 'code' => 'CSCI-A'],
            ['name' => 'Information and Computer Studies (A-Level)', 'code' => 'ICS-A'],
            ['name' => 'Kiswahili (A-Level)', 'code' => 'KISW-A'],
            ['name' => 'English (A-Level)', 'code' => 'ENGL-A'],
            ['name' => 'French (A-Level)', 'code' => 'FREN-A'],
            ['name' => 'Arabic (A-Level)', 'code' => 'ARAB-A'],
            ['name' => 'Chinese (A-Level)', 'code' => 'CHIN-A'],
            ['name' => 'Literature in English', 'code' => 'LIT-A'],
            ['name' => 'Fine Art (A-Level)', 'code' => 'FART-A'],
            ['name' => 'Music (A-Level)', 'code' => 'MUSI-A'],
            ['name' => 'Theatre Arts (A-Level)', 'code' => 'TART-A'],
            ['name' => 'Nutrition (A-Level)', 'code' => 'NUTR-A'],
            ['name' => 'Food and Nutrition (A-Level)', 'code' => 'FNUT-A'],
            ['name' => 'Agriculture (A-Level)', 'code' => 'AGRIC-A'],
            ['name' => 'Divinity', 'code' => 'DIV'],
            ['name' => 'Islamic Knowledge', 'code' => 'ISL'],
            ['name' => 'Christian Knowledge', 'code' => 'CK'],
            ['name' => 'Sociology', 'code' => 'SOC'],
            ['name' => 'Psychology', 'code' => 'PSY'],
            ['name' => 'Philosophy', 'code' => 'PHIL'],
        ];

        foreach ($subjects as $s) {
            GlobalSubject::firstOrCreate(
                ['name' => $s['name']],
                ['code' => $s['code']]
            );
        }
    }
}
