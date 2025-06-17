<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountMembershipsController extends Controller
{
    public function index(Account $account)
    {
        $members = $account->users->reject(fn ($user) => $user->pivot->role
            === 'admin'); // vylučuji admina

        return view('memberManagement', compact('account', 'members'));
    }

    public function removeMember(Account $account, User $user)
    {
        if ($account->users()->where('user_id', $user->id)->exists()) {

            $account->users()->detach($user->id);

            return redirect()->back()->with('success', 'Člen byl odebrán.');
        }

        return redirect()->back()->with('error', 'Akce se nezdařila.');
    }

    public function leaveAccount(Account $account)
    {
        if ($account->users()->detach(auth()->id())) {
            return redirect()->route('dashboardPage')
                ->with('success', 'Opustili jste účet.');
        }


        return redirect()->route('accountDetailPage', $account)
            ->with('error', 'Nemůžete opustit tento účet.')
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

        $user = User::getUserByUsername($userName);

        if (empty($user)) {
            return redirect()->back()
                ->withErrors(['userName' => 'Uživatel s tímto uživatelským jménem neexistuje.'])
                ->with('modal', 'addMemberModal');
        }

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

        return redirect()->back()
            ->with('success', 'Uživatel byl úspěšně přidán do účtu.');
    }

    public function changeMemberRole(
        Account $account,
        User $user,
        Request $request
    ) {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:member,moderator',
        ], [
            'role.required' => 'Role musí být vyplněna.',
            'role.in' => 'Tuto roli nelze přiřadit.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }
        $account->users()
            ->updateExistingPivot($user->id, ['role' => $request->input('role')]);

        return redirect()->back()
            ->with('success', 'Role člena byla úspěšně změněna.');
    }
}
