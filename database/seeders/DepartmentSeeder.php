<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->truncate();


        $mainDepartments = Department::factory()->count(10)->create();

        foreach ($mainDepartments as $department) {
            Department::factory()->count(5)->create(['department_id' => $department->id, 'company' => null]);
        }
    }
}
