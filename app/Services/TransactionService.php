<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class TransactionService
{
    public static function create(int $userId, float $amount)
    {
        $user = self::getLockUser($userId);

        $newBalance = $user->balance + $amount;
        if ($newBalance < 0) {
            throw new Exception('invalid amount');
        }

        DB::transaction(function () use ($user, $newBalance, $amount) {
            Transaction::create([
                'amount' => $amount,
                'balance' => $newBalance,
                'user_id' => $user->id,
            ]);
            $user->update(['balance' => $newBalance]);
        });
    }

    public static function update(int $userId, int $transId, float $newAmount)
    {
        $transactions = self::getLockTransactionsAfterId($userId, $transId);
        $trans = $transactions->where('id', $transId)->first();

        if (is_null($trans)) {
            throw new Exception('transaction not found');
        }

        $user = self::getLockUser($trans->user_id);

        $balance = $trans->balance - $trans->amount + $newAmount;

        if ($balance < 0) {
            throw new Exception("invalid amount");
        }

        $restTransactions = $transactions->where('id', '>', $trans->id)->all();

        DB::beginTransaction();
        $ok = true;

        $trans->update([
            'amount' => $newAmount,
            'balance' => $balance,
        ]);
        foreach ($restTransactions as $t) {
            $balance += $t->amount;
            if ($balance < 0) {
                $ok = false;
                break;
            }
            $t->update(['balance' => $balance]);
        }

        if (!$ok) {
            DB::rollBack();
            throw new Exception('transaction cannot be updated');
        }

        $user->update(['balance' => $balance]);
        DB::commit();
    }

    public static function delete(int $userId, int $transId)
    {
        $transactions = self::getLockTransactionsAfterId($userId, $transId);
        $trans = $transactions->where('id', $transId)->first();

        if (is_null($trans)) {
            throw new Exception('transaction not found');
        }

        $user = self::getLockUser($trans->user_id);

        $balance = $trans->balance - $trans->amount;

        $restTransactions = $transactions->where('id', '>', $trans->id)->all();

        DB::beginTransaction();
        $ok = true;

        foreach ($restTransactions as $t) {
            $balance += $t->amount;
            if ($balance < 0) {
                $ok = false;
                break;
            }
            $t->update(['balance' => $balance]);
        }

        if (!$ok) {
            DB::rollBack();
            throw new Exception('transaction cannot be deleted');
        }

        $trans->delete();
        $user->update(['balance' => $balance]);
        DB::commit();
    }

    private static function getLockUser(int $userId): User
    {
        $user = User::lockForUpdate()->find($userId);
        if (is_null($user)) {
            throw new Exception('user not found');
        }
        return $user;
    }

    private static function getLockTransactionsAfterId(int $userId, int $transId): Collection
    {
        return Transaction::where('user_id', $userId)
            ->where('id', '>=', $transId)
            ->lockForUpdate()
            ->get();
    }
}
