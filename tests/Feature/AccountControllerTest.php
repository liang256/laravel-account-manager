<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AccountControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/accounts');

        $response->assertStatus(200);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('accounts.show', $user->id));

        $response->assertStatus(200);
    }
}
