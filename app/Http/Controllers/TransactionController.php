<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
                'amount.min' => 'Částka musí být aspon 1.',
                'description.string' => 'Popis musí být text.',
                'description.max' => 'Popis nesmí být delší než 255 znaků.',
            ]);
            try {
                $account->balance += $validatedData['amount'];
                $account->save();

                Transaction::create([
                    'account_id' => $account->id,
                    'user_id' => auth()->id(),
                    'amount' => $validatedData['amount'],
                    'type_id' => 2, // 1 - platba, 2 - vklad
                    'description' => $validatedData['description'],
                ]);
            } catch (QueryException $e) {
                return redirect()->back()
                    ->with('error', 'Při transakci nastala chyba');
            }

            return redirect()->route('accountDetailPage', $account)->with('success', 'Peníze byly úspěšně přidány na účet.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('modal', 'depositMoneyModal');
        }
    }

    public function sendPayment(Account $account, Request $request)
    {

        try {
            $validatedData = $request->validate([
                'amount' => 'required|numeric|min:1',
                'description' => 'nullable|string|max:50',
                'recipient' => 'required|numeric',
            ], [
                'amount.required' => 'Částka je povinná.',
                'amount.numeric' => 'Částka musí být číslo.',
                'amount.min' => 'Částka musí být větší než 0.',
                'description.string' => 'Popis musí být text.',
                'description.max' => 'Popis nesmí být delší než 50 znaků.',
                'recipient.required' => 'Příjemce je povinný.',
                'recipient.numeric' => 'Zadejte ČÍSLO účtu.',
            ]);

            $account->lockForUpdate(); // zámek pro aktualizaci účtu
            $recipient = Account::where('id', $validatedData['recipient'])->first();
            if ($recipient === $account) {
                return redirect()->back()
                    ->with('error', 'Nemůžete poslat peníze na svůj vlastní účet.')
                    ->withInput()
                    ->with('modal', 'sendPaymentModal');
            }
            if ($recipient) {
                $recipient->lockForUpdate();
            } // zámek pro příjemce, pokud existuje

            if ($account->balance < $validatedData['amount']) {
                return redirect()->back()
                    ->with('error', 'Nedostatek prostředků na účtu.')
                    ->withInput()
                    ->with('modal', 'sendPaymentModal');
            }

            // změna balance na odesílajícím účtu
            $account->balance -= $validatedData['amount'];

            // transakce odeslání platby
            Transaction::create([
                'account_id' => $account->id,
                'user_id' => auth()->id(),
                'amount' => -$validatedData['amount'],
                'type_id' => 1, // 1 - platba, 2 - vklad
                'description' => $validatedData['description'],
                'recipient_account_id' => $validatedData['recipient'],
            ]);

            $account->save();

            if ($recipient) {
                // změna balance na přijímajícím účtu, pokud je v db (jinak to jde do "světa")
                $recipient->balance += $validatedData['amount'];

                // transakce přijetí platby, pokud je přijemce v db
                Transaction::create([
                    'account_id' => $account->id,
                    'amount' => +$validatedData['amount'],
                    'type_id' => 1, // 1 - platba, 2 - vklad
                    'description' => $validatedData['description'],
                    'recipient_account_id' => $validatedData['recipient'],
                ]);

                $recipient->save();
            }

            return redirect()->route('accountDetailPage', $account)->with('success', 'Platba byla úspěšně odeslána.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('modal', 'sendPaymentModal');
        }

    }
}
