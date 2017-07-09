<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PlatformsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$now = Carbon::now();

        DB::table('platforms')->insert([
        	[
        		'name' => 'PC', 
        		'code' => 'PC', 
        		'created_at' => $now
        	],
        	[
        		'name' => 'Playstation 4', 
        		'code' => 'PS4', 
        		'created_at' => $now
        	],
        	[
        		'name' => 'X Box One', 
        		'code' => 'XONE', 
        		'created_at' => $now
        	]
        ]);
    }
}
