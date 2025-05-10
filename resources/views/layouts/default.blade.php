<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Share Money</title>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Alatsi&amp;subset=cyrillic-ext,latin-ext&amp;display=swap">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-primary" style="font-family: Alatsi, sans-serif;">
<nav class="navbar navbar-expand-md bg-secondary d-flex justify-content-between align-items-center">
    <div class="container-fluid">
        <a href="{{ route('dashboardPage') }}"
           style="width: 213px; height: 60px; background: url('{{ asset('images/logo/png/logo-no-background.png') }}') center / contain no-repeat;">
        </a>

        <div>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link"
                                            href="{{route('profileDetailPage',['user' => auth()->user()],)}}"
                                            style="font-size: 16px;color: var(--bs-black);">Můj
                            profil<i class="far fa-user" style="margin-left: 10px;"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<main
    class="d-flex position-relative d-lg-flex flex-column justify-content-center align-items-center justify-content-lg-center align-items-lg-center"
    style="margin: 50px 80px 0;">
    @if(! request()-> is("dashboard"))
        <a href="{{session('return_url', route('dashboardPage'))}}" class="back-arrow">
            <i class="fas fa-arrow-left"></i>
            Zpět
        </a>
    @endif
    <h1 class="display-4">@yield("heading")</h1>
    @yield("content")
</main>
@yield("scripts")
</body>
</html>
