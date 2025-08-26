<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Client Dashboard</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-xA4i0pW+..." crossorigin="anonymous" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ url('css/app.css') }}">
    @stack('styles')

    <!-- Scripts -->
    <script src="{{ asset('js/script.js') }}" defer></script>
    @stack('scripts')
</head>
<body>

<!-- Header -->
<header class="main-header">
    <div class="container header-content">

        <nav class="main-nav">
            <ul>
                <li><a href="/">Home</a></li>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')"
                                         onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('Uitloggen') }}
                        </x-dropdown-link>
                    </form>
                @else
                    <li><a href="/login">login</a></li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<!-- Main Content -->
<main class="page-content">
    @yield('content')
</main>

<!-- Footer -->
<footer class="main-footer">
    <div class="container">
        <p>&copy; {{ date('Y') }} Quiz Applicatie. Developed by Marouan El Marnissy.</p>
    </div>
</footer>

</body>
</html>
