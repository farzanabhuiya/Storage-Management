<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{                

              // all Favorite
    public function index(Request $request)
    {
        $favorites = Favorite::where('user_id', $request->user()->id)->get();

        return response()->json([
            'data' => $favorites
        ]);
    }


                //   add Favorite
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string', // example: note, pdf, image, folder, link
            'item_id' => 'required|integer'
        ]);

        $favorite = Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'type' => $request->type,
            'item_id' => $request->item_id,
        ]);

        return response()->json(['message' => 'Added to favorites', 'data' => $favorite]);
    }
          


    // deleted Favorite
    public function destroy(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'item_id' => 'required|integer'
        ]);

        Favorite::where([
            'user_id' => $request->user()->id,
            'type' => $request->type,
            'item_id' => $request->item_id,
        ])->delete();

        return response()->json(['message' => 'Removed from favorites']);
    }
}
