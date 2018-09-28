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
        $data = [
            'name' => 'Administrador',
            'email' => 'admin',
            'password' => Hash::make('123123')
        ];        
        $user = \App\User::create($data);
    }
}
