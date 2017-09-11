<?php

use Illuminate\Database\Seeder;

class BillingParamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('monzy_points')->insert([
            [
                'points' => 100,
                'bonus'  => 0,
                'value'  => 5.00,
                'name'   => 'Pacote Easy'
            ],
            [
                'points' => 350,
                'bonus'  => 50,
                'value'  => 14.50,
                'name'   => 'Pacote Medium'
            ],
            [
                'points' => 1050,
                'bonus'  => 100,
                'value'  => 44.00,
                'name'   => 'Pacote Hard'
            ],
            [
                'points' => 1650,
                'bonus'  => 150,
                'value'  => 72.50,
                'name' 	 => 'Pacote Advanced'
            ]
        ]);
    }

}