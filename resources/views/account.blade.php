@extends("layouts.default")

@section("custom-css")
    @vite(["resources/css/accountPage.css"])
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" />
@endsection

@section("heading", $account->name)

@section("content")
    @php
        $userId = auth()->id();
        $role = $account->getUserRole($userId);
        $accountMembers = $account->users;
        $transactions = $account->transactions();
    @endphp

    <h3 style="color: rgb(163,163,163);font-size: 20px;margin-bottom: 40px;">
        vlastník: &nbsp;{{ $account->admin_name }}
    </h3>

    <p class="lead text-center text-success" style="font-size: 45px;margin-bottom: 26px;">
        {{ $account->balance }} CZK
    </p>
    <div class="d-flex gap-5 align-items-center">
        @if($role === "member")
            <span data-toggle="tooltip"
                  title="Jen admin a moderátor mohou zadávat platby.">
            <button class="btn btn-outline" id="send-payment-btn" style="pointer-events: none" disabled>
                Zadat platbu
            </button>
            </span>
        @else
            <button class="btn btn-outline-success" id="send-payment-btn" type="button">Zadat platbu</button>
        @endif
        <button class="btn btn-outline-light" id="deposit-money-btn" type="button">Vložit peníze</button>
    </div>
    <!-- Tabulka výpisu -->
    <div class="container mt-5" style="width: 50%;">
        <div class="table-responsive">
            <table id="transaction-history-table" class="table table-striped nowrap">
                <thead>
                <tr>
                    <th>Datum</th>
                    <th>Typ</th>
                    <th>Popis</th>
                    <th>Částka</th>
                    <th>Odesílatel/Vložil</th>
                </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
                    <tr class={{$transaction->amount > 0 ? "negative-row" : "positive-row"}}>
                        <td>{{date_format(date_create($transaction->created_at), "d.m.y H:i")}}</td>
                        <td>
                            @if($transaction->type_id === 1)
                                Platba
                            @else
                                Vklad
                            @endif
                        </td>
                        <td>{{$transaction->description}}</td>
                        <td id="amountTd">{{$transaction->amount}} CZK</td>
                        <td>
                            @if ($transaction->user)
                                {{ $transaction->user->full_name }}
                            @else
                                {{ $transaction->recipient_account_id }}
                            @endif
                        </td>
                    </tr>
                @endforeach
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

        @if($role !== "member")
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

    <!-- Opustit učet modal -->
    <x-modal-form heading="Opustit účet" :action="route('removeUserFromAccount', $account)" id="leaveAccountModal"
                  class="modal-lg">
        @method("DELETE")
        <p>Opravdu si přejete opustit tento účet?</p>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-danger">Ano</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
        </div>
    </x-modal-form>
    <!-- Smazat učet (pouze admin) modal -->
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
    <!-- vložit peníze modal -->
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
    <!-- Info o uživateli modal -->
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
    <!-- Zadat platbu (admin a moderator) modal -->
    <x-modal-form heading="Zadat platbu" id="sendPaymentModal" class="modal-lg"
                  :action="route('sendPayment', $account)">
        <label for="recipient"> Číslo příjemce:
            <input type="number" id="recipient" name="recipient" placeholder="00000000" required
                   value="{{ old('recipient') }}">
        </label>
        @error('recipient')
        <x-error :message="$message" />
        @enderror
        <label for="paymentAmount">Částka:
            <input type="number" id="paymentAmount" name="amount" min="1" max="{{$account->balance}}"
                   placeholder="Částka v CZK" required
                   value="{{ old('amount') }}">
        </label>
        @error('amount')
        <x-error :message="$message" />
        @enderror

        <label for="textareaPayment">Popis (nepovinný):
            <input name="description" id="textareaPayment" maxlength="30"
                   placeholder="Platba za ..." value="{{ old('description') }}">
        </label>
        @error('description')
        <x-error :message="$message" />
        @enderror

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-secondary">Zadat</button>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Zpět</button>
        </div>
    </x-modal-form>

@endsection

@section("scripts")
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    @vite("resources/js/accountDetail.js")
@endsection
