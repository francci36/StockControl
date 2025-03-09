<!-- resources/views/partials/use_account.blade.php -->
<div class="user-account">
    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle" width="30" height="30">
    <span>{{ Auth::user()->name }}</span>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="dropdown-item">Déconnexion</button>
    </form>
</div>
