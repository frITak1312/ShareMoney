<?php

namespace App\Http\Controllers;

use App\Models\Account;

class AccountController extends Controller
{
    public function index(Account $account)
    {
        return view('account', compact('account'));
    }

    public function create()
    {
        $validatedData = request()->validate([
            'name' => 'required|string|max:15',
        ], [
            'name.required' => 'Název účtu je povinný.',
            'name.string' => 'Název účtu musí být text.',
            'name.max' => 'Název účtu nesmí být delší než 15 znaků.',
        ]);

        $account = Account::create([
            'name' => $validatedData['name'],
        ]);

        $account->users()->attach(auth()->id(), [
            'role' => 'admin',
        ]);

        $account->save();

        return redirect()->route('accountDetailPage', $account)->with('success', 'Účet byl úspěšně vytvořen.');
    }
}
