<?php

use Illuminate\Database\Seeder;
use App\Positions;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $position = Positions::create([
            'name' => 'gynecologist'
        ]);


        $position = Positions::create([
            'name' => 'anesthetist'
        ]);


        $position = Positions::create([
            'name' => 'surgeon'
        ]);


        $position = Positions::create([
            'name' => 'urologist'
        ]);


        $position = Positions::create([
            'name' => 'endoscopist'
        ]);


        $position = Positions::create([
            'name' => 'ophthalmologist'
        ]);


        $position = Positions::create([
            'name' => 'physiotherapist'
        ]);


        $position = Positions::create([
            'name' => 'neurologist'
        ]);
    }
}
