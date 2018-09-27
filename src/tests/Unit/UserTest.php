<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;    

    public function testCreateTest()
    {        
        $data = [
            'name' => 'Administrador',
            'email' => 'adm@adm.com',
            'password' => '123123'
        ];
        $user = \App\User::create($data);        
        $this->assertDatabaseHas('users', $data);
    }

    public function testUpdateTest()
    {
        $data = [
            'name' => 'Administrador',
            'email' => 'adm@adm.com',
            'password' => '123123'
        ];
        $new = [
            'name' => 'Administrador New',
            'email' => 'adm@adm.com.br',
            'password' => '123123'
        ];
        $user = \App\User::create($data);
        $user->update($new);
        //find new values
        $this->assertDatabaseHas('users', $new);
        //not find old values
        $this->assertDatabaseMissing('users', $data);
    }
    public function testDeleteTest()
    {
        $data = [
            'name' => 'Administrador',
            'email' => 'adm@adm.com',
            'password' => '123123'
        ];
        $user = \App\User::create($data);
        
        //confirm insert
        $this->assertDatabaseHas('users', $data);
        $user->delete();
        //confirm removed data
        $this->assertDatabaseMissing('users', $data);
    }


}