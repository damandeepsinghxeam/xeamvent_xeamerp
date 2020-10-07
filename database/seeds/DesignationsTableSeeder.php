<?php

use Illuminate\Database\Seeder;

class DesignationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("INSERT INTO `designations` (`id`, `name`, `short_name`, `isactive`, `hierarchy`, `band_id`, `sort_order`, `created_at`, `updated_at`) VALUES
        (1, 'Managing Director', 'MD', 1, 1, 1, 0, NULL, NULL),
        (2, 'Vice President', 'VP', 1, 2, 3, 0, NULL, NULL),
        (3, 'AVP', 'Assistant Vice President', 1, 3, 3, 0, NULL, NULL),
        (4, 'Sr. GM', 'Sr. General Manager', 1, 4, 3, 0, NULL, NULL),
        (5, 'GM', 'General Manager', 1, 5, 3, 0, NULL, NULL),
        (6, 'DGM', 'Sr. Deputy General Manager', 1, 6, 4, 0, NULL, NULL),
        (7, 'AGM', 'Assistant General Manager', 1, 7, 4, 0, NULL, NULL),
        (8, 'Sr.Manager', 'Sr. Manager', 1, 8, 5, 0, NULL, NULL),
        (9, 'Manager', 'Manager', 1, 9, 5, 0, NULL, NULL),
        (10, 'AM', 'Assistant Manager', 1, 10, 5, 0, NULL, NULL),
        (11, 'Sr.Executive', 'Sr.Executive', 1, 11, 6, 0, NULL, NULL),
        (12, 'Executive', 'Executive', 1, 12, 6, 0, NULL, NULL),
        (13, 'Trainee', 'Trainee', 1, 13, 6, 0, NULL, NULL)");
    }
}
