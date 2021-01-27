<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('job_types')->insert([   
             
            [
                'type' => 'feed'
            ] ,
            
            [
                'type' => 'all'
            ] 
               
        ]);
    }
}
