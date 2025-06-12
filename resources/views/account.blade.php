@extends("layouts.default")
@section("custom-css")
    @vite(["resources/css/accountPage.css"])
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.bootstrap5.css" />
@endsection
@section("heading", $account->name)
@section("content")
    @php
        $role = $account->getUserRole(auth()->id());
    @endphp
    <h3 style="color: rgb(163,163,163);font-size: 20px;margin-bottom: 40px;">
        vlastník: &nbsp;{{$account->owner_name}}</h3>
    <p class="lead text-center text-success" style="font-size: 45px;margin-bottom: 26px;">{{$account->balance}}
        CZK</p>
    <div class="d-flex d-xl-flex gap-5">
        <button class="btn btn-outline-success" type="button">Zadat platbu</button>
        <button class="btn btn-outline-light" type="button">Vložit peníze</button>
    </div>
    <!-- Výpis účtu -->
    <div class="container" style="margin-top: 66px;width: 50%;">
        <div class="row gx-5 gy-3 row-cols-1">
            <div class="col">
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
                        <tr>
                            <td>22.05.2025</td>
                            <td>Platba za úklid</td>
                            <td>500 CZK</td>
                            <td>Jan Marek</td>
                        </tr>
                        <tr>
                            <td>22.05.2025</td>
                            <td>Platba za úklid</td>
                            <td>500 CZK</td>
                            <td>Jan Marek</td>
                        </tr>
                        <tr>
                            <td>22.05.2025</td>
                            <td>Platba za úklid</td>
                            <td>500 CZK</td>
                            <td>Jan Marek</td>
                        </tr>
                        <tr>
                            <td>22.05.2025</td>
                            <td>Platba za úklid</td>
                            <td>500 CZK</td>
                            <td>Jan Marek</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        @if($role ==="admin")
            <div class="divider p-3 text-center">
                <a href="" class="btn btn-info btn-lg" role="button">Spravovat účet</a>
            </div>
        @endif
        @if($role !="user")
            <div class="text-center mt-4">
                <button class="btn-outline-warning btn btn-lg mb-3" id="add-member-btn">
                    Přidat člena <i
                        class="fas fa-plus-square"></i></button>
            </div>
        @endif
        <!-- Členové účtu -->
        <div class="text-center mt-3 divider"><h4>Členové</h4></div>


        <nav class="nav flex-column">
            <a href="#" class="nav-link divider">
                <x-profile-photo class="avatar me-3" />
                Jan Párek
            </a>
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
    <!-- Modal pro opuštění účtu -->
    <x-modal-form heading="Opustit účet" :action="route('removeUserFromAccount', $account->id)"
                  id="leaveAccountModal" class="modal-lg">
        @method("DELETE")
        <div>Opravdu si přejete opustit tento účet?</div>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-danger">Ano</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
        </div>
    </x-modal-form>
    <!-- Modal pro smazání účtu -->
    @if($role === 'admin')
        <x-modal-form heading="Smazat účet" :action="route('deleteAccount', $account->id)"
                      id="deleteAccountModal" class="modal-lg">
            @method("DELETE")
            <div>Opravdu si přejete nenávratně smazat tento účet? Tato akce je nevratná.</div>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button type="submit" class="btn btn-danger">Ano, smazat</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
            </div>
        </x-modal-form>
    @endif
    <!-- Modal pro přidání člena -->
    <x-modal-form heading="Přidat člena" :action="route('addMemberToAccount', $account->id)"
                  id="addMemberModal" class="modal-lg">
        <label>
            Zadejte username nového člena:
            <input type="text" name="userName" placeholder="@username" value="@">
        </label>
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
    <script>
        $(document).ready(function() {
            $("#example").DataTable({
                responsive: true,
                language: {
                    search: "Hledat:",
                    lengthMenu: "_MENU_ záznamů na stránku",
                    info: "Zobrazují se záznamy  _START_ až _END_ z celkových _TOTAL_ záznamů",
                    infoEmpty: "",
                    infoFiltered: "filtrováno z _MAX_ celkových záznamů",
                    zeroRecords: "Nenalezeny žádné odpovídající záznamy",
                    paginate: {
                        next: "Další",
                        previous: "Předchozí"
                    }
                }
            });

            // Modal na opuštění účtu
            $("#leave-account-btn").on("click", function() {
                var modal = new bootstrap.Modal(document.getElementById("leaveAccountModal"));
                modal.show();
            });
            // Modal na přidání člena
            $("#add-member-btn").on("click", function() {
                var modal = new bootstrap.Modal(document.getElementById("addMemberModal"));
                modal.show();
            });
            // Modal na smazání účtu (pouze pro admina)
            $("#delete-account-btn").on("click", function() {
                var modal = new bootstrap.Modal(document.getElementById("deleteAccountModal"));
                modal.show();
            });
        });
    </script>
    <!-- Reopen modalu pokud je nějaká chyba -->
    @if(session('modal'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var modalId = "{{ session('modal') }}";
                var modalElement = document.getElementById(modalId);
                if (modalElement) {
                    var modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        </script>
    @endif

@endsection

