<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store($id)
    {
        $userId = Auth::id();

        $likes = Like::firstOrCreate([
            'user_id' => $userId,
            'item_id' => $id,
        ]);

        return redirect()->route('items.show', $id);
    }

    public function destroy($id)
    {
        $like = Like::where('user_id', Auth::id())
        ->where('item_id', $id)
        ->first();

        $like->delete();
        return redirect()->route('items.show', $id);
    }
}
