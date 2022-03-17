<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
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
        $data = Post::factory()->make()->toArray();
        Post::create($data);
        $this->assertDatabaseHas('posts',$data);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function post_relationship_with_user()
    {
        $post = Post::factory()
                ->for(User::factory())
                ->create();
                
        $this->assertTrue(isset($post->user->id));
        $this->assertTrue($post->user instanceof User);
    }
}
