<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{

    // CREATE NOTE
  public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'content' => 'required|string',
    ]);

    $sizeInKB = round(strlen($request->content) / 1024);
    // dd($sizeInKB);

    $note = Note::create([
        'user_id' =>Auth::id(),
        'title' => $request->title,
        'content' => $request->content,
        'size_in_kb' => $sizeInKB,
    ]);

    return response()->json([
        'message' => 'Note created successfully',
        'note' => $note,
    ], 201);
}




public function index()
{
    $totalItems = Note::count();
    $totalSizeKB = Note::sum('size_in_kb');
    $totalSizeGB = number_format($totalSizeKB / 1024 / 1024, 8); // KB → MB → GB

    return response()->json([
        'category' => 'Note',
        'total_items' => $totalItems,
        'storage' => $totalSizeGB . ' GB'
    ]);
}


 


            // ✅ note alll
    public function all()
    {
        $notes = Note::latest()->get();
        return response()->json(['notes' => $notes]);
    }

    // ✅ Update Note

   public function update(Request $request, $id)
{
    $note = Note::find($id);

    if (!$note) {
        return response()->json(['message' => 'Note not found'], 404);
    }

    if ($note->user_id !== Auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $updatedContent = $request->content ?? $note->content;

    $note->update([
        'title' => $request->title ?? $note->title,
        'content' => $updatedContent,
        'size_in_kb' => ceil(strlen($updatedContent) / 1024),
    ]);

    return response()->json([
        'message' => 'Note updated successfully',
        'note' => $note,
    ]);
}



    // ✅ Delete Note
    public function destroy($id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        if ($note->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json(['message' => 'Note deleted successfully']);
    }
}
