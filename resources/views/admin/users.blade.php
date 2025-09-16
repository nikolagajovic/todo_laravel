<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard - Korisnici') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Ime Korisnika</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Ukupno Taskova
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach ($users as $user)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="text-left py-3 px-4">
                                      
                                            <a href="{{ route('admin.users.show', $user) }}"
                                                class="text-blue-600 hover:underline font-semibold">
                                                {{ $user->name }}
                                            </a>
                                        </td>
                                        <td class="text-left py-3 px-4">{{ $user->email }}</td>
                                        <td class="text-center py-3 px-4">{{ $user->todos_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
