@props(['type' => 'success'])

<span {{ $attributes->merge(['class' => "alert alert-$type mt-3"]) }} role="alert">
    {{ $slot }}
</span>
