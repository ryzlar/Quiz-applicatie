<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - QuizLab</title>

    <!-- CSS -->
    <script src="https://kit.fontawesome.com/430ad301a9.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Serif:ital,wght@0,400;0,600;1,400;1,600&amp;family=Red+Hat+Display:wght@900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('js/script.js') }}">
    @stack('styles')
    <link rel="icon" href="{{ asset('images/204138825_icon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          integrity="sha512-xA4i0pW+I5+9UZgeYVrKRukaEpdVNeUArN/P6/FmhDyA9JtUnLg6am3c2NnC18p5icHN4LJgV1Xp7wsjZ/uYew=="
          crossorigin="anonymous"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="body-auth">
<!-- Header -->
<header>

</header>

<div class="theme-toggle" id="theme-toggle"><i class="fa-solid fa-sun"></i></div>


<!-- Main Content -->
<main>
    @yield('content')
</main>

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




<!-- JavaScript -->
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>


</html>
