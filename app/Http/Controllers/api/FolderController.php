<?php

namespace App\Http\Controllers\Api;

use App\Models\Folder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
     // Create folder
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'parent_folder_id' => 'nullable|exists:folders,id',
        ]);
        $sizeInKB = ceil(strlen($request->content) / 1024); 

        $folder = Folder::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
            'parent_folder_id' => $request->parent_folder_id,
             'size_in_kb' => $sizeInKB,
        ]);

        return response()->json([
            'message' => 'Folder created successfully',
            'folder' => $folder,
        ], 201);
    }


    public function index()
{
    
    $totalItems = Folder::count();
    $totalSizeKB = Folder::sum('size_in_kb');
    $totalSizeGB = number_format($totalSizeKB / 1024 / 1024, 2); // KB → MB → GB

    return response()->json([
        'category' => 'Folder',
        'total_items' => $totalItems,
        'storage' => $totalSizeGB . ' GB'
    ]);
}

    // Delete folder
    public function destroy($id)
    {
        $folder = Folder::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$folder) {
            return response()->json(['message' => 'Folder not found'], 404);
        }

        $folder->delete();

        return response()->json(['message' => 'Folder deleted successfully']);
    }
}
