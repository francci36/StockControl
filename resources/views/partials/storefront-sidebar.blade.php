<aside class="bg-gray-800 text-white p-4 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Catégories</h2>
    <ul class="space-y-2">
        @foreach($categories as $category)
            <li>
                <a href="{{ route('storefront', ['category' => $category->id]) }}" 
                   class="block py-3 px-4 rounded-lg hover:bg-blue-600 transition duration-300 @if(request('category') == $category->id) bg-blue-500 text-white font-semibold @else text-gray-300 @endif">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>
</aside>