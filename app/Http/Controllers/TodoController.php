<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\Category;

class TodoController extends Controller
{

    public function index()
    {
        $todos = Auth::user()->todos()->where('status', 'pending')->with('category')->latest()->paginate(5);
        $categories = Category::all();
        return view('todos.index', compact('todos', 'categories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'task' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'due_date' => 'nullable|date',
            'duration_minutes' => 'nullable|integer|min:1',
        ]);

        Auth::user()->todos()->create($validated);
        return redirect()->route('todos.index');
    }
    public function update(Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }
        $todo->update(['status' => 'completed']);
        return redirect()->route('todos.index');
    }

    public function fail(Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }
        if ($todo->status === 'pending') {
            $todo->update(['status' => 'failed']);
        }
        return response()->json(['status' => 'failed']);
    }

    public function destroy(Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403);
        }
        $todo->delete();
        return redirect()->route('todos.index');
    }

    public function history(Request $request)
    {

        $query = Auth::user()->todos()
            ->whereIn('status', ['completed', 'failed'])
            ->with('category');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->where('task', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('category', function ($catQuery) use ($searchTerm) {
                        $catQuery->where('name', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        $todos = $query->latest()->paginate(5);

        return view('todos.history', compact('todos'));
    }
}
