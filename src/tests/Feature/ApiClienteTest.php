<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class ApiClienteTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;   
    
    function getToken($user = null){
        if($user == null){
            $user = \App\User::first();
        }
        $token =  $user->createToken('MeContrata')->accessToken;        
        return ['HTTP_Authorization' =>  'Bearer '.$token];
    }

    function testLoginUserTest(){               
        $this->post('api/details', [], $this->getToken())
                ->assertStatus(200)
                ->assertJsonStructure([
                     'user' => [                         
                            "id",
                            "name",
                            "email",
                            "created_at",
                            "updated_at"
                         ]                         
                ]);
    }

    /**     
     * get /api/cliente | list cliente
     * @return void
     */
    public function testIndexClienteTest()
    {      
        $this->get('api/cliente', $this->getToken())
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [      
                    '*' => [
                        "id",
                        "nome",
                        "email",
                        "telefone"
                    ]
                ]
            
        ]);
    }

    /**     
     * get /api/cliente/:id | show cliente
     * @return void
     */
    public function testGetClienteFoundTest()
    {
        $user = \App\User::first();
        $data_cliente = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $cliente = \App\Cliente::create($data_cliente);             
        $this->get('api/cliente/'.$cliente->id, $this->getToken())
            ->assertStatus(200)
            ->assertJsonStructure([                
                    "id",
                    "nome",
                    "email",
                    "telefone",
                    "dependentes",
                    "user"
                ]            
        );
    }

    /**     
     * get /api/cliente/:id | show cliente
     * @return void
     */
    public function testGetClienteNotFoundTest()
    {
        $user = \App\User::first();
        $data_cliente = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $cliente = \App\Cliente::create($data_cliente);
        $id = $cliente->id;
        $cliente->delete();

        $this->get('api/cliente/'.$id, $this->getToken())
        ->assertStatus(404);        
    }

    /**     
     * post /api/cliente | create cliente
     * @return void
     */
    public function testPostClienteTest()
    {
        $user = \App\User::first();
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        
       
        $this->post('api/cliente', $data, $this->getToken() )
            ->assertStatus(201)
            ->assertJsonStructure(
                 [
                    "id",
                    "nome",
                    "telefone"                    
                ]
            
        );              
        $this->assertDatabaseHas('clientes', $data);
    }

    /**     
     * put /api/cliente | update cliente
     * @return void
     */
    public function testPutClienteTest()
    {
        
        $user = \App\User::first();
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];        
        
        $cliente = \App\Cliente::create($data);

        $new = [
            'nome' => $this->faker->firstName(),
            'telefone' => $this->faker->phoneNumber,
            'email' => $this->faker->email()
        ];  

        $this->put('api/cliente/'.$cliente->id, $new, $this->getToken($user) )
            ->assertStatus(201);
        
        $this->assertDatabaseHas('clientes', $new);
    }


    /**     
     * put /api/cliente | update cliente
     * @return void
     */
    public function testPutClienteOtherUserTest()
    {
        
        $user = \App\User::first();
        $data_user = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123123'
        ];
        $other_user = \App\User::create($data_user); 

        
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];        
        $cliente = \App\Cliente::create($data);  
        
        
        $new = [
            'nome' => $this->faker->firstName(),
            'telefone' => $this->faker->phoneNumber,
            'email' => $this->faker->email()
        ]; 
                
        //retorna erro que cliente nao pertence ao usuario
        $this->put('api/cliente/'.$cliente->id, $new, $this->getToken($other_user))
                ->assertStatus(401);        
    }

    /**     
     * delete /api/cliente/:id | delete cliente
     * @return void
     */
    public function testDeleteClienteTest()
    {        
        $user = \App\User::first();
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];        
        $cliente = \App\Cliente::create($data);                      

        $this->delete('api/cliente/'.$cliente->id, [], $this->getToken($user))
                ->assertStatus(200);        

        $data['deleted_at'] = null; //o deleted_at pq estou usando softdelete
        $this->assertDatabaseMissing('clientes', $data);
    }
}
