<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Moji Zadaci') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                 
                    <form method="POST" action="{{ route('todos.store') }}" class="mb-6">
                        @csrf
                        <div class="flex items-center">
                            <input type="text" name="task" id="task" placeholder="Unesite novi zadatak..."
                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                            <x-primary-button class="ml-3">
                                {{ __('Dodaj') }}
                            </x-primary-button>
                        </div>
                         @error('task')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </form>

               
                    <div class="space-y-4">
                        @forelse ($todos as $todo)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <span class="{{ $todo->completed ? 'line-through text-gray-500' : '' }}">
                                    {{ $todo->task }}
                                </span>

                                <div class="flex items-center space-x-2">
                                 
                                    <form method="POST" action="{{ route('todos.update', $todo) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm {{ $todo->completed ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                            {{ $todo->completed ? 'Poništi' : 'Završi' }}
                                        </button>
                                    </form>

                           
                                    <form method="POST" action="{{ route('todos.destroy', $todo) }}" onsubmit="return confirm('Jeste li sigurni?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                                            Obriši
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p>Nemate nijedan zadatak.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>