<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\{Domain, Page};
use Illuminate\Foundation\Testing\RefreshDatabase;

class DomainTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    protected $domain;
    
    
    use RefreshDatabase;

    public function setUp() : void

    {
        parent::setUp();
        $this->domain =  Domain::factory(['name' => 'tote', "score" => 100])
        ->has(
            Page::factory()
                ->count(3)
                ->state(function (array $attributes, Domain $domain) {
                    return ['domain_id' => $domain->id];
                })
        )
        ->create();

        self::assertTrue(true);

    }
   
     /** @test */

    public function a_domain_has_a_name()
    {

        $this->assertEquals('tote', $this->domain->domainName());
    }

    /** @test */

    public function a_domain_has_a_score()
    {

        $this->assertEquals(100, $this->domain->domainScore());
    }


     /** @test */

     public function a_domain_has_many_pages()
     {
 
      
         $this->assertCount(3, $this->domain->pages);
            
     }
}
