<!-- resources/views/components/dropdown.blade.php -->

<div class="relative">
    <x-slot name="trigger">
        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
            {{ $trigger }}
        </button>
    </x-slot>

    <div class="absolute right-0 mt-2 w-48 rounded-md shadow-lg origin-top-right">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
            {{ $content }}
        </div>
    </div>
</div>
