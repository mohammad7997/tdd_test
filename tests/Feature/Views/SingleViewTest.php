<?php

namespace Tests\Feature\Views;

use DOMXPath;
use DOMDocument;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SingleViewTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function show_form_in_single_view_when_user_logged_in()
    {
        $post = Post::factory()->create();
        $comments = Comment::factory()->count(4)->create();

        $user = User::factory()->create();

        $this->actingAs($user);
        $view = (string) $this
        ->view(
            'single',
            compact(['post','comments'])
        );

        $dom = new DOMDocument();
        $dom->loadHTML($view);
        $dom = new DOMXPath($dom);

        $action = route('single.save_comment',$post->id);
        $form = $dom->query("//form[@method='POST'][@action='$action']/textarea[@name='content']");
        // dd($dom);
        $this->assertCount(1,$form);
    }


    /**
     * show_form_in_single_view_when_user_logged_out
     *
     * @return void
     * @test
     */
    public function show_form_in_single_view_when_user_logged_out()
    {
        $post = Post::factory()->create();
        $comments = Comment::factory()->count(4)->create();

        $view = (string) $this
        ->view(
            'single',
            compact(['post','comments'])
        );

        $dom = new DOMDocument();
        $dom->loadHTML($view);
        $dom = new DOMXPath($dom);

        $action = route('single.save_comment',$post->id);
        $form = $dom->query("//form[@method='POST'][@action='$action']/textarea[@name='content']");
        // dd($dom);
        $this->assertCount(0,$form);
    }
}
