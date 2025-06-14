@php
    if (isset($src)) {
        $imgSrc = $src;
    } else {
        if (empty(auth()->user()->avatar_path)) {
            $imgSrc = asset('images/default-avatar.png');
        } else {
            $imgSrc = asset(Storage::url(auth()->user()->avatar_path));
        }
    }
@endphp

<img
    src="{{ $imgSrc }}"
    alt="user-profile-photo"
    loading="lazy"
    {{$attributes}} />
