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
    
    function getToken(){
        $user = \App\User::first();
        $token =  $user->createToken('MeContrata')->accessToken;        
        return ['HTTP_Authorization' =>  'Bearer '.$token];
    }

    function testLoginUserTest(){               
        $this->post('api/details', [], $this->getToken())
                ->assertStatus(200)
                ->assertJsonStructure([
                     '*' => [
                        "id",
                        "name",
                        "email",
                        "created_at",
                        "updated_at"
                    ]                
                ]);               
    }

    /**     
     * get /api/user | list user
     * @return void
     */
    public function testIndexUserTest()
    {      
        $this->get('api/user', $this->getToken())
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
        $this->get('api/user/'.$user->id, $this->getToken())
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
        $data_user = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123123'
        ];
        $user = \App\User::create($data_user);  
        $id = $user->id;
        $user->delete();

        $this->get('api/user/'.$id, $this->getToken())
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
            'password' => '1231234',
            'password_confirmation' => '1231234'
        ];        
       
       
        $this->post('api/user', $data, $this->getToken() )
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
        //nao tem password_confirmation no banco de dados...   
        unset($data['password_confirmation']);
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
            'password' => '1231234',
            'password_confirmation' => '1231234'          
        ];        
        $user = \App\User::create($data);

        $new = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail                       
        ];  

        $this->put('api/user/'.$user->id, $new, $this->getToken() )
            ->assertStatus(201);

        //password sera criptografado no banco de dados...   
        unset($new['password']);
        //nao tem password_confirmation no banco de dados...   
        unset($new['password_confirmation']);
        $this->assertDatabaseHas('users', $new);
    }

    /**     
     * put /api/user | update user
     * @return void
     */
    public function testPutUserNoConfirmationTest()
    {
        
        $data = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '1231234',
            'password_confirmation' => '1231234'          
        ];        
        $user = \App\User::create($data);

        $new = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '1231234',                      
        ];  

        $this->put('api/user/'.$user->id, $new, $this->getToken() )
            ->assertStatus(400);       
    }


    public function testPutUserConfirmationTest()
    {
        
        $data = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '1231234',
            'password_confirmation' => '1231234'          
        ];        
        $user = \App\User::create($data);

        $new = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '1231234',  
            'password_confirmation' => '1231234'
        ];  

        $this->put('api/user/'.$user->id, $new, $this->getToken() )
            ->assertStatus(201);       
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
            'password' => '1231234',
            'password_confirmation' => '1231234'
        ];        
        $user = \App\User::create($data);

        $this->delete('api/user/'.$user->id, [], $this->getToken() )
            ->assertStatus(200);         
        
        //password sera criptografado no banco de dados...   
        unset($data['password']);
        //nao tem password_confirmation no banco de dados...   
        unset($data['password_confirmation']);
        $data['deleted_at'] = null; //o deleted_at pq estou usando softdelete
        $this->assertDatabaseMissing('users', $data);
    }
}
