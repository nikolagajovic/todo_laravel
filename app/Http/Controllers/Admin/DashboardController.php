<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function usersDashboard()
    {
        $users = User::withCount('todos')
        ->orderBy('name')
        ->paginate(10);

        return view('admin.users', compact('users'));
    }

     public function showUser(User $user, Request $request)
    {
        // Počinjemo upit za zadatke SAMO OVOG KORISNIKA
        $query = $user->todos()->with('category');

        // Filtriranje po statusu
        if ($request->has('status') && in_array($request->status, ['pending', 'completed', 'failed'])) {
            $query->where('status', $request->status);
        }

        // Pretraga po nazivu zadatka
        if ($request->has('search') && $request->search != '') {
            $query->where('task', 'LIKE', '%' . $request->search . '%');
        }

        // Dohvati zadatke sa paginacijom
        $todos = $query->latest()->paginate(10);
        
        return view('admin.user_details', compact('user', 'todos'));
    }
}
