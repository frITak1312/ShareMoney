<img
    src="{{ empty(auth()->user()->avatar_path) ? asset('images/profilePictures/default-avatar.png') : asset('storage/' . auth()->user()->avatar_path)}}"
    alt="user-profile-photo"
    {{$attributes}}/>
