<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\TransactionService;

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

        try {
            TransactionService::create(Auth::id(), $request->amount);
        } catch (\Throwable $ex) {
            return response("the transaction cannot be created:" . $ex->getMessage(), 422);
        }

        return redirect()->route('accounts.show', ['userId' => Auth::id()]);
    }

    public function edit(Transaction $trans)
    {
        $this->authorize('update', $trans);
        return view('transaction.edit', ['trans' => $trans]);
    }

    public function update(Request $request, Transaction $trans)
    {
        $request->validate([
            'amount' => 'required|numeric|between:-9999999999.99,9999999999.99',
        ]);

        $this->authorize('update', $trans);

        try {
            TransactionService::update($trans->user_id, $trans->id, $request->amount);
        } catch (\Throwable $ex) {
            return response("the transaction cannot be updated:" . $ex->getMessage(), 422);
        }

        return redirect()->route('accounts.show', ['userId' => $trans->user_id]);
    }

    public function destroy(Transaction $trans)
    {
        $this->authorize('delete', $trans);

        try {
            TransactionService::delete($trans->user_id, $trans->id);
        } catch (\Throwable $ex) {
            return response("the transaction cannot be deleted:" . $ex->getMessage(), 422);
        }

        return redirect()->route('accounts.show', ['userId' => $trans->user_id]);
    }
}
