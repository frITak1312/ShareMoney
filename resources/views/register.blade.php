<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Share Money - registrace</title>
    @vite(['resources/css/authenticate.css'])
</head>
<body>
<main>
    <x-auth-logo/>
    <div class="form-container text-start p-5 rounded-4">
        <div class="d-flex justify-content-center gap-2 mb-4">
            <a href="{{ route('loginPage', request()->has('email') || $errors->has('userExists') ? ['email' => old('email')] : []) }}"
               class="btn btn-outline-primary {{ request()->routeIs('loginPage') ? 'active' : '' }}">
                Přihlášení
            </a>
            <a href="{{ route('registerPage') }}"
               class="btn btn-outline-success {{ request()->routeIs('registerPage') ? 'active' : '' }}">Registrace</a>
        </div>
        <x-form id="registerForm" method="post" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Jméno</label>
                <input type="text" class="form-control" id="name" name="name" required
                       value="{{ old('name') }}">
                @error('name')
                <x-error :message="$message"/>
                @enderror
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Příjmení</label>
                <input type="text" class="form-control" id="surname" name="surname" required
                       value="{{ old('surname') }}">
                @error('surname')
                <x-error :message="$message"/>
                @enderror
            </div>
            <div class="mb-3">
                <label for="registerEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="registerEmail" name="email" required
                       value="{{ old('email', request('email')) }}">
                @error('email')
                <x-error :message="$message"/>
                @enderror
            </div>
            <div class="mb-3">
                <label for="registerPassword" class="form-label">Heslo</label>
                <input type="password" class="form-control" id="registerPassword" name="password" min="8" required>
                @error('password')
                <x-error :message="$message"/>
                @enderror
            </div>
            @error("userExists")
            <x-error :message="$message"/>
            @enderror
            <button type="submit" class="btn btn-success w-100">Registrovat se</button>
        </x-form>
    </div>
</main>
</body>
</html>
