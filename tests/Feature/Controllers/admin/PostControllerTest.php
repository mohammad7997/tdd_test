<?php

namespace Tests\Feature\Controllers\admin;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;


    protected $middleware = ['web','admin'];


    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function index_method()
    {
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        Post::factory()->count(100)->create();
        $response = $this->get(route('admin.posts.index'));
        $response->assertOk();
        $response->assertViewIs('admin.post.index');
        $response->assertViewHas('posts',Post::latest()->paginate(15));

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }

    /**
     * create_method
     *
     * @return void
     * @test
     */
    public function create_method()
    {
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        Tag::factory()->count(100)->create();
        $response = $this->get(route('admin.posts.create'));
        $response->assertOk();
        $response->assertViewIs('admin.post.create');
        $response->assertViewHas('tags',Tag::latest()->get());

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }

    /**
     * edit_method
     *
     * @return void
     * @test
     */
    public function edit_method()
    {
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        $post = Post::factory()->create();
        Tag::factory()->count(100)->create();
        $response = $this->get(route('admin.posts.edit',$post->id));
        $response->assertOk();
        $response->assertViewIs('admin.post.edit');
        $response->assertViewHasAll([
            'tags'=>Tag::latest()->get(),
            'post' => $post
        ]);

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }


    /**
     * store_methode
     *
     * @return void
     * @test
     */
    public function store_methode()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        $data = Post::factory()->state(['user_id'=>$user->id])->make()->toArray();
        $tags = Tag::factory()->count(10)->create();

        $response = $this->post(route('admin.posts.store'),
            array_merge(
                ['tags'=>$tags->pluck('id')->toArray()],
                $data
            )
        );

        $this->assertDatabaseHas('posts',$data);
        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('message');
        $this->assertEquals(
            $tags->pluck('id')->toArray(),
            Post::where($data)->first()->tags->pluck('id')->toArray()
        );

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }


    /**
     * update_methode
     *
     * @return void
     * @test
     */
    public function update_methode()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        $post = Post::factory()->state(['user_id'=>$user->id])->hasTags(10)->create();
        $data = Post::factory()->state(['id'=>$post->id,'user_id'=>$user->id])->make()->toArray();
        $tags = Tag::factory()->count(10)->create();

        $response = $this->put(route('admin.posts.update',$post->id),
            array_merge(
                ['tags'=>$tags->pluck('id')->toArray()],
                $data
            )
        );

        $this->assertDatabaseHas('posts',$data);
        $response->assertRedirect(route('admin.posts.index'));
        $response->assertSessionHas('message');
        $this->assertEquals(
            $tags->pluck('id')->toArray(),
            Post::where($data)->first()->tags->pluck('id')->toArray()
        );

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }


    /**
     * validate_required_data
     *
     * @return void
     * @test
     */
    public function validate_required_data()
    {
        $this->withExceptionHandling();
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        $errors = ['title','image','description','tags'];

        $response_store = $this->post(route('admin.posts.store'),[]);
        $response_store->assertSessionHasErrors($errors);

        $post = Post::factory()->create();
        $response_update = $this->put(route('admin.posts.update',$post->id),[]);
        $response_update->assertSessionHasErrors($errors);

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }


    /**
     * validate_image_url
     *
     * @return void
     * @test
     */
    public function validate_image_url()
    {
        $this->withExceptionHandling();
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        $data = ['image' => 'sss'];
        $errors = ['image'=>'The image must be a valid URL.'];

        $response_store = $this->post(route('admin.posts.store'),$data);
        $response_store->assertSessionHasErrors($errors);

        $post = Post::factory()->create();
        $response_update = $this->put(route('admin.posts.update',$post->id),$data);
        $response_update->assertSessionHasErrors($errors);

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }


    /**
     * validate_array
     *
     * @return void
     * @test
     */
    public function validate_array()
    {
        $this->withExceptionHandling();
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        $data = ['tags' => 'sss'];
        $errors = ['tags'=>'The tags must be an array.'];

        $response_store = $this->post(route('admin.posts.store'),$data);
        $response_store->assertSessionHasErrors($errors);

        $post = Post::factory()->create();
        $response_update = $this->put(route('admin.posts.update',$post->id),$data);
        $response_update->assertSessionHasErrors($errors);

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }


    /**
     * validate_exists
     *
     * @return void
     * @test
     */
    public function validate_exists()
    {
        $this->withExceptionHandling();
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        $data = ['tags' => [0]];
        $errors = ['tags.0'=>'The selected tags.0 is invalid.'];

        $response_store = $this->post(route('admin.posts.store'),$data);
        $response_store->assertSessionHasErrors($errors);

        $post = Post::factory()->create();
        $response_update = $this->put(route('admin.posts.update',$post->id),$data);
        $response_update->assertSessionHasErrors($errors);

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }


    /**
     * destroy_method
     *
     * @return void
     * @test
     */
    public function destroy_method()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);

        $post = Post::factory()->hasTags(10)->hasComments(10)->create();
        $comment = $post->comments()->first();

        $response = $this->delete(route('admin.posts.destroy',$post->id));
        $response->assertRedirect(route('admin.posts.index'));

        $this->assertDeleted($comment);
        $this->assertEmpty($post->tags);
        $this->assertDeleted($post);

        $this->assertEquals(
            request()->route()->middleware(),
            $this->middleware
        );
    }
}
