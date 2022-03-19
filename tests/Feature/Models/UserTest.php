<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase,TraitModelTest;

    protected function get_model()
    {
        return new Post();
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

    /**
     * user_relationship_with_comment
     *
     * @return void
     * @test
     */
    public function user_relationship_with_comment()
    {
        $count = rand(1, 10);

        $user = User::factory()
                ->hasComments($count)
                ->create();

        $this->assertCount($count,$user->comments);
        $this->assertTrue($user->comments->first() instanceof Comment);
    }

}
