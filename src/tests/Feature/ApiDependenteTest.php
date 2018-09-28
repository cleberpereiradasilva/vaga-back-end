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
        $dependente = \App\Dependente::first();              
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
        $dependente = \App\Dependente::first();
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
        $cliente = \App\Cliente::first();
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
        
        $cliente = \App\Cliente::first();
        $user = \App\User::first();
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
        
        $cliente = \App\Cliente::first();
        $user = \App\User::first();
        $other_user = \App\User::where('id','!=',$user->id)->first();
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
        $cliente = \App\Cliente::first();
        $user = \App\User::first();
        $other_user = \App\User::where('id','!=',$user->id)->first();
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
