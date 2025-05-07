@extends('layouts.storefront')

@section('title', 'Contactez-nous')

@section('content')
<div class="container mx-auto py-10 px-6">
    <h2 class="text-3xl font-semibold text-gray-800 dark:text-white text-center mb-6">Contactez-nous</h2>
    <p class="text-lg text-gray-600 dark:text-gray-300 text-center mb-8">
        Vous avez une question ? Remplissez le formulaire ci-dessous.
    </p>
    
    <form method="POST" action="{{ route('contact.submit') }}" class="max-w-xl mx-auto">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-white">Nom :</label>
            <input type="text" name="name" class="w-full px-4 py-2 rounded-md border bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-white">Email :</label>
            <input type="email" name="email" class="w-full px-4 py-2 rounded-md border bg-white dark:bg-gray-800 text-gray-800 dark:text-white">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-white">Message :</label>
            <textarea name="message" class="w-full px-4 py-2 rounded-md border bg-white dark:bg-gray-800 text-gray-800 dark:text-white"></textarea>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md">Envoyer</button>
    </form>
</div>
@endsection
