<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index(Account $account)
    {
        return view('account', compact('account'));
    }

    public function removeUser(Account $account)
    {
        $user = auth()->user();

        if ($account->users()->where('user_id', $user->id)->exists()) {
            $account->users()->detach($user->id);

            return redirect()->route('dashboardPage')->with('success', 'Opustili jste účet.');
        }

        return redirect()->route('accountDetailPage', $account)
            ->with('error', 'Nemáte oprávnění opustit tento účet.')
            ->with('modal', 'leaveAccountModal');
    }

    public function addMember(Account $account, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userName' => 'required',
        ], [
            'userName.required' => 'Uživatelské jméno nesmí být prázdné.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('modal', 'addMemberModal');
        }

        $userName = $request->input('userName');
        $userName = ltrim($userName, '@');

        if (! User::getUserByUsername($userName)) {
            return redirect()->back()
                ->withErrors(['userName' => 'Uživatel s tímto uživatelským jménem neexistuje.'])
                ->with('modal', 'addMemberModal');
        }

        $user = User::getUserByUsername($userName);

        // Kontrola: přidává sám sebe
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->withErrors(['userName' => 'Nemůžete přidat sám sebe.'])
                ->with('modal', 'addMemberModal');
        }

        // Kontrola: už je členem
        if ($account->users()->where('user_id', $user->id)->exists()) {
            return redirect()->back()
                ->withErrors(['userName' => 'Tento uživatel je již členem účtu.'])
                ->with('modal', 'addMemberModal');
        }

        $account->users()->attach($user->id);

        return redirect()->route('accountDetailPage', $account)->with('success', 'Uživatel byl úspěšně přidán do účtu.');
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
