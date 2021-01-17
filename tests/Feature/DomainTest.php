<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Domain;

class DomainTest extends TestCase
{
    
    // use DatabaseMigrations;
    // use RefreshDatabase;
    /**
     * 
     * A basic feature test example.
     *
     * @return void
     */
    public function testDomainCreate()
    {
        
        $user = User::factory()->make(
            
            [
                'name' => 'testUser',
                'email' => 'testUser@gmail.com',
                'password' => 'TestUser10',
                'roles_id' => '1',
                "created_at" =>  date("Y-m-d H:i:s")
                ] 
            );
            
            
            $response = $this->actingAs($user);
            
            $response = $this->get('domain/create');
            
            $response->assertStatus(200);
            
            $view = $this->view('domain/create');

            $view->assertSee('Domain name');

            $view->assertSee('Domain Score');


        }
        
        
        
    }
    