@extends("layouts.default")
@section("heading", "Dashboard")
@section("content")
    <x-profile-photo class="object-fit-cover border rounded-circle"
                     style="display: block;overflow: hidden;width: 110px;height: 110px;padding: 2px;margin-top: 25px;margin-bottom: 10px;"
                     width="117" height="117"/>
    <h3>{{auth()->user()->full_name}}</h3>
    <div class="d-inline-flex justify-content-between align-items-center"
         style="margin-top: 100px;width: 70%;margin-bottom: 0;">
        <h1>Sdílené účty</h1>
        <button class="btn btn-primary" type="button"
                style="--bs-body-bg: var(--bs-secondary);background: var(--bs-secondary);border-radius: 13px;box-shadow: 1px 1px 3px 0px rgb(88,84,84);">
            + nový
        </button>
    </div>
    <div class="container" style="margin-top: 66px;">
        <div class="form-check" style="margin-bottom: 19px;"><input class="form-check-input"
                                                                    style="border: 2px solid black" type="checkbox"
                                                                    id="formCheck-2"><label class="form-check-label"
                                                                                            for="formCheck-2">Pouze mé
                účty</label></div>
        <div class="row gx-5 gy-3 row-cols-2">
            <div class="col">
                <div data-bss-disabled-mobile="true" data-bss-hover-animate="pulse"
                     style="background: var(--bs-secondary-bg);border-radius: 13px;box-shadow: 4px 0px 3px rgb(231,226,226);">
                    <h3 class="text-center">Hokejový tým</h3>
                    <p class="lead text-center text-success">23 450 CZK</p>
                </div>
            </div>
            <div class="col">
                <div
                    style="background: var(--bs-secondary-bg);border-radius: 13px;box-shadow: 4px 0 3px rgb(231,226,226);">
                    <h3 class="text-center">Nájem</h3>
                    <p class="lead text-center text-success">500 CZK</p>
                </div>
            </div>
            <div class="col">
                <div
                    style="background: var(--bs-secondary-bg);border-radius: 13px;box-shadow: 4px 0 3px rgb(231,226,226);">
                    <h3 class="text-center">Hospodské pátečky</h3>
                    <p class="lead text-center text-success">10 000 CZK</p>
                </div>
            </div>
            <div class="col">
                <div
                    style="background: var(--bs-secondary-bg);border-radius: 13px;box-shadow: 4px 0 3px rgb(231,226,226);">
                    <h3 class="text-center">Filmový klan</h3>
                    <p class="lead text-center text-success">300 CZK</p>
                </div>
            </div>
        </div>
    </div>
@endsection

