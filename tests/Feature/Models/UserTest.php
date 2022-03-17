<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function insert_data()
    {
        $data = User::factory()->make()->toArray();
        $data['password'] = 'test';
        User::create($data);
        $this->assertDatabaseHas('users', $data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function user_relationship_with_post()
    {
        $count = rand(1, 10);

        $user = User::factory()
            ->hasPosts($count)
            ->create();

        $this->assertCount($count,$user->posts);
        $this->assertTrue($user->posts()->first() instanceof Post);
    }

}
