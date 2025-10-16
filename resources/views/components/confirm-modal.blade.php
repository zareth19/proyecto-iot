{{-- resources/views/components/confirm-modal.blade.php --}}
<div 
    x-show="$root['{{ $trigger }}']" 
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
>
    <div 
        @click.away="$root['{{ $trigger }}'] = false"
        class="bg-white rounded-lg shadow-lg p-6 w-80 text-center"
    >
        <i class="fa-solid fa-circle-exclamation text-red-500 text-4xl mb-3"></i>
        <h2 class="text-lg font-semibold mb-2">{{ $title }}</h2>
        <p class="text-gray-600 mb-5 text-sm">{{ $message }}</p>

        <div class="flex justify-center space-x-4">
            <form method="POST" action="{{ $action }}">
                @csrf
                @if($method ?? false)
                    @method($method)
                @endif
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    {{ $confirmText ?? 'Confirmar' }}
                </button>
            </form>

            <button 
                @click="$root['{{ $trigger }}'] = false"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg"
            >
                {{ $cancelText ?? 'Cancelar' }}
            </button>
        </div>
    </div>
</div>
