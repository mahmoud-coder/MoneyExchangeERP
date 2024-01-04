<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach([
            ['name'=>'Odeta', 'surname'=>'Muižininkienė', 'duty'=>'Accountant', 'salary' => 872.24, 'pension'=> 0.0, 'social_insurance'=>1.77, 'apply_tax_free_amount' => true, 'joined_at'=>'2023-05-01'],
            ['name'=>'Amr', 'surname'=>'Gamaleldin', 'duty'=>'Director', 'salary' => 1361.61, 'pension'=> 0.0, 'social_insurance'=>1.77, 'apply_tax_free_amount' => true, 'joined_at'=>'2023-05-01'],
            ['name'=>'Tomas', 'surname'=>'Gėdrimas', 'duty'=>'Head of Law', 'salary' => 573.93, 'pension'=> 3.0, 'social_insurance'=>1.77, 'apply_tax_free_amount' => false, 'joined_at'=>'2023-05-01'],
            ['name'=>'Pavel', 'surname'=>'Hardzeichyk', 'duty'=>'AML Manager', 'salary' => 1332.89, 'pension'=> 3.0, 'social_insurance'=>1.77, 'apply_tax_free_amount' => true, 'joined_at'=>'2023-05-01'],
            ['name'=>'Ahmed', 'surname'=>'Mahmoud', 'duty'=>'Head of Public Relations', 'salary' => 2750, 'pension'=> 0.0, 'social_insurance'=>1.77, 'apply_tax_free_amount' => true, 'joined_at'=>'2023-05-01']
        ] as $employee){
            Employee::create($employee);
        }
    }
}
