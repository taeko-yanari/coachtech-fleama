<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'recommend');

        $query = Item::with(['images', 'likes'])
            ->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        
        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }

        $recommendItems = $query->get();

        $mylistItems = collect();
        if (auth()->check()) {
            $userId = auth()->id();
            $mylistItems = Item::with(['images', 'likes'])
                ->orderBy('created_at', 'desc')->orderBy('id', 'desc')
                ->whereHas('likes', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
            })
            ->get();
        }

        return view('items.index', compact('recommendItems', 'mylistItems', 'activeTab'));
    }

    public function search(Request $request)
    {
        $activeTab = $request->input('tab', 'recommend');

        $query = Item::with(['images', 'likes'])
            ->orderBy('created_at', 'desc')->orderBy('id', 'desc');

        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $recommendItems = $query->get();

        $mylistItems = collect();
        
        if (auth()->check()) {
            $userId = auth()->id();

            $mylistQuery = Item::with(['images', 'likes'])
                ->orderBy('created_at', 'desc')->orderBy('id', 'desc')
                ->whereHas('likes', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });

            
            if ($request->keyword) {
                $mylistQuery->where('name', 'like', '%' . $request->keyword . '%');
                }
                
                $mylistItems = $mylistQuery->get();

        }

        return view('items.index', compact('recommendItems', 'mylistItems', 'activeTab'));
    }

    public function show($id)
    {
        $item = Item::with('images','categories','comments.user')->findOrFail($id);
        return view('items.show', compact('item'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'selling';

        $item = Item::create(collect($validated)->except('image', 'category_ids')->toArray());
        $item->categories()->sync($validated['category_ids']);      

        if (session('temp_image_path')) {
            $tempPath = session('temp_image_path');
            $finalPath = 'items/' . uniqid() . '_' . basename($tempPath);
            Storage::disk('public')->move($tempPath, $finalPath);
            session()->forget('temp_image_path');
            $item->images()->create(['image_path' => $finalPath]);
            }
        
        return redirect()->route('mypage.index');
    }

    public function uploadTemp(Request $request)
    {        
        if (session('temp_image_path')) {
            Storage::disk('public')->delete(session('temp_image_path'));
        }

        if ($request->hasFile('image')) {
            $request->validate([
                'image' =>  ['mimes:jpeg,png'],
            ]);

            $path = $request->file('image')->store('temp', 'public');
            session(['temp_image_path' => $path]);

            return response()->json(['success' => true]);
        }

    return response()->json(['success' => false]);

    }

    public function removeTemp()
    {
        if (session('temp_image_path')) {
            Storage::disk('public')->delete(session('temp_image_path'));
        session()->forget('temp_image_path');
    }
    return response()->json(['success' => true]);
    }
    
}
