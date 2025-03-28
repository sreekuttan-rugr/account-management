<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // Log a transaction (Credit or Debit)
    public function store(Request $request)
    {
        $request->validate([
            'account_number' => 'required|exists:accounts,account_number',
            'type' => 'required|in:Credit,Debit',
            'amount' => 'required|numeric|min:0.01|max:100000', // Prevents negative/zero transactions & large values
            'description' => 'nullable|string|max:500'
        ]);
        

        $account = Account::where('account_number', $request->account_number)
            ->where('user_id', Auth::id())
            ->first();

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        // Prevent overdrafts (if balance is insufficient)
        if ($request->type === 'Debit' && $account->balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        DB::transaction(function () use ($request, $account) {
            // Create the transaction
            Transaction::create([
                'id' => Str::uuid(),
                'account_id' => $account->id,
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description
            ]);

            // Update the account balance
            if ($request->type === 'Credit') {
                $account->increment('balance', $request->amount);
            } else {
                $account->decrement('balance', $request->amount);
            }
        });

        return response()->json(['message' => 'Transaction recorded successfully']);
    }

    // Get all transactions for an account (with optional date filters)
    public function index(Request $request)
{
    $request->validate([
        'account_number' => 'required|exists:accounts,account_number',
        'from' => 'nullable|date',
        'to' => 'nullable|date|after_or_equal:from'
    ]);

    $account = Account::where('account_number', $request->account_number)
        ->where('user_id', Auth::id())
        ->first();

    if (!$account) {
        return response()->json(['message' => 'Account not found'], 404);
    }

    $transactions = Transaction::where('account_id', $account->id)
        ->when($request->from, fn ($query) => $query->whereDate('created_at', '>=', $request->from))
        ->when($request->to, fn ($query) => $query->whereDate('created_at', '<=', $request->to))
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($transactions);
}

public function transfer(Request $request)
{
    $request->validate([
        'from_account_number' => 'required|exists:accounts,account_number',
        'to_account_number' => 'required|exists:accounts,account_number',
        'amount' => 'required|numeric|min:0.01'
    ]);

    $fromAccount = Account::where('account_number', $request->from_account_number)
        //->where('user_id', Auth::id())
        ->first();

    $toAccount = Account::where('account_number', $request->to_account_number)->first();

    if (!$fromAccount || !$toAccount) {
        return response()->json(['message' => 'One or both accounts not found'], 404);
    }

    if ($fromAccount->id === $toAccount->id) {
        return response()->json(['message' => 'Cannot transfer to the same account'], 400);
    }

    if ($fromAccount->balance < $request->amount) {
        return response()->json(['message' => 'Insufficient funds'], 400);
    }

    DB::transaction(function () use ($fromAccount, $toAccount, $request) {
        // Debit sender
        Transaction::create([
            'id' => Str::uuid(),
            'account_id' => $fromAccount->id,
            'type' => 'Debit',
            'amount' => $request->amount,
            'description' => 'Transfer to ' . $toAccount->account_number
        ]);
        $fromAccount->decrement('balance', $request->amount);

        // Credit receiver
        Transaction::create([
            'id' => Str::uuid(),
            'account_id' => $toAccount->id,
            'type' => 'Credit',
            'amount' => $request->amount,
            'description' => 'Transfer from ' . $fromAccount->account_number
        ]);
        $toAccount->increment('balance', $request->amount);
    });

    return response()->json(['message' => 'Transfer successful']);
}



}
