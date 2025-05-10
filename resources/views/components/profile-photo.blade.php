<img
    src="{{ empty(auth()->user()->avatar_path) ? asset('images/default-avatar.png') : asset(Storage::url(auth()->user()->avatar_path))}}"
    alt="user-profile-photo"
    loading="lazy"
    {{$attributes}} />
