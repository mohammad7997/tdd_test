<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase,TraitModelTest;

    protected function get_model()
    {
        return new Post();
    }

    /**
     * comment_relationship_with_post
     *
     * @return void
     * @test
     */
    public function comment_relationship_with_post()
    {
        $comment = Comment::factory()
                ->hasCommentable(Post::factory())
                ->create();

        $this->assertTrue(isset($comment->commentable->id));
        $this->assertTrue($comment->commentable->first() instanceof Post);
    }

    /**
     * comment_relationship_with_user
     *
     * @return void
     * @test
     */
    public function comment_relationship_with_user()
    {
        $comment = Comment::factory()
                    ->for(User::factory())
                    ->create();
        $this->assertTrue(isset($comment->user->id));
        $this->assertTrue($comment->user instanceof User);
    }
}
