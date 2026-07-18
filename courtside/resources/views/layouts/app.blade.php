<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Courtside')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --navy: #1F3864;
            --navy-light: #2E5090;
        }

        body {
            background-color: #F4F6F9;
        }

        .navbar-courtside {
            background-color: var(--navy);
        }

        .btn-navy {
            background-color: var(--navy);
            border-color: var(--navy);
            color: #fff;
        }

        .btn-navy:hover {
            background-color: var(--navy-light);
            border-color: var(--navy-light);
            color: #fff;
        }

        .card-auth {
            max-width: 420px;
            margin: 60px auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark navbar-courtside px-4">
        <span class="navbar-brand mb-0 h1">🏸 Courtside</span>
        @auth
        <div class="d-flex align-items-center text-white">
            <div class="dropdown me-3">
                <button class="btn btn-sm btn-outline-light position-relative dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    🔔
                    @if (auth()->user()->unreadNotifications->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 320px;">
                    @forelse (auth()->user()->unreadNotifications->take(5) as $notif)
                    <li>
                        <form action="{{ route('notifikasi.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-wrap small">
                                {{ $notif->data['message'] ?? 'Notifikasi baru' }}
                            </button>
                        </form>
                    </li>
                    @empty
                    <li><span class="dropdown-item-text text-muted small">Tidak ada notifikasi baru.</span></li>
                    @endforelse
                    @if (auth()->user()->unreadNotifications->count() > 0)
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('notifikasi.read-all') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item small text-center">Tandai semua sudah dibaca</button>
                        </form>
                    </li>
                    @endif
                </ul>
            </div>
            <span class="me-3">{{ auth()->user()->nama }} ({{ auth()->user()->role }})</span>
            <a href="{{ route('profil.edit') }}" class="btn btn-sm btn-outline-light me-2">Profil Saya</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-outline-light">Logout</button>
            </form>
        </div>
        @endauth
    </nav>

    <div class="container mt-4">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>