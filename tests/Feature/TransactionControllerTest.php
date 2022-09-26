<?php

namespace Tests\Feature;

use App\Models\Transaction;
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

    public function test_transactions_edit()
    {
        $trans = Transaction::factory()->create();
        $response = $this->actingAs($trans->user)->get(route('transactions.edit', ['trans' => $trans->id]));
        $response->assertStatus(200);
    }

    public function test_transactions_update()
    {
        $user = User::factory()->create();
        for ($i=0; $i<10; $i++) {
            $amount = 100;
            $user->balance += $amount;
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'balance' => $user->balance,
            ]);
        }
        $user->save();

        $toEdit = $user->transactions->first();
        $response = $this->actingAs($user)->put(
            route('transactions.update', ['trans' => $toEdit->id]),
            ['amount' => 1000]
        );
        $user->refresh();
        $toEdit->refresh();
        $response->assertStatus(302);
        $this->assertEquals(10, $user->transactions->count());
        $this->assertEquals(1000, $toEdit->amount); // asserts the target was edited
        $this->assertEquals(1900, $user->balance); // asserts the user's balance also be updated

        $response = $this->actingAs($user)->put(
            route('transactions.update', ['trans' => $toEdit->id]),
            ['amount' => -1000]
        );
        $user->refresh();
        $response->assertStatus(422);
        $this->assertEquals(1900, $user->balance); // asserts the user's balance still same
    }

    public function test_transactions_destroy()
    {
        $user = User::factory()->create();
        for ($i=0; $i<10; $i++) {
            $amount = 100;
            $user->balance += $amount;
            Transaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'balance' => $user->balance,
            ]);
        }
        $user->save();

        $toRemove = $user->transactions->first();
        $response = $this->actingAs($user)->delete(
            route('transactions.destroy', ['trans' => $toRemove->id])
        );
        $user->refresh();
        $response->assertStatus(302);
        $this->assertEquals(9, $user->transactions->count());
        $this->assertEquals(900, $user->balance); // asserts the user's balance also be updated
        $this->assertModelMissing($toRemove); // asserts the target was deleted
    }
}
