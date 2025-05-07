<nav class="bg-gray-800 text-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-3">
            <a href="{{ route('storefront') }}" class="text-xl font-bold hover:text-blue-300">Boutique en Ligne</a>
            
            <div class="flex items-center space-x-6">
                <a href="{{ route('contact') }}" class="hover:text-blue-300 hidden md:block">Contact</a>
                
                <!-- Bouton menu mobile -->
                <button class="md:hidden focus:outline-none" id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>