<?php

namespace Tests\Feature\Models;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait TraitModelTest{

    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function insert_data()
    {
        // get model and table name
        $model = $this->get_model();
        $table = $model->getTable();

        // data for insert in db
        $data = $model::factory()->make()->toArray();
        if ($model instanceof User){
            $data['password'] = 'test';
        }

        // save in db and assert
        $model::create($data);
        $this->assertDatabaseHas($table,$data);
    }

    abstract protected function get_model() : Model;
}

