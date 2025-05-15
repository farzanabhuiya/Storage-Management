<?php

namespace App\Http\Controllers\Api;

use App\Models\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
      public function lock(Request $request)
    {
        $request->validate([
        'file_id' => 'required|exists:files,id',
            'lock_pin' => 'required|digits:4',
        ]);

        $file = File::where('id', $request->file_id)
                    ->where('user_id', Auth::id())
                    ->first();

        if (!$file) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $file->is_locked = true;
        $file->lock_pin = $request->lock_pin;
        $file->save();

        return response()->json(['message' => 'File lock']);
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'file_id' => 'required|exists:files,id',
            'lock_pin' => 'required|digits:4',
        ]);

        $file = File::where('id', $request->file_id)
                    ->where('user_id', Auth::id())
                    ->first();

        if (!$file) {
            return response()->json(['message' => 'File not found'], 404);
        }

        if (!$file->is_locked || $file->lock_pin !== $request->lock_pin) {
            return response()->json(['message' => 'file unlock '], 403);
        }

        $file->is_locked = false;
        $file->lock_pin = null;
        $file->save();

        return response()->json(['message' => 'File Lock']);
    }
}
