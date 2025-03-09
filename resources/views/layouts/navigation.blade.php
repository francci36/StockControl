<nav class="bg-dark text-light py-4 shadow-lg">
    <div class="container flex justify-between items-center">
        <!-- Logo ou nom du site -->
        <a href="{{ route('dashboard') }}" class="text-xl font-bold">MonSite</a>

        <!-- Liens de navigation (visibles sur les grands écrans) -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="{{ route('dashboard') }}" class="text-light hover:text-gray-300 transition duration-300">Dashboard</a>
            @guest
                <a href="{{ route('home') }}" class="text-light hover:text-gray-300 transition duration-300">Accueil</a>
            @endguest
            <a href="{{ route('stocks.index') }}" class="text-light hover:text-gray-300 transition duration-300">Stocks</a>
            <a href="{{ route('orders.index') }}" class="text-light hover:text-gray-300 transition duration-300">Commandes</a>
            <a href="{{ route('suppliers.index') }}" class="text-light hover:text-gray-300 transition duration-300">Fournisseurs</a>
            <a href="{{ route('rapports.index') }}" class="text-light hover:text-gray-300 transition duration-300">Rapports</a>
            <a href="{{ route('transactions.index') }}" class="text-light hover:text-gray-300 transition duration-300">Transactions</a>
            <a href="{{ route('transactions.create') }}" class="text-light hover:text-gray-300 transition duration-300">Nouvelle Transaction</a>
        </div>

        <!-- Informations utilisateur (visibles sur les grands écrans) -->
        <div class="hidden md:flex items-center space-x-4">
            @auth
                <!-- Photo de profil -->
                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-full w-10 h-10 border-2 border-light hover:border-gray-300 transition duration-300">
                <!-- Nom de l'utilisateur -->
                <span class="text-light">{{ Auth::user()->name }}</span>
                <!-- Formulaire de déconnexion -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-light hover:text-gray-300 transition duration-300">Déconnexion</button>
                </form>
            @endauth

            @guest
                <!-- Liens de connexion et d'inscription -->
                <a href="{{ route('login') }}" class="text-light hover:text-gray-300 transition duration-300">Connexion</a>
                <a href="{{ route('register') }}" class="text-light hover:text-gray-300 transition duration-300">Inscription</a>
            @endguest
        </div>

        <!-- Bouton du menu hamburger (visible sur les petits écrans) -->
        <button id="menu-toggle" class="md:hidden text-light focus:outline-none" aria-label="Toggle navigation" title="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Menu déroulant pour les petits écrans -->
    <div id="mobile-menu" class="md:hidden hidden mt-4">
        <a href="{{ route('dashboard') }}" class="block py-2 px-4 text-light hover:bg-gray-700">Dashboard</a>
        @guest
            <a href="{{ route('home') }}" class="block py-2 px-4 text-light hover:bg-gray-700">Accueil</a>
        @endguest
        <a href="{{ route('stocks.index') }}" class="block py-2 px-4 text-light hover:bg-gray-700">Stocks</a>
        <a href="{{ route('orders.index') }}" class="block py-2 px-4 text-light hover:bg-gray-700">Commandes</a>
        <a href="{{ route('suppliers.index') }}" class="block py-2 px-4 text-light hover:bg-gray-700">Fournisseurs</a>
        <a href="{{ route('rapports.index') }}" class="block py-2 px-4 text-light hover:bg-gray-700">Rapports</a>
        <a href="{{ route('transactions.index') }}" class="block py-2 px-4 text-light hover:bg-gray-700">Transactions</a>
        <a href="{{ route('transactions.create') }}" class="block py-2 px-4 text-light hover:bg-gray-700">Nouvelle Transaction</a>

        <!-- Informations utilisateur -->
        @auth
            <div class="border-t border-gray-700 mt-2 pt-2">
                <div class="flex items-center px-4 py-2">
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-full w-8 h-8 border-2 border-light">
                    <span class="ml-3 text-light">{{ Auth::user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 px-4 text-light hover:bg-gray-700">Déconnexion</button>
                </form>
            </div>
        @endauth

        @guest
            <div class="border-t border-gray-700 mt-2 pt-2">
                <a href="{{ route('login') }}" class="block py-2 px-4 text-light hover:bg-gray-700">Connexion</a>
                <a href="{{ route('register') }}" class="block py-2 px-4 text-light hover:bg-gray-700">Inscription</a>
            </div>
        @endguest
    </div>
</nav>

<!-- Script pour le menu hamburger -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        menuToggle.addEventListener('click', function () {
            mobileMenu.classList.toggle('show');
        });
    });
</script>
