<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class ClienteTest extends TestCase
{
    use DatabaseTransactions;    
    use WithFaker;

    public function testClienteCreateTest()
    {        
        $user = \App\User::first();
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $cliente = \App\Cliente::create($data);        
        $this->assertDatabaseHas('clientes', $data);
    }

    public function testUpdateTest()
    {
        $user = \App\User::first();
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $new = [
            'nome' => 'Administrador New',
            'email' => 'adm@adm.com.br',           
        ];

        $cliente = \App\Cliente::create($data); 
        $cliente->update($new);
        //find new values
        $this->assertDatabaseHas('clientes', $new);
        //not find old values
        $this->assertDatabaseMissing('clientes', $data);
    }

    public function testDeleteTest()
    {
        $user = \App\User::first();
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'telefone' => $this->faker->phoneNumber,
            'user_id' => $user->id
        ];
        $cliente = \App\Cliente::create($data); 
        
        //confirm insert
        $this->assertDatabaseHas('clientes', $data);
        $cliente->delete();
        //confirm removed data
        $data['deleted_at'] = null; //o deleted_at pq estou usando softdelete
        $this->assertDatabaseMissing('clientes', $data);
    }

}
