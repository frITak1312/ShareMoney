@extends("layouts.default")

@section("custom-css")
    @vite(["resources/css/accountPage.css"])
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css" />
@endsection

@section("heading", $account->name)

@section("content")
    @php
        $userId = auth()->id();
        $role = $account->getUserRole($userId);
        $accountMembers = $account->users;
    @endphp

    <h3 style="color: rgb(163,163,163);font-size: 20px;margin-bottom: 40px;">
        vlastník: &nbsp;{{ $account->owner_name }}
    </h3>

    <p class="lead text-center text-success" style="font-size: 45px;margin-bottom: 26px;">
        {{ $account->balance }} CZK
    </p>

    <div class="d-flex d-xl-flex gap-5">
        <button class="btn btn-outline-success" type="button">Zadat platbu</button>
        <button class="btn btn-outline-light" id="deposit-money-btn" type="button">Vložit peníze</button>
    </div>

    <!-- Tabulka výpisu -->
    <div class="container mt-5" style="width: 50%;">
        <div class="table-responsive">
            <table id="example" class="table table-striped nowrap">
                <thead>
                <tr>
                    <th>Datum</th>
                    <th>Popis</th>
                    <th>Částka</th>
                    <th>Odeslal/Vložil</th>
                </tr>
                </thead>
                <tbody>
                <!-- Dummy data -->
                @for($i = 0; $i < 4; $i++)
                    <tr>
                        <td>22.05.2025</td>
                        <td>Platba za úklid</td>
                        <td>500 CZK</td>
                        <td>Jan Marek</td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        @if($role === "admin")
            <div class="divider p-3 text-center">
                <a href="#" class="btn btn-info btn-lg" role="button">Spravovat účet</a>
            </div>
        @endif

        @if($role !== "user")
            <div class="text-center mt-4">
                <button class="btn-outline-info btn btn-lg mb-3" id="add-member-btn">
                    Přidat člena <i class="fas fa-plus-square"></i>
                </button>
            </div>
        @endif

        <!-- Členové -->
        <div class="text-center mt-3 divider"><h4>Členové</h4></div>
        <nav class="nav flex-column">
            @foreach($accountMembers as $member)
                @php
                    $memberRole = $member->pivot->role;
                    $dateJoined = date_format(date_create($member->pivot->joined_at), "d.m.Y");
                    $roleColors = ['admin' => 'red', 'moderator' => 'orange', 'member' => 'black'];
                    $color = $roleColors[$memberRole];
                    $src = empty($member->avatar_path)
                        ? asset('images/default-avatar.png')
                        : asset(Storage::url($member->avatar_path));
                @endphp
                <a href="#" class="nav-link user-detail-box"
                   data-name="{{ $member->full_name }}"
                   data-username="{{ $member->username }}"
                   data-role="{{ $memberRole }}"
                   data-joined="{{ $dateJoined}}">
                    <x-profile-photo class="avatar me-3" :src="$src" />
                    {{ $member->full_name }} - <strong style="color:{{ $color }}">{{ $memberRole }}</strong>
                </a>
            @endforeach
        </nav>

        <footer>
            <p>
                @if($role === 'admin')
                    <button class="btn zoom-hover" id="delete-account-btn" type="button">
                        <i class="fa-solid fa-door-open fas "></i> &nbsp;Smazat účet
                    </button>
                @else
                    <button class="btn zoom-hover" id="leave-account-btn" type="button">
                        <i class="fa-solid fa-door-open fas "></i> &nbsp;Opustit účet
                    </button>
                @endif
            </p>
        </footer>
    </div>

    <!-- Modály -->
    <x-modal-form heading="Opustit účet" :action="route('removeUserFromAccount', $account)" id="leaveAccountModal"
                  class="modal-lg">
        @method("DELETE")
        <p>Opravdu si přejete opustit tento účet?</p>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-danger">Ano</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
        </div>
    </x-modal-form>

    @if($role === 'admin')
        <x-modal-form heading="Smazat účet" :action="route('deleteAccount', $account)" id="deleteAccountModal"
                      class="modal-lg">
            @method("DELETE")
            <p>Opravdu si přejete nenávratně smazat tento účet?</p>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-danger">Ano, smazat</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
            </div>
        </x-modal-form>
    @endif

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

    <x-modal-form heading="Vložit peníze" :action="route('depositMoney', $account)" id="depositMoneyModal"
                  class="modal-lg">
        <label for="depositAmount">Částka:
            <input type="number" id="depositAmount" name="amount" placeholder="Částka v CZK" required
                   value="{{ old('amount') }}">
        </label>
        @error('amount')
        <x-error :message="$message" /> @enderror

        <label for="textareaDeposit">Popis (nepovinný):
            <input name="description" id="textareaDeposit" maxlength="30" placeholder="Příspěvek na ..."
                   value="{{ old('description') }}">
        </label>
        @error('description')
        <x-error :message="$message" /> @enderror

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-secondary">Vložit</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Zpět</button>
        </div>
    </x-modal-form>

    <x-modal-form heading="Informace o uživateli" id="userInfoModal"
                  class="modal-lg">
        <table class="table table-hover table-striped">
            <tr>
                <th>Jméno</th>
                <td id="modal-name"></td>
            </tr>
            <tr>
                <th>Uživatelské jméno</th>
                <td id="modal-username"></td>
            </tr>
            <tr>
                <th>Role</th>
                <td id="modal-role"></td>
            </tr>
            <tr>
                <th>Připojil se</th>
                <td id="modal-joined"></td>
            </tr>
        </table>
    </x-modal-form>

@endsection

@section("scripts")
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>

    <script>
        $(document).ready(function() {
            $("#example").DataTable({
                responsive: true,
                language: {
                    search: "Hledat:",
                    lengthMenu: "_MENU_ záznamů na stránku",
                    info: "Zobrazují se záznamy _START_ až _END_ z _TOTAL_",
                    infoEmpty: "",
                    infoFiltered: "filtrováno z _MAX_ celkových záznamů",
                    zeroRecords: "Nenalezeny žádné odpovídající záznamy",
                    paginate: {
                        next: "Další",
                        previous: "Předchozí"
                    }
                }
            });

            // Otevření modálů
            $("#leave-account-btn").click(() => new bootstrap.Modal("#leaveAccountModal").show());
            $("#add-member-btn").click(() => new bootstrap.Modal("#addMemberModal").show());
            $("#delete-account-btn").click(() => new bootstrap.Modal("#deleteAccountModal").show());
            $("#deposit-money-btn").click(() => new bootstrap.Modal("#depositMoneyModal").show());
            // Modal s info o uživateli
            $(".user-detail-box").click(function() {
                const name = $(this).data("name");
                const username = $(this).data("username");
                const role = $(this).data("role");
                const joined = $(this).data("joined");

                $("#modal-name").text(name);
                $("#modal-username").text("@" + username);
                $("#modal-role").text(role);
                $("#modal-joined").text(joined);

                new bootstrap.Modal("#userInfoModal").show();
            });
        });
    </script>
@endsection
