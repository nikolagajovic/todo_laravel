<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Arhiva Zadataka') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <form method="GET" action="{{ route('todos.history') }}" class="mb-6">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Pretraži po nazivu ili kategoriji..."
                                class="w-full rounded-l-md border-gray-300 shadow-sm" value="{{ request('search') }}">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-r-md hover:bg-blue-700">
                                Pretraži
                            </button>
                        </div>
                    </form>

                    <div class="space-y-4">
                        @forelse ($todos as $todo)
                            <div
                                class="flex items-center justify-between p-4 rounded-lg
                                {{ $todo->status === 'completed' ? 'bg-green-100' : 'bg-red-100' }}">

                                <div>
                                    <span class="line-through text-gray-500">
                                        {{ $todo->task }}
                                    </span>
                                    @if ($todo->category)
                                        <span
                                            class="ml-2 text-xs text-white bg-gray-500 px-2 py-1 rounded-full align-middle">{{ $todo->category->name }}</span>
                                    @endif

                                    <div class="text-xs text-gray-500 mt-1">
                                        Kreirano: {{ $todo->created_at->diffForHumans() }}
                                    </div>

                                    <div class="text-sm mt-1">
                                        @if ($todo->status === 'completed')
                                            <span class="font-bold text-green-700">Završen</span>
                                        @else
                                            <span class="font-bold text-red-700">Ne završen</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <form method="POST" action="{{ route('todos.destroy', $todo) }}"
                                        onsubmit="return confirm('Jeste li sigurni da želite trajno obrisati ovaj zadatak?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-sm text-gray-600 hover:text-red-900 font-semibold">Obriši</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p>Nema arhiviranih zadataka.</p>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $todos->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
