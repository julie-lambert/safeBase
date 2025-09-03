<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteAccessTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }



    public function guest_cannot_access_backups_route()
    {
        $response = $this->get('/backups');
        $response->assertRedirect('/login');
    }


    public function authenticated_user_can_access_backups()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/backups');
        $response->assertStatus(200);
    }
}