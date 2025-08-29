<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - QuizLab</title>

    <!-- Fonts & Icons -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

<header class="frontend-header">
    <div class="container-header">
        <a href="{{ url('/') }}" class="logo">
            <span class="logo-quiz">Quiz</span><span class="logo-lab">Lab</span>
        </a>
        <nav class="nav-links">
            <a href="{{ route('index') }}">Home</a>
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-dropdown-link :href="route('logout')"
                                     onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        {{ __('Uitloggen') }}
                    </x-dropdown-link>
                </form>
                <a href="{{ route('quizzes.index') }}">Quizzes</a>
                @auth
                    @if(auth()->check() && auth()->user()->role === 'student')
                        <a href="">Mijn Scores</a>

                    @elseif(auth()->check() && auth()->user()->role === 'teacher')
                        <a href="">Docent</a>
                    @endif
                @endauth

            @else
                <li><a href="/login">login</a></li>
            @endauth
        </nav>

        <!-- Theme toggle -->
        <div class="theme-toggle" id="theme-toggle">
            <i class="fa-solid fa-sun"></i>
        </div>
    </div>
</header>

<!-- Main Content -->
<main>
    @yield('content')
</main>

<!-- Footer -->
<footer class="frontend-footer">
    &copy; {{ date('Y') }} QuizLab. Developed by Marouan.
</footer>

<script>
    const toggle = document.getElementById('theme-toggle');
    const html = document.documentElement;

    // Check of de gebruiker al een theme in localStorage heeft
    if (localStorage.getItem('theme') === 'dark') {
        html.classList.add('dark');
        toggle.innerHTML = '<i class="fa-solid fa-moon"></i>';
    }

    // Klik-event om te togglen
    toggle.addEventListener('click', () => {
        html.classList.toggle('dark');

        if(html.classList.contains('dark')) {
            toggle.innerHTML = '<i class="fa-solid fa-moon"></i>';
            localStorage.setItem('theme', 'dark');
        } else {
            toggle.innerHTML = '<i class="fa-solid fa-sun"></i>';
            localStorage.setItem('theme', 'light');
        }
    });

</script>

</body>
</html>
