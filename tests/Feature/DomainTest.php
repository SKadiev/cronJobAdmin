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
    use RefreshDatabase;
    /**
     * 
     * A basic feature test example.
     *
     * @return void
     */

    protected $user;


    public function setUp(): void {

        parent::setUp();   

        $this->user = User::factory()->make(
            
            [
                'name' => 'testUser',
                'email' => 'testUser@gmail.com',
                'password' => 'TestUser10',
                'roles_id' => '1',
                "created_at" =>  date("Y-m-d H:i:s")
            ] 
        );

        $this->domain = Domain::factory()->create([
            'name' => 'tote',
            'score' => '100'
        ]);
            

    }

    public function testDomainCreate()
    {
            
        $response = $this->actingAs($this->user);
        
        $response = $this->get('domain/create');
        
        $response->assertStatus(200);
        
        $view = $this->view('domain/create');

        $view->assertSee('Domain name');

        $view->assertSee('Domain Score');


    }


    public function testDomainEdit()
    {
        
        $response = $this->actingAs($this->user);
        $response = $this->get("domain/{$this->domain->id}/edit");
        
        $response->assertStatus(200);
        
        $view = $this->view("domain/edit", ['domain' => $this->domain]);
        
        $view->assertSee('tote');
        
        $view->assertSee('100');

        $view->assertSee('Domain Name');

        $view->assertSee('Domain Score');
        

    }

    public function testDomainIndex()
    {
        

        $response = $this->actingAs($this->user);
        $response = $this->get("domain");
        
        $response->assertStatus(200);
        
        $view = $this->view("domain/index", ['domains' => [ $this->domain]]);
        

        $view->assertSee('Domain Name');
        
        $view->assertSee('Domain Score');

        $view->assertSee('tote');
        
        $view->assertSee('100');
        

    }

            
        
}
    