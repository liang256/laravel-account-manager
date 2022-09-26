<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TransactionControllerTest extends TestCase
{
    public function test_transactions_create()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('transactions.create'));

        $response->assertStatus(200);
    }

    public function test_transactions_store()
    {
        // add an amount N transaction
        $user = User::factory()->create();
        $amount = $amount = fake()->randomNumber(5, false);
        $response = $this->actingAs($user)->post(route('transactions.store'), ['amount' => $amount]);

        $trans = $user->transactions->first();
        $this->assertEquals($user->balance + $amount, $trans->balance);
        $this->assertEquals($amount, $trans->amount);
        $response->assertStatus(302);

        // add an amount -N transaction
        $this->actingAs($user)->post(route('transactions.store'), ['amount' =>  -1 * $amount]);
        $user->refresh();
        $this->assertEquals(0, $user->balance);

        // try to make the balance negative
        $response = $this->actingAs($user)->post(route('transactions.store'), ['amount' => -1]);
        $response->assertStatus(422);
    }
}
