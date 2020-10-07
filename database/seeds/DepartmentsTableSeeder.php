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
        DB::statement("INSERT INTO `departments` (`id`, `name`) VALUES
        (1, 'Admin'),
        (2, 'IT'),
        (3, 'Business Development'),
        (4, 'Accounts'),
        (5, 'Service Delivery'),
        (6, 'HR'),
        (7, 'Management'),
        (8, 'Service Delivery One'),
        (9, 'Lakme'),
        (10, 'Service Delivery Two');");
    }
}
