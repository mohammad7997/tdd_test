<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Comment;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function insert_data()
    {
        $data = Comment::factory()->make()->toArray();
        Comment::create($data);
        $this->assertDatabaseHas('comments', $data);
    }
}
