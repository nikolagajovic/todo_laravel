<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalji za korisnika: ') }} <span class="font-bold">{{ $user->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Lista svih zadataka</h3>

                    <!-- Forma za filter i pretragu -->
                    <form method="GET" action="{{ route('admin.users.show', $user) }}" class="mb-6 bg-gray-50 p-4 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Pretraži zadatak</label>
                                <input type="text" name="search" id="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ request('search') }}">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Filter po statusu</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Svi statusi</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Na čekanju</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Završeni</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Neuspešni</option>
                                </select>
                            </div>
                            <div class="self-end">
                                <button type="submit" class="w-full justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    Primeni filtere
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Lista zadataka -->
                    <div class="space-y-4">
                        @forelse ($todos as $todo)
                            <div class="p-4 rounded-lg border
                                {{ $todo->status === 'completed' ? 'bg-green-50 border-green-200' : '' }}
                                {{ $todo->status === 'failed' ? 'bg-red-50 border-red-200' : '' }}
                                {{ $todo->status === 'pending' ? 'bg-gray-50 border-gray-200' : '' }}">
                                
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $todo->task }}</p>
                                        @if($todo->category)
                                            <span class="text-xs text-white bg-blue-500 px-2 py-1 rounded-full align-middle">{{ $todo->category->name }}</span>
                                        @endif
                                    </div>
                                    <div class="text-sm font-bold text-right flex-shrink-0 ml-4
                                        {{ $todo->status === 'completed' ? 'text-green-700' : '' }}
                                        {{ $todo->status === 'failed' ? 'text-red-700' : '' }}
                                        {{ $todo->status === 'pending' ? 'text-yellow-700' : '' }}">
                                        {{ ucfirst($todo->status) }}
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mt-2 border-t pt-2">
                                    <span>Kreirano: {{ $todo->created_at->format('d.m.Y H:i') }}</span>
                                    @if($todo->due_date)
                                        <span class="mx-2">|</span>
                                        <span class="font-semibold">Rok: {{ $todo->due_date->format('d.m.Y H:i') }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">Ovaj korisnik nema zadataka koji odgovaraju filterima.</p>
                        @endforelse
                    </div>

                    <!-- Paginacija -->
                    <div class="mt-6">
                        {{ $todos->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>