<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Share Money - přihlášení</title>
    @vite(['resources/css/authenticate.css'])
</head>
<body>
<main>
    <x-auth-logo />
    <div class="form-container text-start p-5 rounded-4">
        <div class="d-flex justify-content-center gap-2 mb-4">
            <a href="{{ route('loginPage') }}"
               class="btn btn-outline-primary {{ request()->routeIs('loginPage') ? 'active' : '' }}">Přihlášení</a>
            <a href="{{ route('registerPage') }}"
               class="btn btn-outline-success {{ request()->routeIs('registerPage') ? 'active' : '' }}">Registrace</a>
        </div>
        <x-form id="loginForm" method="post" action="{{ route('login') }}">
            <div class="mb-3">
                <label for="loginEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="loginEmail" name="email" required
                       value="{{ old('email', request('email')) }}">
                @error('email')
                <x-error :message="$message" />
                @enderror
            </div>
            <div class="mb-3">
                <label for="loginPassword" class="form-label">Heslo</label>
                <input type="password" class="form-control" id="loginPassword" name="password" required>
                @error('password')
                <x-error :message="$message" />
                @enderror
            </div>
            @error('invalidCredentials')
            <x-error :message="$message" />
            @enderror
            <button type="submit" class="btn btn-primary w-100">Přihlásit se</button>
        </x-form>
    </div>
</main>
</body>
</html>





