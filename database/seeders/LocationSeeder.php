<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Region;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            'Arusha' => ['Arusha City', 'Arusha DC', 'Karatu', 'Longido', 'Meru', 'Monduli', 'Ngorongoro'],
            'Dar es Salaam' => ['Ilala', 'Kinondoni', 'Temeke', 'Kigamboni', 'Ubungo'],
            'Dodoma' => ['Dodoma City', 'Bahi', 'Chamwino', 'Chemba', 'Kondoa', 'Kongwa', 'Mpwapwa'],
            'Geita' => ['Bukombe', 'Chato', 'Geita DC', 'Geita TC', 'Mbogwe', 'Nyang\'hwale'],
            'Iringa' => ['Iringa MC', 'Iringa DC', 'Kilolo', 'Mufindi', 'Mafinga TC'],
            'Kagera' => ['Biharamulo', 'Bukoba MC', 'Bukoba DC', 'Karagwe', 'Kyerwa', 'Missenyi', 'Muleba', 'Ngara'],
            'Katavi' => ['Mpanda MC', 'Mpanda DC', 'Mlele', 'Tanganyika'],
            'Kigoma' => ['Kigoma-Ujiji MC', 'Kigoma DC', 'Kasulu TC', 'Kasulu DC', 'Kibondo', 'Kakonko', 'Uvinza'],
            'Kilimanjaro' => ['Moshi MC', 'Moshi DC', 'Hai', 'Siha', 'Rombo', 'Mwanga', 'Same'],
            'Lindi' => ['Lindi MC', 'Lindi DC', 'Kilwa', 'Liwale', 'Nachingwea', 'Ruangwa'],
            'Manyara' => ['Babati TC', 'Babati DC', 'Hanang', 'Kiteto', 'Mbulu', 'Simanjiro'],
            'Mara' => ['Musoma MC', 'Musoma DC', 'Bunda', 'Butiama', 'Rorya', 'Serengeti', 'Tarime'],
            'Mbeya' => ['Mbeya CC', 'Mbeya DC', 'Chunya', 'Kyela', 'Mbarali', 'Rungwe', 'Busokelo'],
            'Morogoro' => ['Morogoro MC', 'Morogoro DC', 'Gairo', 'Kilombero', 'Kilosa', 'Mvomero', 'Ulanga', 'Malinyi'],
            'Mtwara' => ['Mtwara MC', 'Mtwara DC', 'Masasi TC', 'Masasi DC', 'Nanyumbu', 'Newala', 'Tandahimba'],
            'Mwanza' => ['Ilemela MC', 'Nyamagana MC', 'Kwimba', 'Magu', 'Misungwi', 'Sengerema', 'Ukerewe'],
            'Njombe' => ['Njombe TC', 'Njombe DC', 'Ludewa', 'Makete', 'Wanging\'ombe', 'Makambako TC'],
            'Pemba North' => ['Micheweni', 'Wete'],
            'Pemba South' => ['Chake Chake', 'Mkoani'],
            'Pwani' => ['Kibaha TC', 'Kibaha DC', 'Bagamoyo', 'Kisarawe', 'Mafia', 'Mkuranga', 'Rufiji'],
            'Rukwa' => ['Sumbawanga MC', 'Sumbawanga DC', 'Kalambo', 'Nkasi'],
            'Ruvuma' => ['Songea MC', 'Ruvuma DC', 'Mbinga DC', 'Namtumbo', 'Nyasa', 'Tunduru'],
            'Shinyanga' => ['Kahama TC', 'Shinyanga MC', 'Shinyanga DC', 'Kishapu', 'Msalala'],
            'Simiyu' => ['Bariadi TC', 'Bariadi DC', 'Busega', 'Itilima', 'Maswa', 'Meatu'],
            'Singida' => ['Singida MC', 'Singida DC', 'Iramba', 'Ikungi', 'Manyoni', 'Mkalama'],
            'Songwe' => ['Mbozi', 'Ileje', 'Momba', 'Songwe DC'],
            'Tabora' => ['Tabora MC', 'Uyui DC', 'Igunga', 'Kaliua', 'Nzega', 'Sikonge', 'Urambo'],
            'Tanga' => ['Tanga CC', 'Handeni TC', 'Handeni DC', 'Kilindi', 'Korogwe TC', 'Korogwe DC', 'Lushoto', 'Mkinga', 'Muheza', 'Pangani'],
            'Zanzibar North' => ['Kaskazini A', 'Kaskazini B'],
            'Zanzibar South' => ['Kati', 'Kusini'],
            'Zanzibar West' => ['Mjini', 'Magharibi'],
        ];

        foreach ($locations as $regionName => $districts) {
            $region = Region::create(['name' => $regionName]);
            foreach ($districts as $districtName) {
                District::create([
                    'region_id' => $region->id,
                    'name' => $districtName,
                ]);
            }
        }
    }
}
