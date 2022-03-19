<?php

namespace Tests\Feature\Models;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTest extends TestCase
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
     *
     */
    public function tag_relationship_with_post()
    {
        $count = rand(1,10);
        $tags = Tag::factory()
                ->hasPosts($count)
                ->create();

        $this->assertCount($count,$tags->posts);
        $this->assertTrue($tags->posts()->first() instanceof Post);
    }
}
