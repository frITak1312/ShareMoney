<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(User $user)
    {
        return view('editProfile', compact('user'));
    }

    public function edit(Request $request, User $user)
    {

        $validatedData = request()->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id), // kontrola unikátnosti s ignorováním aktuálního uživatele
            ],
            'password' => 'nullable|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg',

        ]);

        $user->name = $validatedData['name'];
        $user->surname = $validatedData['surname'];
        $user->email = $validatedData['email'];
        if (! empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }
        // Operování s profilovou fotkou
        if ($request->input('remove_avatar') === "1") {
            ImageService::deleteImage($user->avatar_path);
            $user->avatar_path = null; // Odebrání cesty z databáze
        } elseif ($request->hasFile('avatar')) {
            ImageService::deleteImage($user->avatar_path); // Smazání starého obrázku

            $user->avatar_path = ImageService::processAndSaveImage(
                $request->file('avatar'),
                'images/profilePhotos'
            );
        }

        $user->save();
        // aktualizace uživatele v session
        Auth::setUser($user);

        return redirect()->route('profileDetailPage', $user)->with('success', 'Profil byl úspěšně aktualizován.');
    }
}

