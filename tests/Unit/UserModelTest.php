<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserModel()
    {
        $job_title = Str::random(10);
        $id = random_int(1,100);
        $email = Str::random(7) . '@mail.com';

        User::factory()->create([
            'id'=> $id,
            'email' => $email
        ]);

         UserInfo::factory()->create([
            'user_id' => $id,
            'job_title' => $job_title
        ]);

         $this->assertDatabaseHas('users', [
            'id'=> $id,
            'email' => $email,
        ]);

          $this->assertDatabaseHas('user_info', [
            'user_id' => $id,
            'job_title' => $job_title
        ]);
    }
}
