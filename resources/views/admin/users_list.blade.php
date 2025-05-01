@extends('layouts.app')

@section('content')
    <h2>Liste des utilisateurs</h2>

    <a href="{{ route('admin.users.create.form') }}">Créer un nouvel utilisateur</a>

    <table border="1">
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
            </tr>
        @endforeach
    </table>
@endsection
