{{--Vytvořeno kvŮli csrf--}}

<form {{ $attributes }}>
    @csrf
    {{ $slot }}
</form>
