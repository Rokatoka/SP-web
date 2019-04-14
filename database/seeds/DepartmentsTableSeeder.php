<?php

use Illuminate\Database\Seeder;
use App\Department;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department = Department::create([
            'hospital_id' => 1,
            'name' => 'Obstetric and gynecological emergency department',
            'position_id' => 1
        ]);

        $department = Department::create([
            'hospital_id' => 1,
            'name' => 'Department of Female Diseases',
            'position_id' => 1
        ]);

        $department = Department::create([
            'hospital_id' => 1,
            'name' => 'Department of Anesthesia, Resuscitation and Intensive Care',
            'position_id' => 2
        ]);

        $department = Department::create([
            'hospital_id' => 1,
            'name' => 'Department of Urology',
            'position_id' => 4
        ]);



        $department = Department::create([
            'hospital_id' => 2,
            'name' => 'Physiotherapy department',
            'position_id' => 7
        ]);

        $department = Department::create([
            'hospital_id' => 2,
            'name' => 'Endoscopy department',
            'position_id' => 5
        ]);

        $department = Department::create([
            'hospital_id' => 2,
            'name' => 'Department of Ultrasound Diagnostics',
            'position_id' => 7
        ]);

        $department = Department::create([
            'hospital_id' => 2,
            'name' => 'Department of Ophthalmology',
            'position_id' => 6
        ]);



        $department = Department::create([
            'hospital_id' => 3,
            'name' => 'Laboratory modeling and orthotics',
            'position_id' => 7
        ]);

        $department = Department::create([
            'hospital_id' => 3,
            'name' => 'Laboratory of robotic and functional rehabilitation',
            'position_id' => 7
        ]);

        $department = Department::create([
            'hospital_id' => 3,
            'name' => 'Psychoneurological Department',
            'position_id' => 8
        ]);




        $department = Department::create([
            'hospital_id' => 4,
            'name' => 'Department of Paid Surgery',
            'position_id' => 3
        ]);

        $department = Department::create([
            'hospital_id' => 4,
            'name' => 'Department of Multidisciplinary Surgery',
            'position_id' => 3
        ]);

        $department = Department::create([
            'hospital_id' => 4,
            'name' => 'Department of Vascular Surgery',
            'position_id' => 3
        ]);

        $department = Department::create([
            'hospital_id' => 4,
            'name' => 'Department of kidney transplantation and urology',
            'position_id' => 4
        ]);

    }
}
