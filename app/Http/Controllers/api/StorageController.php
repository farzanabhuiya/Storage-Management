<?php

namespace App\Http\Controllers\Api;

use App\Models\Note;
use App\Models\Image;
use App\Models\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StorageController extends Controller
{
    public function getStorageSummary(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }


        $notesSize = Note::where('user_id', $user->id)->sum('size_in_kb');
        $imagesSize = Image::where('user_id', $user->id)->sum('size_in_kb');
        $pdfsSize = Pdf::where('user_id', $user->id)->sum('size_in_kb');


        $totalUsedStorageInKb = $notesSize + $imagesSize + $pdfsSize;
        // Total storage 15 GB = 15 * 1024 MB = 15 * 1024 * 1024 KB
        $totalStorageInKb = 15 * 1024 * 1024;
        $availableStorageInKb = $totalStorageInKb - $totalUsedStorageInKb;

       return response()->json([
       'totalStorage' => number_format($totalStorageInKb / 1024, 2) . ' MB',
       'usedStorage' => number_format($totalUsedStorageInKb / 1024, 2) . ' MB',
       'availableStorage' => number_format($availableStorageInKb / 1024, 2) . ' MB',
    ]);

    }
}
