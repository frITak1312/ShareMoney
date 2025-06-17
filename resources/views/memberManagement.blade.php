@extends("layouts.default")
@section("custom-css")
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" />
@endsection

@section("heading", "Správá členů")

@section("content")
    <h3 style="color: rgb(114,114,114); font-size: 20px; margin-bottom: 40px; margin-top: 20px">
        účet: &nbsp;{{ $account->name }}
    </h3>

    <div class="container mt-5" style="max-width: 1000px">
        <table id="member-management-table" class="table nowrap table-hover table-bordered">

            <thead>
            <tr>
                <th>#</th>
                <th>Člen</th>
                <td>Uživ. jméno</td>
                <th>Email</th>
                <th>Připojil se</th>
                <th>Role</th>
                <th>Akce</th>
            </tr>
            </thead>
            <tbody>
            @foreach($members as $member)
                @php
                    $src = empty($member->avatar_path)
                        ? asset('images/default-avatar.png')
                        : asset(Storage::url($member->avatar_path));
                    $role = $member->pivot->role;
                @endphp
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td class="p-1">
                        <x-profile-photo class="avatar me-3" src="{{$src}}" />
                        {{ $member->full_name }}
                    </td>
                    <td>{{$member->username}}</td>
                    <td>{{$member->email}}</td>
                    <td>{{ date_format(date_create($member->joined_at), "d.m.Y")}}</td>
                    <td>
                        <div class="dropdown d-inline">
                            <button class="btn dropdown-toggle" type="button"
                                    id="dropdownMenuButton{{$loop->iteration}}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $role }}
                            </button>
                            <ul class="dropdown-menu p-0" style="min-width: 0;"
                                aria-labelledby="dropdownMenuButton{{$loop->iteration}}">
                                <li>
                                    <x-form method="post" action="{{route('changeMemberRole', [$account,$member])}}"
                                            style="margin: 0">
                                        @method("PUT")
                                        <input type="hidden" name="role"
                                               value="{{ $role === "member" ? "moderator" : "member" }}">
                                        <button class="dropdown-item" type="submit"
                                        >{{ $role === "member" ? "moderator" : "member" }}</button>
                                    </x-form>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <x-form method="post" action="{{route('removeMemberFromAccount',[$account,$member])}}"
                                style="margin: 0">
                            @method("DELETE")
                            <button class="btn btn-danger btn-sm"
                                    type="submit">Odebrat
                            </button>
                        </x-form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- Přidat člena (admin a moderator) modal  -->
    <x-modal-form heading="Přidat člena" :action="route('addMemberToAccount', $account)" id="addMemberModal"
                  class="modal-lg">
        <label>
            Zadejte username:
            <input type="text" name="userName" id="userName" placeholder="@username">
        </label>
        @error('userName')
        <x-error :message="$message" /> @enderror
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-secondary">Přidat</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Zpět</button>
        </div>
    </x-modal-form>
@endsection

@section("scripts")
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    @vite(['resources/js/memberManagement.js'])
@endsection
