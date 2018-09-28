<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class ApiDependenteTest extends TestCase
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
     * get /api/dependente | list dependente
     * @return void
     */
    public function testIndexDependenteTest()
    {      
        $this->get('api/dependente', $this->getToken())
        ->assertStatus(200)
        ->assertJsonStructure([
                'data' => [
                    '*' => [
                        "id",
                        "nome",
                        "email",
                        "celular"
                    ]
                ]
            
        ]);
    }

    /**     
     * get /api/dependente/:id | show dependente
     * @return void
     */
    public function testGetDependenteFoundTest()
    {
        $user = \App\User::first();
        $data_cliente = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $cliente = \App\Cliente::create($data_cliente);
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber, 
            'cliente_id' => $cliente->id,
            'user_id' => $user->id
        ];
        $dependente = \App\Dependente::create($data);      
        $this->get('api/dependente/'.$dependente->id, $this->getToken())
            ->assertStatus(200)
            ->assertJsonStructure([                
                    "id",
                    "nome",
                    "email",
                    "celular"                    
                ]            
        );
    }

    /**     
     * get /api/dependente/:id | show dependente
     * @return void
     */
    public function testGetDependenteNotFoundTest()
    {
        $user = \App\User::first();
        $data_cliente = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $cliente = \App\Cliente::create($data_cliente);
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber, 
            'cliente_id' => $cliente->id,
            'user_id' => $user->id
        ];
        $dependente = \App\Dependente::create($data); 
        $id = $dependente->id;
        $dependente->delete();

        $this->get('api/dependente/'.$id, $this->getToken())
        ->assertStatus(404);        
    }

    /**     
     * post /api/dependente | create dependente
     * @return void
     */
    public function testPostDependenteTest()
    {
        $user = \App\User::first();
        $data_cliente = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $cliente = \App\Cliente::create($data_cliente);
        
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber,
            'cliente_id' => $cliente->id
        ];
        
        $this->post('api/dependente', $data, $this->getToken() )
            ->assertStatus(201)
            ->assertJsonStructure(
                [
                    "id",
                    "nome",
                    "celular"
                ]            
        );              
        $this->assertDatabaseHas('dependentes', $data);
    }

    /**     
     * put /api/dependente | update dependente
     * @return void
     */
    public function testPutDependenteTest()
    {
        
        $user = \App\User::first();
        $data_cliente = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $cliente = \App\Cliente::create($data_cliente);
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber,
            'cliente_id' => $cliente->id,
            'user_id' => $user->id
        ];        
        
        $dependente = \App\Dependente::create($data);

        $new = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber,
            'cliente_id' => $cliente->id,
            'user_id' => $user->id
        ];  


        $this->put('api/dependente/'.$dependente->id, $new, $this->getToken($user) )
            ->assertStatus(201);
        
        $this->assertDatabaseHas('dependentes', $new);
    }


    /**     
     * put /api/dependente | update dependente
     * @return void
     */
    public function testPutDependenteOtherUserTest()
    {
        
        $user = \App\User::first();
        $data_cliente = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $cliente = \App\Cliente::create($data_cliente);
        $data_user = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123123'
        ];
        $other_user = \App\User::create($data_user);  
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber,
            'cliente_id' => $cliente->id,
            'user_id' => $user->id
        ];        
        
        $dependente = \App\Dependente::create($data);

        $new = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber,
            'cliente_id' => $cliente->id,
            'user_id' => $user->id
        ];  

        $this->put('api/dependente/'.$dependente->id, $new, $this->getToken($other_user) )
            ->assertStatus(401);        
        
    }

    /**     
     * delete /api/cliente/:id | delete cliente
     * @return void
     */
    public function testDependenteClienteTest()
    {        
        $user = \App\User::first();
        $data_cliente = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $cliente = \App\Cliente::create($data_cliente);

        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber,
            'cliente_id' => $cliente->id,
            'user_id' => $user->id
        ];        
        
        $dependente = \App\Dependente::create($data);                    
        
        $this->delete('api/dependente/'.$dependente->id, [], $this->getToken($user))
                ->assertStatus(200);        

        $data['deleted_at'] = null; //o deleted_at pq estou usando softdelete
        $this->assertDatabaseMissing('dependentes', $data);
    }
}
