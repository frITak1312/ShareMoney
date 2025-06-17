<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Account $account)
    {
        if (! $account->isMember(auth()->user()->id)) {
            abort(403);
        }

        $transactions = $account->transactions();

        $accountMembers = $account->users;

        return view('account', compact('account', 'accountMembers', 'transactions'));
    }

    public function deleteAccount(Account $account)
    {
        $user = auth()->user();
        $role = $account->getUserRole($user->id);

        if ($role !== 'admin') {
            return redirect()->route('accountDetailPage', $account)
                ->with('error', 'Pouze administrátor může smazat účet.')
                ->with('modal', 'deleteAccountModal');
        }

        if ($account->balance != 0) {
            return redirect()->route('accountDetailPage', $account)
                ->with('error', 'Účet lze smazat pouze pokud je zůstatek 0.')
                ->with('modal', 'deleteAccountModal');
        }

        $account->users()->detach();
        $account->delete();

        return redirect()->route('dashboardPage')->with('success', 'Účet byl úspěšně smazán.');
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:15',
        ], [
            'name.required' => 'Název účtu je povinný.',
            'name.string' => 'Název účtu musí být text.',
            'name.max' => 'Název účtu nesmí být delší než 15 znaků.',
        ]);

        try {
            $account = Account::create([
                'id' => $this->generateAccountId(),
                'name' => $validatedData['name'],
            ]);

            $account->users()->attach(auth()->id(), [
                'role' => 'admin',
                'joined_at' => now(),
            ]);

            return redirect()->route('accountDetailPage', $account)->with('success', 'Účet byl úspěšně vytvořen.');
        } catch (QueryException $exception) {
            // chyba když v DB je učet s tímto id (23000 - SQL , 1062 - MySQL)
            if ($exception->getCode() === '23000' || $exception->getCode() === '1062') {
                return $this->create($request);
            }

            // Pokud se nepodařilo vytvořit členství, smažeme účet
            if (isset($account)) {
                $account->delete();
            }

            return redirect()->back()->with('error', 'Nepodařilo se vytvořit účet nebo přidat členství.');
        }
    }

    private function generateAccountId()
    {
        do {
            $id = mt_rand(100000000, 999999999);
        } while (Account::where('id', $id)->exists());

        return $id;
    }

    public function editName(Account $account, Request $request)
    {
        if (! $account->isMember(auth()->user()->id)) {
            abort(403);
        }

        $name = $request->validate([
            'name' => 'required|string|max:15',
        ], [
            'name.required' => 'Název účtu nesmí býtr prázdný.',
            'name.string' => 'Název účtu musí být text.',
            'name.max' => 'Název účtu nesmí být delší než 15 znaků.',
        ]);

        $account->name = $request->name;
        $account->save();

        return redirect()->route('accountDetailPage', $account)
            ->with('success', 'Název účtu byl úspěšně změněn.');
    }
}
