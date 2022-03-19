<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SingleControllerTest extends TestCase
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
        $post = Post::factory()->hasComments(rand(1,20))->create();

        $response = $this->get(route('single.index', $post->id));

        $response->assertOk();
        $response->assertViewIs('single');
        $response->assertViewHasAll([
            'post' => $post,
            'comments' => $post->comments()->latest()->paginate(15)
        ]);
    }

    /**
     * save_comment_when_user_logged_in
     *
     * @return void
     * @test
     *
     */
    public function save_comment_when_user_logged_in()
    {

        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $data = Comment::factory()
                ->state([
                    'user_id' => $user->id,
                    'commentable_id' => $post->id,
                ])
                ->make()
                ->toArray();

        $response = $this->postJson(
            route('single.save_comment',$post->id),
            [
                'content' => $data['content']
            ]
        );

        $this->assertDatabaseHas('comments',$data);
        // $response->assertRedirect(route('single.index', $post->id)); // for http
        $response->assertOk();
        $response->assertJson([
            'success' => true
        ]);

    }


    /**
     * save_comment_when_user_not_logged_in
     *
     * @return void
     * @test
     *
     */
    public function save_comment_when_user_not_logged_in()
    {
        // $this->withoutExceptionHandling();
        $post = Post::factory()->create();
        $data = Comment::factory()
                ->state([
                    'commentable_id' => $post->id,
                ])
                ->make()
                ->toArray();

        unset($data['user_id']);

        $response = $this->postJson(
            route('single.save_comment',$post->id),
            [
                'content' => $data['content']
            ]
        );

        // $response->assertRedirect(route('login'));
        $response->assertUnauthorized();
        $this->assertDatabaseMissing('comments',$data);

    }


    /**
     * comment_save_validation_error
     *
     * @return void
     * @test
     */
    public function comment_save_validation_error()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $data = Comment::factory()
                ->state([
                    'user_id' => $user->id,
                    'commentable_id' => $post->id,
                ])
                ->make()
                ->toArray();

        $response = $this->postJson(
            route('single.save_comment',$post->id),
            [
                'content' => ''
            ]
        );

        $response->assertJsonValidationErrors([
            'content'
        ]);

        // for http
        // $response->assertSessionHasErrors([
        //     'content'
        // ]);
    }
}
