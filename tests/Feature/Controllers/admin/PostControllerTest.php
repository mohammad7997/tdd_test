<?php

namespace Tests\Feature\Controllers\admin;

use Tests\TestCase;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function index_method()
    {
        $this->withoutExceptionHandling();
        Post::factory()->count(100)->create();
        $response = $this->get(route('admin.posts.index'));
        $response->assertOk();
        $response->assertViewIs('admin.post.index');
        $response->assertViewHas('posts',Post::latest()->paginate(15));
    }

    /**
     * create_method
     *
     * @return void
     * @test
     */
    public function create_method()
    {
        Tag::factory()->count(100)->create();
        $response = $this->get(route('admin.posts.create'));
        $response->assertOk();
        $response->assertViewIs('admin.post.create');
        $response->assertViewHas('tags',Tag::latest()->get());
    }

    /**
     * edit_method
     *
     * @return void
     * @test
     */
    public function edit_method()
    {
        $post = Post::factory()->create();
        Tag::factory()->count(100)->create();
        $response = $this->get(route('admin.posts.edit',$post->id));
        $response->assertOk();
        $response->assertViewIs('admin.post.edit');
        $response->assertViewHasAll([
            'tags'=>Tag::latest()->get(),
            'post' => $post
        ]);
    }
}
