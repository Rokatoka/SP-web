<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Role::truncate();

        Role::create([
            'name' => 'administrator',
            'description' => 'Администратор',
            'instrumental' => 'для администратора'
        ]);

        Role::create([
            'name' => 'doctor',
            'description' => 'Доктор',
            'instrumental' => 'для доктора'
        ]);

        Role::create([
            'name' => 'patient',
            'description' => 'Пациент',
            'instrumental' => 'для пациента'
        ]);

    }
}
