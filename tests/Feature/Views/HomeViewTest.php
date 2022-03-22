<?php

namespace Tests\Feature\Views;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeViewTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function home_rendered_for_admin_user()
    {
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);
        $view = $this->view('home');
        $view->assertSee('<a> Test for admin type </a>',false);
    }

    /**
     * home_rendered_for_not_admin_user
     *
     * @return void
     * @test
     */
    public function home_rendered_for_not_admin_user()
    {
        $user = User::factory()->state(['type'=>'user'])->create();
        $this->actingAs($user);
        $view = $this->view('home');
        $view->assertDontSee('<a> Test for admin type </a>',false);
    }
}
