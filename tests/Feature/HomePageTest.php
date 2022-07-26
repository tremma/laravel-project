<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHome()
    {
        $response = $this->get('');

        $response = assertStatus(200);

        $response->assertViewIs('users');

        $response->assertSeeText("Список пользователей", $escaped = true);
    }
}
