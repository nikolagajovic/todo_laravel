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

                    <!-- Forma za dodavanje novog zadatka -->
                    <form method="POST" action="{{ route('todos.store') }}" class="mb-6 space-y-4">
                        @csrf
                        <div>
                            <input type="text" name="task" placeholder="Unesite novi zadatak..."
                                class="w-full rounded-md shadow-sm..." required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Kategorija -->
                            <select name="category_id" class="rounded-md shadow-sm...">
                                <option value="">-- Bez kategorije --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                            <input type="datetime-local" name="due_date" class="rounded-md shadow-sm...">

                        </div>
                        <x-primary-button>{{ __('Dodaj Zadatak') }}</x-primary-button>
                    </form>

                    <!-- Lista zadataka -->
                    <div class="space-y-4">
                        @forelse ($todos as $todo)
                            <div
                                class="flex items-center justify-between p-4 rounded-lg
                                {{ $todo->status === 'completed' ? 'bg-green-100' : '' }}
                                {{ $todo->status === 'failed' ? 'bg-red-100' : '' }}
                                {{ $todo->status === 'pending' ? 'bg-gray-50' : '' }}">

                                <div>
                                    <span
                                        class="{{ $todo->status !== 'pending' ? 'line-through text-gray-500' : 'text-gray-800' }}">
                                        {{ $todo->task }}
                                    </span>
                                    @if ($todo->category)
                                        <span
                                            class="ml-2 text-xs text-white bg-blue-500 px-2 py-1 rounded-full align-middle">{{ $todo->category->name }}</span>
                                    @endif

                                    <div class="text-sm text-gray-600 mt-1">
                                        @if ($todo->status === 'pending' && $todo->due_date)
                                            <span class="font-bold text-indigo-700 timer"
                                                data-due-date="{{ $todo->due_date->toIso8601String() }}"
                                                data-task-id="{{ $todo->id }}">
                                                Preostalo: Učitavanje...
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Kreirano: {{ $todo->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 flex-shrink-0">
                                @if ($todo->status === 'pending')
                                    <form method="POST" action="{{ route('todos.update', $todo) }}"
                                        class="mark-done-form">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="text-sm text-green-600 hover:text-green-900 font-semibold">Urađeno</button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('todos.destroy', $todo) }}"
                                    onsubmit="return confirm('Jeste li sigurni?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-sm text-red-600 hover:text-red-900 font-semibold">Obriši</button>
                                </form>
                            </div>
                    </div>
                @empty
                    <p>Nemate nijedan zadatak. Dodajte jedan!</p>
                    @endforelse
                </div>
                <div class="mt-6">
                    {{ $todos->links() }}
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const timers = document.querySelectorAll('.timer');

                const failTask = (taskId, timerElement) => {
                    const url = `/todos/${taskId}/fail`;
                    fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                    }).then(response => {
                        if (response.ok) {
                            // Sakrij dugme "Urađeno"
                            const container = timerElement.closest('.flex');
                            const markDoneForm = container.querySelector('.mark-done-form');
                            if (markDoneForm) {
                                markDoneForm.style.display = 'none';
                            }
                            // Promeni boju pozadine
                            container.classList.remove('bg-gray-50');
                            container.classList.add('bg-red-100');
                            // Promeni tekst statusa
                            timerElement.parentElement.innerHTML =
                                '<span class="font-bold text-red-700">Neurađen (vreme isteklo)</span>';
                        }
                    });
                };

                const formatRemainingTime = (remainingTime) => {
                    if (remainingTime <= 0) return "Vreme je isteklo!";

                    const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

                    let output = "Preostalo: ";
                    if (days > 0) output += `${days}d `;
                    if (hours > 0) output += `${hours}h `;
                    if (minutes > 0) output += `${minutes}m `;
                    if (days === 0) output += `${seconds}s`; 

                    return output.trim();
                };

                timers.forEach(timerEl => {
                    const dueDate = new Date(timerEl.dataset.dueDate);
                    const taskId = timerEl.dataset.taskId;

                    const intervalId = setInterval(() => {
                        const now = new Date();
                        const remainingTime = dueDate - now;

                        if (remainingTime <= 0) {
                            clearInterval(intervalId);
                            failTask(taskId, timerEl);
                        } else {
                            timerEl.textContent = formatRemainingTime(remainingTime);
                        }
                    }, 1000);
                });
            });
        </script>
    @endpush
</x-app-layout>
