<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function create()
    {
        return view('transaction.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|between:-9999999999.99,9999999999.99',
        ]);
        
        $user = User::find(Auth::id());
        $newBalance = $user->balance + $request->amount;
        if ($newBalance < 0) {
            return response('invalid amount', 422);
        }

        DB::transaction(function () use ($user, $newBalance, $request) {
            Transaction::create([
                'amount' => $request->amount,
                'balance' => $newBalance,
                'user_id' => $user->id,
            ]);
            $user->update(['balance' => $newBalance]);
        });

        return redirect()->route('accounts.show', ['userId' => $user->id]);
    }

    public function edit(Transaction $trans)
    {
        if ($trans->user_id != Auth::id()) {
            return abort(403);
        }
        return view('transaction.edit', ['trans' => $trans]);
    }

    public function update(Request $request, Transaction $trans)
    {
        $request->validate([
            'amount' => 'required|numeric|between:-9999999999.99,9999999999.99',
        ]);

        if ($trans->user_id != Auth::id()) {
            return abort(403);
        }

        $balance = $trans->balance - $trans->amount + $request->amount;

        if ($balance < 0) {
            return response("invalid amount", 422);
        }

        $restTransactions = Transaction::where('user_id', $trans->user_id)
            ->where('id', '>', $trans->id)
            ->get();
        
        DB::beginTransaction();
        $ok = true;

        $trans->update([
            'amount' => $request->amount,
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

        if ($ok) {
            $user = User::findOrFail($trans->user_id);
            $user->update(['balance' => $balance]);
            DB::commit();
            return redirect()->route('accounts.show', ['userId' => $trans->user_id]);
        }

        DB::rollBack();
        return response("invalid amount", 422);
    }

    public function destroy(Transaction $trans)
    {
        if ($trans->user_id != Auth::id()) {
            return abort(403);
        }
        $balance = $trans->balance - $trans->amount;

        $restTransactions = Transaction::where('user_id', $trans->user_id)
            ->where('id', '>', $trans->id)
            ->get();
        
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

        if ($ok) {
            $trans->delete();
            $user = User::findOrFail($trans->user_id);
            $user->update(['balance' => $balance]);
            DB::commit();
            return redirect()->route('accounts.show', ['userId' => $trans->user_id]);
        }

        DB::rollBack();
        return response("the transaction cannot be deleted", 422);
    }
}
