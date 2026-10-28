<?php

namespace Tests\Feature;

use Database\Factories\ContactpersonFactory;
use Database\Factories\DepartmentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ContactPersonPhoneFixTest extends TestCase
{
    use RefreshDatabase;

    private function runMigration(): void
    {
        (require database_path('migrations/2026_10_28_220759_fix_phone_number_of_viktoria_ferdinandi.php'))->up();
    }

    public function test_the_missing_area_code_is_added(): void
    {
        $person = ContactpersonFactory::new()->create(['department_id' => DepartmentFactory::new()->create()->id, 'email' => 'v.ferdinandi@bereal-immobilien.at', 'phone' => '+43 535 36 19 887']);

        $this->runMigration();

        $this->assertSame('+43 1 535 36 19 887', DB::table('contactpeople')->where('id', $person->id)->value('phone'));
    }

    public function test_other_numbers_and_already_correct_numbers_are_left_alone(): void
    {
        $fixed = ContactpersonFactory::new()->create(['department_id' => DepartmentFactory::new()->create()->id, 'email' => 'v.ferdinandi@bereal-immobilien.at', 'phone' => '+43 1 535 36 19 887']);
        $other = ContactpersonFactory::new()->create(['department_id' => DepartmentFactory::new()->create()->id, 'email' => 'someone@bereal-immobilien.at', 'phone' => '+43 535 36 19 887']);

        $this->runMigration();

        $this->assertSame('+43 1 535 36 19 887', DB::table('contactpeople')->where('id', $fixed->id)->value('phone'));
        $this->assertSame('+43 535 36 19 887', DB::table('contactpeople')->where('id', $other->id)->value('phone'));
    }
}
