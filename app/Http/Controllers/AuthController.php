<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'regex:/^[\p{L}\s\-]+$/u'],
            'surname' => ['required', 'regex:/^[\p{L}\s\-]+$/u'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ], [
            'name.required' => 'Jméno nesmí být prázdné',
            'surname.required' => 'Příjmení nesmí být prázdné',
            'email.required' => 'Email nesmí být prázdný',
            'email.email' => 'Zadejte platný email',
            'password.required' => 'Heslo musí být vyplněno',
            'password.min' => 'Heslo musí mít aspoň 8 znaků',
        ]);
        // shodný email je již v DB
        if (User::where('email', $request->only('email'))->exists()) {
            return back()
                ->withErrors(['userExists' => 'Uživatel s tímto e-mailem již existuje'])
                ->withInput();
        }

        $user = User::create([
            'name' => ucfirst($validatedData['name']),
            'surname' => ucfirst($validatedData['surname']),
            'username' => $this->createUsername($validatedData['name'],
                $validatedData['surname']),
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'avatar_path' => '',
        ]);

        Auth::login($user);

        return redirect()->route('dashboardPage');
    }

    public function createUsername($name, $surname): string
    {
        $random = rand(101, 999);

        return strtolower(substr($name, 0, 2).substr($surname, 0, 3)
            .$random);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email nesmí být prázdný',
            'email.email' => 'Zadej platný email',
            'password.required' => 'Heslo nesmí být prázdné',
        ]);

        if (! Auth::attempt($validatedData)) {
            return back()
                ->withErrors([
                    'invalidCredentials' => 'Zadali jste špatné přihlašovací údaje',
                ])
                ->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('dashboardPage');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('loginPage');
    }
}
