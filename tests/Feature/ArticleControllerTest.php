<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(); 
    }

    public function test_user_can_fetch_articles()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'category',
                        'source',
                        'published_at',
                    ]
                ]
            ]);
    }

    public function test_user_can_fetch_personalized_articles()
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/articles/personalized');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'category',
                        'source',
                        'published_at',
                    ]
                ]
            ]);
    }

}