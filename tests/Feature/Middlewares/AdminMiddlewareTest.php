<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Middleware\CheckRoleUserIsAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function check_role_user_is_not_admin()
    {
        $user = User::factory()->state(['type'=>'user'])->create();
        $this->actingAs($user);

        $request = Request::create('admin/','GET');

        $middleware = new CheckRoleUserIsAdmin();
        $response = $middleware->handle($request,function(){});

        $this->assertEquals($response->getStatusCode(),302);
    }


    /**
     * check_role_user_is_not_admin
     *
     * @return void
     * @test
     */
    public function check_role_user_is_admin()
    {
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        $request = Request::create('admin/','GET');

        $middleware = new CheckRoleUserIsAdmin();
        $response = $middleware->handle($request,function(){});

        $this->assertEquals($response,null);
    }


    /**
     * check_role_user_is_not_logged_in
     *
     * @return void
     * @test
     */
    public function check_role_user_is_not_logged_in()
    {
        $request = Request::create('admin/','GET');

        $middleware = new CheckRoleUserIsAdmin();
        $response = $middleware->handle($request,function(){});

        $this->assertEquals($response->getStatusCode(),302);
    }
}
