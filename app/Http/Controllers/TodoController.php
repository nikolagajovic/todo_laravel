<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    
    public function index() {
        $todos = Auth::user()->todos()->orderBy('created_at', 'desc')->get();
        return view('todos.index', ['todos' => $todos]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        Auth::user()->todos()->create([
            'task' => $request->task,
        ]);

        return redirect()->route('todos.index')->with('success', 'Zadatak uspješno dodan.');
    }

      public function update(Request $request, Todo $todo)
    {
     
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }

        $todo->update([
            'completed' => !$todo->completed,
        ]);

        return redirect()->route('todos.index');
    }

    public function destroy(Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }
        
        $todo->delete();

        return redirect()->route('todos.index')->with('success', 'Zadatak uspješno obrisan.');
    }
}
