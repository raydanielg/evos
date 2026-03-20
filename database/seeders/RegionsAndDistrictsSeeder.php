<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsAndDistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            'Arusha' => ['Arusha CC', 'Arumeru', 'Karatu', 'Longido', 'Meru', 'Monduli', 'Ngorongoro'],
            'Dar es Salaam' => ['Ilala', 'Kinondoni', 'Temeke', 'Kigamboni', 'Ubungo'],
            'Dodoma' => ['Bahi', 'Chamwino', 'Chemba', 'Dodoma CC', 'Kondoa', 'Kongwa', 'Mpwapwa'],
            'Geita' => ['Bukombe', 'Chato', 'Geita DC', 'Geita TC', 'Mbogwe', "Nyang'hwale"],
            'Iringa' => ['Iringa DC', 'Iringa MC', 'Kilolo', 'Mafinga TC', 'Mufindi'],
            'Kagera' => ['Biharamulo', 'Bukoba DC', 'Bukoba MC', 'Karagwe', 'Kyerwa', 'Missenyi', 'Muleba', 'Ngara'],
            'Katavi' => ['Mlele', 'Mpanda DC', 'Mpanda MC', 'Tanganyika'],
            'Kigoma' => ['Buhigwe', 'Kakonko', 'Kasulu DC', 'Kasulu TC', 'Kibondo', 'Kigoma DC', 'Kigoma Ujiji MC', 'Uvinza'],
            'Kilimanjaro' => ['Hai', 'Moshi DC', 'Moshi MC', 'Mwanga', 'Rombo', 'Same', 'Siha'],
            'Lindi' => ['Kilwa', 'Lindi DC', 'Lindi MC', 'Liwale', 'Nachingwea', 'Ruangwa'],
            'Manyara' => ['Babati DC', 'Babati TC', 'Hanang', 'Kiteto', 'Mbulu', 'Simanjiro'],
            'Mara' => ['Bunda DC', 'Bunda TC', 'Butiama', 'Musoma DC', 'Musoma MC', 'Rorya', 'Serengeti', 'Tarime DC', 'Tarime TC'],
            'Mbeya' => ['Busokelo', 'Chunya', 'Ileje', 'Kyela', 'Mbeya CC', 'Mbeya DC', 'Rungwe'],
            'Morogoro' => ['Gairo', 'Ifakara TC', 'Kilombero', 'Kilosa', 'Morogoro MC', 'Mvomero', 'Ulanga', 'Malinyi'],
            'Mtwara' => ['Masasi DC', 'Masasi TC', 'Mtwara DC', 'Mtwara MC', 'Nanyumbu', 'Newala DC', 'Newala TC', 'Tandahimba'],
            'Mwanza' => ['Ilemela', 'Kwimba', 'Magu', 'Misungwi', 'Nyamagana', 'Sengerema', 'Ukerewe'],
            'Njombe' => ['Ludewa', 'Makambako TC', 'Njombe DC', 'Njombe TC', "Wanging'ombe"],
            'Pwani' => ['Bagamoyo', 'Kibaha DC', 'Kibaha TC', 'Kisarawe', 'Mafia', 'Mkuranga', 'Rufiji'],
            'Rukwa' => ['Kalambo', 'Nkasi', 'Sumbawanga DC', 'Sumbawanga MC'],
            'Ruvuma' => ['Madaba', 'Mbinga', 'Namtumbo', 'Nyasa', 'Songea DC', 'Songea MC', 'Tunduru'],
            'Shinyanga' => ['Kahama TC', 'Kishapu', 'Shinyanga DC', 'Shinyanga MC'],
            'Simiyu' => ['Bariadi DC', 'Bariadi TC', 'Busega', 'Itilima', 'Maswa', 'Meatu'],
            'Singida' => ['Ikungi', 'Iramba', 'Manyoni', 'Mkalama', 'Singida DC', 'Singida MC'],
            'Songwe' => ['Ileje', 'Mbozi', 'Momba', 'Songwe'],
            'Tabora' => ['Igunga', 'Kaliua', 'Nzega', 'Sikonge', 'Tabora MC', 'Urambo', 'Uyui'],
            'Tanga' => ['Handeni DC', 'Handeni TC', 'Kilindi', 'Korogwe DC', 'Korogwe TC', 'Lushoto', 'Mkinga', 'Muheza', 'Pangani', 'Tanga CC'],

            'Mjini Magharibi' => ['Mjini', 'Magharibi'],
            'Kaskazini Unguja' => ['Kaskazini A', 'Kaskazini B'],
            'Kusini Unguja' => ['Kusini'],
            'Kati Unguja' => ['Kati'],
            'Kaskazini Pemba' => ['Micheweni', 'Wete'],
            'Kusini Pemba' => ['Chake Chake', 'Mkoani'],
        ];

        foreach ($regions as $regionName => $districts) {
            DB::table('regions')->updateOrInsert(
                ['name' => $regionName],
                ['name' => $regionName, 'updated_at' => now(), 'created_at' => now()]
            );

            $regionId = DB::table('regions')->where('name', $regionName)->value('id');

            foreach ($districts as $districtName) {
                DB::table('districts')->updateOrInsert(
                    ['region_id' => $regionId, 'name' => $districtName],
                    ['region_id' => $regionId, 'name' => $districtName, 'updated_at' => now(), 'created_at' => now()]
                );
            }
        }
    }
}
