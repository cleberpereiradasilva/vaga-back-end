<?php

use Illuminate\Database\Seeder;

class DependenteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Dependente::class, 15)->create();      
    }
}
