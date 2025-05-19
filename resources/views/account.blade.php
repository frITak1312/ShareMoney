@extends("layouts.default")
@section("heading", $account->name)
@section("content")
    @if(session('success'))
        <x-toast>
            {{ session("success") }}
        </x-toast>
    @endif

@endsection


