<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        if (is_null($user)) {
            $user = User::factory()->create();
        }
        $amount = fake()->randomNumber(5, false);
        $user->balance += $amount;
        $user->save();
        return [
            'user_id' => $user->id,
            'amount' => $amount,
            'balance' => $user->balance,
        ];
    }
}
