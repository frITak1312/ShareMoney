<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Share Money</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('custom-css')
</head>
<body class="bg-primary" style="font-family: Kanit, sans-serif;">
<nav class="navbar navbar-expand-md bg-secondary d-flex justify-content-between align-items-center">
    <div class="container-fluid">
        <a href="{{ route('dashboardPage') }}"
           style="width: 213px; height: 60px; background: url('{{ asset('images/logo/png/logo-no-background.png') }}') center / contain no-repeat;">
        </a>

        <div>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button"
                           data-bs-toggle="dropdown">
                            <i class="fa fa-user"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: unset">
                            <li>
                                <a class="dropdown-item"
                                   href="{{route('profileDetailPage',['user' => auth()->user()],)}}">
                                    <i class="fa fa-edit"></i> Můj profil
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <x-form method="post" class="mb-0" action="{{route('logout')}}">
                                    <button class="dropdown-item" type="submit">
                                        <i class="fa fa-sign-out-alt"></i> Odhlásit se
                                    </button>
                                </x-form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<main
    class="d-flex position-relative d-lg-flex flex-column justify-content-center align-items-center justify-content-lg-center align-items-lg-center"
    style="margin: 50px 80px 0;">
    @if(! request()-> is("dashboard"))
        <a href="{{session('return_url', route("dashboardPage"))}}" class="back-arrow">
            <i class="fas fa-arrow-left"></i>
            Zpět
        </a>
    @endif
    <h1 class="display-4">@yield("heading")</h1>
    @if(session('success'))
        <x-toast />
    @endif
    @yield("content")
</main>
@yield("scripts")
</body>
</html>
