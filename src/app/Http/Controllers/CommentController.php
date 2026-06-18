<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request,$id)
    {
        $validated = $request->validated();

        $userId = Auth::id();

        $comment = Comment::create([
            'comment' => $validated['comment'],
            'user_id' => $userId,
            'item_id' => $id,
        ]);

        return redirect()->route('items.show', $id);
    }
}
