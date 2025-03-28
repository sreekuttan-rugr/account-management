<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->accounts);
    }

    // Create a new account
    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'account_name' => 'required|string|unique:accounts,account_name',
            'account_type' => ['required', Rule::in(['Personal', 'Business'])],
            'currency' => ['required', Rule::in(['USD', 'EUR', 'GBP'])],
        ]);

        $account = Auth::user()->accounts()->create($validated);

        return response()->json($account, 201);
    }

    // Get details of a specific account
    public function show(Account $account)
    {
        $this->authorize('view', $account);
        return response()->json($account);
    }

    // Update an account (e.g., change account name)
    public function update(Request $request, Account $account)
    {
        $this->authorize('update', $account);

        $validated = $request->validate([
            'account_name' => 'sometimes|string|unique:accounts,account_name,' . $account->id,
        ]);

        $account->update($validated);

        return response()->json($account);
    }

    // Delete an account (Soft Delete)
    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);
        $account->delete();

        return response()->json(['message' => 'Account deleted'], 204);
    }
}
