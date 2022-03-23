<?php

namespace Tests\Feature\Middlewares;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Middleware\CheckUserActivity;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckUserActivityTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function check_user_active_in_cach_when_user_loggrd_in()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = Request::create('admin/','GET');

        $middleware = new CheckUserActivity();
        $middleware->handle($request,function(){});

        $this->assertEquals('active',Cache::get("user-{$user->id}-status"));
        $this->travel(11)->seconds();
        $this->assertNull(Cache::get("user-{$user->id}-status"));

    }

    /**
     * check_user_active_in_cach_when_user_not_loggrd_in
     *
     * @return void
     * @test
     */
    public function check_user_active_in_cach_when_user_not_loggrd_in()
    {
        $request = Request::create('admin/','GET');

        $middleware = new CheckUserActivity();
        $response = $middleware->handle($request,function(){});

        $this->assertNull($response);
    }


    /**
     * check_user_active_chache_set_on_web_middleware
     *
     * @return void
     * @test
     */
    public function check_user_active_chache_set_on_web_middleware()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = $this->get(route('home'));
        $request->assertOk();

        $this->assertEquals('active',Cache::get("user-{$user->id}-status"));
        $this->assertEquals(request()->route()->middleware() , ['web']);

    }
}
