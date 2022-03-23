<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileUploadTest extends TestCase
{
    protected $middleware = ['web','admin'];

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function upload_image()
    {
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('avatar.jpg');
        $hash_name = $file->hashName();

        $response = $this->postJson(route('admin.upload_file'),['image' => $file]);
        $response->assertJson([
            'url' => Storage::url('images/'.$hash_name),
            'success'=>true,
        ]);

        Storage::disk('public')->assertExists('images/'.$hash_name);

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }
}
