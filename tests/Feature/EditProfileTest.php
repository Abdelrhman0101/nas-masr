<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_profile_requires_auth(): void
    {
        $response = $this->putJson('/api/edit-profile', []);
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_edit_profile_updates_name(): void
    {
        $user = User::create([
            'name' => 'Old',
            'phone' => '01000000010',
            'password' => 'password',
            'role' => 'user',
        ]);

        Sanctum::actingAs($user);

        $resp = $this->putJson('/api/edit-profile', [
            'name' => 'New Name',
        ]);

        $resp->assertStatus(200);
        $resp->assertJsonPath('data.name', 'New Name');
    }
}