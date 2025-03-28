<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generateStatement($account_number)
    {
        $account = Account::where('account_number', $account_number)
            ->where('user_id', Auth::id())
            ->first();

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        $transactions = Transaction::where('account_id', $account->id)->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('pdf.statement', compact('account', 'transactions'));

        return $pdf->download("Account_Statement_{$account_number}.pdf");
    }
}
