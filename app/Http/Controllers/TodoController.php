<?php
namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        // get todos for the currently authenticated user
        $todos = Todo::where('user_id', Auth::id())->get();
        return response()->json($todos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required|string|max:255',
        ]);

        $todo = Todo::create([
            'task' => $request->task,
            'completed' => false,
            'user_id' => Auth::id(),
        ]);

        return response()->json($todo, 201);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $todo->update([
            'completed' => $request->completed,
        ]);

        return response()->json($todo);
    }

    public function destroy($id)
    {
        $todo = Todo::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $todo->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
