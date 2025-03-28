<?php



namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Helpers\LuhnHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    // Create a new account
    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255|unique:accounts,account_name',
            'account_type' => 'required|in:Personal,Business',
            'currency' => 'required|in:USD,EUR,GBP',
            'initial_balance' => 'nullable|numeric|min:0'
        ]);
        

        $account = Account::create([
            'user_id' => Auth::id(),
            'account_name' => $request->account_name,
            'account_type' => $request->account_type,
            'currency' => $request->currency,
            'balance' => $request->initial_balance ?? 0
        ]);

        return response()->json(['message' => 'Account created successfully', 'account' => $account], 201);
    }

    // Fetch account details by account number
    public function show($account_number)
    {
        $account = Account::where('account_number', $account_number)
            ->where('user_id', Auth::id())
            ->first();

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        return response()->json($account);
    }

    // Update account details (except account number)
    public function update(Request $request, $account_number)
    {
        $account = Account::where('account_number', $account_number)
            ->where('user_id', Auth::id())
            ->first();

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        $request->validate([
            'account_name' => 'sometimes|string|unique:accounts,account_name,' . $account->id,
            'account_type' => 'sometimes|in:Personal,Business',
            'currency' => 'sometimes|in:USD,EUR,GBP'
        ]);

        $account->update($request->only(['account_name', 'account_type', 'currency']));

        return response()->json(['message' => 'Account updated successfully', 'account' => $account]);
    }

    // Deactivate (soft delete) an account
    public function destroy($account_number)
    {
        $account = Account::where('account_number', $account_number)
            ->where('user_id', Auth::id())
            ->first();

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        $account->delete();

        return response()->json(['message' => 'Account deactivated successfully']);
    }

    public function restore($account_number)
{
    $account = Account::onlyTrashed()
        ->where('account_number', $account_number)
        ->where('user_id', Auth::id())
        ->first();

    if (!$account) {
        return response()->json(['message' => 'No deleted account found'], 404);
    }

    $account->restore();
    return response()->json(['message' => 'Account restored successfully']);
}

// public function show($account_number)
// {
//     $account = Account::where('account_number', $account_number)->first();

//     if (!$account || Auth::user()->cannot('view', $account)) {
//         return response()->json(['message' => 'Unauthorized or account not found'], 403);
//     }

//     return response()->json($account);
// }


}

