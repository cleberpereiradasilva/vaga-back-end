<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = \App\User::where('email','admin')->get();
        if(count($admin) == 0){
            $data = [
                'name' => 'Administrador',
                'email' => 'admin',
                'password' => Hash::make('123123')
            ];        
            $user = \App\User::create($data);            
            factory(\App\User::class, 15)->create();        
            factory(\App\Cliente::class, 45)->create(); 
            factory(\App\Dependente::class, 45)->create();  
        }
    }
}
