<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="http://127.0.0.1:8000/dashboard" class="brand-link">
        <span class="brand-text font-weight-light">MonSite</span>
    </a>
    <div class="sidebar">
        @auth
        <!-- User Account -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::user()->profile_photo_url }}" class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Déconnexion</button>
                </form>
            </div>
        </div>
        @endauth
        <!-- Pas besoin d'inclure use_account ici, car il est déjà dans la navbar -->
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
    </div>
</aside>
