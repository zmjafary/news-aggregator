<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class PreferenceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(); 
    }

    public function test_user_can_update_preferences()
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/preferences/update', [
            'source' => ['New York Times'],
            'category' => ['Politics'],
            'authors' => ['Michael Gold'],
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Preferences Updated!']);
    }
}