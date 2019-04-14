<?php

use Illuminate\Database\Seeder;
use App\Hospital;

class HospitalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospital = Hospital::create([
            'name' => 'National Center for Maternity and Childhood',
            'address' => 'Astana, 32 Turan Avenue'
        ]);

        $hospital = Hospital::create([
            'name' => 'Republican Diagnostic Center',
            'address' => 'Astana, st. Syganak, 2'
        ]);

        $hospital = Hospital::create([
            'name' => 'National Center for Childrens Rehabilitation',
            'address' => 'Astana, Turan Avenue, 36'
        ]);

        $hospital = Hospital::create([
            'name' => 'National Science Center for Oncology and Transplantology',
            'address' => 'Astana, st. Kerey Zhanibek Khandar, 3.'
        ]);
    }
}
