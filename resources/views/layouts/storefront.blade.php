<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Boutique</title>
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('storefront') }}" class="nav-link">Accueil Boutique</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cart.show') }}">
                    <i class="fas fa-shopping-cart"></i> Panier
                    <span class="badge badge-primary" id="cart-count">
                        {{ array_sum(array_column(Session::get('cart', []), 'quantity')) }}
                    </span>
                </a>
            </li>
            @guest
                <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Connexion</a></li>
                <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Inscription</a></li>
            @else
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#"><i class="far fa-user"></i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="fas fa-user-cog mr-2"></i> Mon compte</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i> Déconnexion</button>
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('storefront') }}" class="brand-link"><span class="brand-text font-weight-light">Ma Boutique</span></a>

        <div class="sidebar">
            @auth
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ Auth::user()->profile_photo_url ? asset('storage/' . Auth::user()->profile_photo_url) : asset('images/default-profile.png') }}" 
                         class="img-circle elevation-2" alt="Image utilisateur">
                </div>
                <div class="info"><a href="{{ route('profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a></div>
            </div>
            @endauth

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-header">CATÉGORIES</li>
                    @if(isset($categories) && count($categories) > 0)
                        @foreach($categories as $category)
                        <li class="nav-item">
                            <a href="{{ route('storefront', ['category' => $category->id]) }}" class="nav-link {{ request('category') == $category->id ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tag"></i>
                                <p>{{ $category->name }}</p>
                            </a>
                        </li>
                        @endforeach
                    @else
                        <li class="nav-item text-white p-2">Aucune catégorie disponible</li>
                    @endif

                    <li class="nav-header">MON COMPTE</li>
                    @auth
                    <li class="nav-item"><a href="{{ route('orders.index') }}" class="nav-link"><i class="nav-icon fas fa-box-open"></i> Mes commandes</a></li>
                    @endauth
                    <li class="nav-item"><a href="{{ route('contact') }}" class="nav-link"><i class="nav-icon fas fa-envelope"></i> Contact</a></li>
                </ul>
            </nav>

            <div class="mt-auto p-3">
                <button id="darkModeToggle" class="btn btn-block btn-outline-light"><i class="fas fa-moon mr-2"></i>Mode Sombre</button>
            </div>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1 class="m-0">@yield('page-title', 'Boutique')</h1></div>
                    <div class="col-sm-6"><ol class="breadcrumb float-sm-right">@yield('breadcrumb')</ol></div>
                </div>
            </div>
        </div>

        <section class="content"><div class="container-fluid">@yield('content')</div></section>
    </div>

    <footer class="main-footer bg-dark text-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4"><h5>Notre Boutique</h5><p>Votre destination pour des produits de qualité à des prix compétitifs.</p></div>
                <div class="col-md-4">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('storefront') }}" class="text-light">Accueil</a></li>
                        <li><a href="{{ route('contact') }}" class="text-light">Contact</a></li>
                        <li><a href="#" class="text-light">Conditions générales</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Suivez-nous</h5>
                    <a href="#" class="text-light mr-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light mr-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light mr-2"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="text-center py-3 bg-black">© {{ date('Y') }} Ma Boutique. Tous droits réservés.</div>
    </footer>
</div>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<<!-- Supprimez le double chargement de jQuery -->
<script>
    // Dark Mode Toggle
    document.getElementById('darkModeToggle').addEventListener('click', function() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    });

    // Appliquer le thème au chargement
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
    }

    // Fonction pour afficher les notifications
    function showAlert(message, type = 'success') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} fixed-top w-50 mx-auto mt-2 text-center`;
        alert.textContent = message;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 3000);
    }

    // Gestion de l'ajout au panier
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.dataset.productId;
            
            try {
                const response = await fetch('http://127.0.0.1:8000/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': 'XaKzlrUfcBBkuIcoGLzl8CmSlxygz0Db4lNIeW5K'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });

                const data = await response.json();
                
                if (response.ok) {
                    showAlert('Produit ajouté au panier');
                    // Mettre à jour le compteur
                    const cartCount = document.getElementById('cart-count');
                    if (cartCount) {
                        cartCount.textContent = parseInt(cartCount.textContent) + 1;
                    }
                } else {
                    showAlert(data.message || 'Erreur', 'danger');
                }
            } catch (error) {
                showAlert('Erreur de connexion', 'danger');
                console.error('Error:', error);
            }
        });
    });
    $(document).ready(function() {
    // Ajout au panier
    $(document).on('click', '.add-to-cart', function() {
        const productId = $(this).data('product-id');
        
        $.post('{{ route("cart.add") }}', {
            _token: '{{ csrf_token() }}',
            product_id: productId,
            quantity: 1
        }, function(response) {
            if (response.success) {
                $('#cart-count').text(response.cart_count);
                toastr.success('Produit ajouté au panier');
            }
        }).fail(function() {
            toastr.error('Une erreur est survenue');
        });
    });

    // Suppression du panier
    $(document).on('click', '.remove-from-cart', function() {
        if (!confirm('Êtes-vous sûr de vouloir retirer ce produit ?')) return;
        
        const productId = $(this).data('product-id');
        
        $.post('{{ route("cart.remove") }}', {
            _token: '{{ csrf_token() }}',
            product_id: productId
        }, function(response) {
            if (response.success) {
                $('#cart-count').text(response.cart_count);
                location.reload();
            }
        }).fail(function() {
            toastr.error('Une erreur est survenue');
        });
    });

    // Mise à jour de la quantité
    $(document).on('change', '.update-quantity', function() {
        const productId = $(this).data('product-id');
        const quantity = $(this).val();
        
        $.post('{{ route("cart.update") }}', {
            _token: '{{ csrf_token() }}',
            product_id: productId,
            quantity: quantity
        }, function(response) {
            if (response.success) {
                $('#cart-count').text(response.cart_count);
                location.reload();
            }
        }).fail(function() {
            toastr.error('Une erreur est survenue');
        });
    });
});

// Fonction pour mettre à jour le compteur du panier
function updateCartCount() {
    $.get('{{ route("cart.count") }}', function(response) {
        $('#cart-count').text(response.count);
    });
}
</script>
</html>