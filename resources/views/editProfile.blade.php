@extends("layouts.default")
@section("heading", "Editace profilu")
@section("content")
    <x-form class="text-center" style="margin-top: 50px;" enctype="multipart/form-data" method="post"
            action="{{route('editProfile', ['user' => auth()->user()])}}">
        @method("put")
        <section class="d-flex gap-3">
            <div class="d-flex flex-column gap-5">
                <div class="input-group"><span class="input-group-text">Jméno</span><input class="form-control"
                                                                                           type="text"
                                                                                           name="name"
                                                                                           value="{{auth()->user()->name}} "
                                                                                           required/>
                </div>
                <div class="input-group"><span class="input-group-text">Příjmení</span><input class="form-control"
                                                                                              type="text"
                                                                                              name="surname"
                                                                                              value="{{auth()->user()->surname}}"
                                                                                              required/>
                </div>
                <div class="input-group"><span class="input-group-text">Email</span><input class="form-control"
                                                                                           name="email"
                                                                                           type="email"
                                                                                           value="{{auth()->user()->email}}"
                                                                                           required/>
                </div>
                <div class="input-group"><span class="input-group-text">Nové heslo</span><input class="form-control"
                                                                                                name="password"
                                                                                                type="password"/></div>
            </div>
            <div class="d-flex flex-column align-items-center gap-3">
                <x-profile-photo class="object-fit-cover"
                                 style="display: block;overflow: hidden;  background-image: url('{{ asset('images/profilePictures/default-avatar.png') }}'); background-size: cover; background-position: center center;"
                                 width="250" height="320"/>
                <input class="form-control" type="file" accept="image/png, image/jpg, image/jpeg" name="avatar"/>
                <input type="hidden" name="remove_avatar" id="removeAvatar" value="0">
                <button class="btn btn-outline-warning d-none" type="button" id="returnPhoto">Původní fotka</button>
                <button class="btn btn-outline-danger" type="button" id="removePhoto">Odebrat fotku</button>
            </div>
        </section>
        <button class="btn btn-outline-success" type="submit" style="margin-top: 60px;">Uložit změny</button>
    </x-form>
@endsection
@section("scripts")
    <script src="{{ asset('js/editProfilePhoto.js') }}"></script>
@endsection

