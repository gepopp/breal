<?php

namespace Database\Seeders;

use App\Models\Contactperson;
use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactpersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contactpeople')->truncate();


        $departments = Department::whereNotNull('department_name')->get();

        foreach ($departments as $department) {
            Contactperson::factory()->count(random_int(3,8))->create(['department_id' => $department->id]);
        }
    }
}
