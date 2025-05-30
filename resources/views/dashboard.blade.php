@extends("layouts.default")
@section("heading", "Dashboard")
@section("content")
    @if(session('success'))
        <x-toast>
            {{ session("success") }}
        </x-toast>
    @endif
    <x-profile-photo class="object-fit-cover border rounded-circle"
                     style="display: block;overflow: hidden;width: 110px;height: 110px;padding: 2px;margin-top: 25px;margin-bottom: 10px;"
                     width="117" height="117" />
    <h3>{{auth()->user()->full_name}}</h3>
    <div class="d-inline-flex justify-content-between align-items-center"
         style="margin-top: 100px;width: 70%;margin-bottom: 0;">
        <h1>Sdílené účty</h1>
        <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#myModal" data-toggle="button"
                style="background: var(--bs-secondary);border-radius: 13px;box-shadow: 1px 1px 3px 0 rgb(88,84,84);">
            + &nbsp;&nbsp; založit účet
        </button>
    </div>
    <div class="container" style="margin-top: 66px;">
        <div class="form-check" style="margin-bottom: 19px;"><input class="form-check-input"
                                                                    style="border: 2px solid black" type="checkbox"
                                                                    id="formCheck-2"><label class="form-check-label"
                                                                                            for="formCheck-2">Pouze mé
                účty</label></div>
        <div class="d-none text-center" id="no-admin-accounts-msg"><h4 style="color: darkgrey">Aktuálně nevlastníte
                žádný
                účet</h4></div>

        <div class="row gx-5 gy-3 row-cols-2">
            @foreach(auth()->user()->accounts as $account)
                <div class="col" data-flag="{{ $account->pivot->role === 'admin' ? 'admin' : '' }}">
                    <a href="{{ route('accountDetailPage', $account) }}">
                        <div data-bss-disabled-mobile="true" data-bss-hover-animate="pulse"
                             style="background: var(--bs-secondary-bg);border-radius: 13px;box-shadow: 4px 0px 3px rgb(231,226,226);">
                            <h3 class="text-center">{{$account->name}}</h3>
                            <p class="lead text-center text-success">{{$account->balance}}CZK</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <x-modal-form heading="Nový účet" :action="route('createAccount')">
        <input type="text" name="name" placeholder="Název účtu" maxlength="15">
        <button type="submit" class="btn btn-success w-100">Vytvořit nový účet</button>
    </x-modal-form>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkbox = document.getElementById("formCheck-2");
        const accounts = document.querySelectorAll(".col[data-flag]");
        const noAdminMsg = document.getElementById("no-admin-accounts-msg");

        checkbox.addEventListener("change", function() {
            let visibleAdminCount = 0;

            accounts.forEach(account => {
                const isAdmin = account.getAttribute("data-flag") === "admin";

                if (this.checked) {
                    if (!isAdmin) {
                        account.classList.add("d-none");
                    } else {
                        account.classList.remove("d-none");
                        visibleAdminCount++;
                    }
                } else {
                    account.classList.remove("d-none");
                }
            });

            if (this.checked && visibleAdminCount === 0) {
                noAdminMsg.classList.remove("d-none");
                noAdminMsg.classList.add("d-block");
            } else {
                noAdminMsg.classList.remove("d-block");
                noAdminMsg.classList.add("d-none");
            }
        });
    });
</script>


