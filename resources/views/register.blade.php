<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @voltStyles
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="password_confirmation">Confirmer le mot de passe :</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit">S'inscrire</button>
        </form>

        <!-- Composant Volt pour l'inscription -->
        @volt('pages.auth.register')
    </div>

    @voltScripts
</body>
</html>
