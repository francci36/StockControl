<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Styles CSS avec Vite -->
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div id="app" class="flex flex-col min-h-screen">
        <!-- Barre de navigation -->
        @if (Route::has('login'))
            <header class="shadow-sm">
                @include('layouts.navigation')
            </header>
        @endif

        <!-- Contenu principal -->
        <main class="flex-grow container mx-auto p-4">
            @yield('content')
        </main>

        <!-- Pied de page -->
        @include('layouts.footer')
    </div>

    <!-- Scripts avec Vite -->
    @vite('resources/js/app.js')

    <!-- Scripts supplémentaires -->
    @yield('scripts')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    
</body>
</html>
