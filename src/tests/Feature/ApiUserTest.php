<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class ApiUserTest extends TestCase
{
    use WithFaker;

    use DatabaseTransactions;    

    /**     
     * get /api/user | list user
     * @return void
     */
    public function testIndexUserTest()
    {
        $this->get('api/user')
        ->assertStatus(200)
        ->assertJsonStructure([
                '*' => [
                    "id",
                    "name",
                    "email",
                    "email_verified_at",
                    "created_at",
                    "updated_at"
                ]
            
        ]);
    }

    /**     
     * get /api/user/:id | show user
     * @return void
     */
    public function testGetUserFoundTest()
    {
        $user = \App\User::first();

        $this->get('api/user/'.$user->id)
        ->assertStatus(200)
        ->assertJsonStructure([                
                    "id",
                    "name",
                    "email",
                    "email_verified_at",
                    "created_at",
                    "updated_at"
                ]            
        );
    }

    /**     
     * get /api/user/:id | show user
     * @return void
     */
    public function testGetUserNotFoundTest()
    {
        $user = \App\User::first();
        $id = $user->id;
        $user->delete();

        $this->get('api/user/'.$id)
        ->assertStatus(404);        
    }

    /**     
     * post /api/user | create user
     * @return void
     */
    public function testPostUserTest()
    {
        
        $data = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123123'
        ];        
        $this->post('api/user', $data )
            ->assertStatus(201)
            ->assertJsonStructure(
                 [
                    "id",
                    "name",
                    "email",                    
                    "created_at",
                    "updated_at"
                ]
            
        );        
        $this->assertDatabaseHas('users', $data);
    }

    /**     
     * put /api/user | update user
     * @return void
     */
    public function testPutUserTest()
    {
        
        $data = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123123'
        ];        
        $content = $this->post('api/user', $data )
            ->assertStatus(201);

        // to get id
        $user = json_decode($content->getContent());   
        $new = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123123'
        ];  

        $this->put('api/user/'.$user->id, $new )
            ->assertStatus(201);

        $this->assertDatabaseHas('users', $new);
    }

    /**     
     * delete /api/user/:id | delete user
     * @return void
     */
    public function testDeleteUserTest()
    {        
        $data = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123123'
        ];        
        $content = $this->post('api/user', $data )
            ->assertStatus(201);    
                              
        // to get id
        $user = json_decode($content->getContent());

        $this->delete('api/user/'.$user->id, $data )
            ->assertStatus(200);         
              
        $this->assertDatabaseMissing('users', $data);
    }
}