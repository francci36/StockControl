<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
        </li>
        @guest
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('home') }}" class="nav-link">Accueil</a>
            </li>
        @endguest
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @guest
            <!-- Liens de connexion et d'inscription -->
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link">Connexion</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('register') }}" class="nav-link">Inscription</a>
            </li>
        @endguest
    </ul>
</nav>
