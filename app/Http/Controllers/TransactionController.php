<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function depositMoney(Account $account, Request $request)
    {
        try {
            $validatedData = $request->validate([
                'amount' => 'required|numeric|min:1',
                'description' => 'nullable|string|max:255',
            ], [
                'amount.required' => 'Částka je povinná.',
                'amount.numeric' => 'Částka musí být číslo.',
                'amount.min' => 'Částka musí být větší než 0.',
                'description.string' => 'Popis musí být text.',
                'description.max' => 'Popis nesmí být delší než 255 znaků.',
            ]);

            $account->balance += $validatedData['amount'];
            $account->save();

            Transaction::create([
                'account_id' => $account->id,
                'user_id' => auth()->id(),
                'amount' => $validatedData['amount'],
                'type_id' => 2, // 1 - platba, 2 - vklad
                'description' => $validatedData['description'],
            ]);

            return redirect()->route('accountDetailPage', $account)->with('success', 'Peníze byly úspěšně přidány na účet.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('modal', 'depositMoneyModal');
        }
    }
}
