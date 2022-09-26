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

    public function edit()
    {
        
    }

    public function update()
    {
        
    }

    public function destroy()
    {
        
    }
}
