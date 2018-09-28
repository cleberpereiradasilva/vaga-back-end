<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class UserTest extends TestCase
{
    use DatabaseTransactions;    
    use WithFaker;

    public function testUserCreateTest()
    {        
        $data = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123123'
        ];
        $user = \App\User::create($data);  
        //password sera criptografado no banco de dados...   
        unset($data['password']);      
        $this->assertDatabaseHas('users', $data);
    }

    public function testUserUpdateTest()
    {
        $data = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123123'
        ];
        $new = [
            'name' => 'Administrador New',
            'email' => 'adm@adm.com.br',
            'password' => '123123'
        ];
        $user = \App\User::create($data);
        $user->update($new);

        //password sera criptografado no banco de dados...   
        unset($data['password']);  

        //password sera criptografado no banco de dados...   
        unset($new['password']);  
        //find new values
        $this->assertDatabaseHas('users', $new);
        //not find old values
        $this->assertDatabaseMissing('users', $data);
    }

    public function testUserDeleteTest()
    {
        $data = [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => '123123'
        ];
        $user = \App\User::create($data);
        
        //password sera criptografado no banco de dados...   
        unset($data['password']);  

        //confirm insert
        $this->assertDatabaseHas('users', $data);


        $user->delete();
        //confirm removed data
        $data['deleted_at'] = null; //o deleted_at pq estou usando softdelete
        $this->assertDatabaseMissing('users', $data);
    }


}
