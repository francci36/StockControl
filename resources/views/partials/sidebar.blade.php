<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="http://127.0.0.1:8000/dashboard" class="brand-link">
        <span class="brand-text font-weight-light">MonSite</span>
    </a>
    <div class="sidebar">
        @auth
        <!-- User Account -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                    <img 
                        src="{{ Auth::user()->profile_photo_url ? asset('storage/' . Auth::user()->profile_photo_url) : asset('images/default-profile.png') }}" 
                        class="img-circle elevation-2" 
                        alt="{{ Auth::user()->name }}">
            </div>
            <div class="info">
                <a href="{{ route('profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Déconnexion</button>
                </form>
            </div>
        </div>
        @endauth


        <!-- Navigation Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="http://127.0.0.1:8000/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="http://127.0.0.1:8000/stocks" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Stocks</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="http://127.0.0.1:8000/orders" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Commandes</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="http://127.0.0.1:8000/suppliers" class="nav-link">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Fournisseurs</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="http://127.0.0.1:8000/rapports" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Rapports</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="http://127.0.0.1:8000/transactions" class="nav-link">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>Transactions</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="http://127.0.0.1:8000/transactions/create" class="nav-link">
                        <i class="nav-icon fas fa-plus"></i>
                        <p>Nouvelle Transaction</p>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Bouton Mode Sombre -->
        <div class="mt-4 p-3 d-flex justify-content-center">
        <button id="darkModeToggle" class="btn btn-outline-secondary w-full truncate">
            Activer le Mode Sombre
        </button>
        </div>
    </div>
</aside>

<script>
   document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('darkModeToggle');

    // Fonction pour activer/désactiver le mode sombre
    const toggleDarkMode = () => {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    };

    // Appliquer le thème sauvegardé au chargement de la page
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
    }

    // Ajouter l'événement au bouton de bascule
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', toggleDarkMode);
    }
});
</script>