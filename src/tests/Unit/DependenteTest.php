<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class DependenteTest extends TestCase
{
    use DatabaseTransactions;    
    use WithFaker;

    public function testDependenteCreateTest()
    {        
        $user = \App\User::first();
        $cliente = \App\Cliente::first();
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber,
            'cliente_id' => $cliente->id,
            'user_id' => $user->id
        ];
        $dependente = \App\Dependente::create($data);        
        $this->assertDatabaseHas('dependentes', $data)
            ->assertEquals($dependente->cliente->name, $cliente->name);
    }

    public function testUpdateTest()
    {
        $user = \App\User::first();
        $cliente = \App\Cliente::first();
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber,
            'cliente_id' => $cliente->id,
            'user_id' => $user->id
        ];
        $new = [
            'nome' => 'Administrador New',
            'email' => 'adm@adm.com.br',           
        ];

        $dependente = \App\Dependente::create($data); 
        $dependente->update($new);
        //find new values
        $this->assertDatabaseHas('dependentes', $new);
        //not find old values
        $this->assertDatabaseMissing('dependentes', $data);
    }

    public function testDeleteTest()
    {
        $user = \App\User::first();
        $cliente = \App\Cliente::first();
        $data = [
            'nome' => $this->faker->firstName(),
            'email' => $this->faker->email(),
            'celular' => $this->faker->phoneNumber,
            'cliente_id' => $cliente->id,
            'user_id' => $user->id
        ];
        $dependente = \App\Dependente::create($data); 
        
        //confirm insert
        $this->assertDatabaseHas('dependentes', $data);
        $dependente->delete();
        //confirm removed data
        $data['deleted_at'] = null; //o deleted_at pq estou usando softdelete
        $this->assertDatabaseMissing('dependentes', $data);
    }

}
