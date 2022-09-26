<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;

class AccountController extends Controller
{
    public function index() 
    {
        return view("account.index", ["users" => User::all()]);
    }

    public function show($userId) 
    {
        return view("account.show", [
            "transactions" => Transaction::where("user_id", $userId)->orderBy('id')->get(),
            "userId" => $userId
        ]);
    }
}
