<?php

namespace Tests\Unit;
use Illuminate\Http\Response;
use App\Models\Role;

use Tests\TestCase;

class ExampleTest extends TestCase
{

   /**
     * A basic test example.
     *
     * @return void
     */
    public function test_unit_has_some_value() {
        $user ="Some value";
        $this->assertNotEmpty($user);
    }
}
