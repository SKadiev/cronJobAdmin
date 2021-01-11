<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rules')->insert([   
             
            [
                'name' => 'lowRanking',
                'from' => '0',
                'to' => '40',

            ] ,
            
            [
                'name' => 'mediumRanking',
                'from' => '40',
                'to' => '80'
            ] ,
            
            [
                'name' => 'highRanking',
                'from' => '80',
                'to' => '100'
            ]
             ,
            [
                'name' => 'full',
                'from' => '0',
                'to' => '100'
            ]
        ]);
    }
}
