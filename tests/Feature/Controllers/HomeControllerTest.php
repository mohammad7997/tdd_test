<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     *
     */
    public function test_index()
    {
        // $this->withoutExceptionHandling();
        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertViewIs('home');
    }
}
