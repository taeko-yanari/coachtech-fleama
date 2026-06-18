<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'sell');

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $profile = Auth::user();

        $query = Item::with(['images', 'purchase'])
            ->orderBy('created_at', 'desc');
        
        $query->where('user_id', auth()->id());
        

        $sellItems = $query->get();
        $purchaseItems = collect();
       
        $userId = auth()->id();
        $purchaseItems = Item::with(['images', 'purchase'])
            ->whereHas('purchase', function ($q) use ($userId) {
            $q->where('user_id', $userId);
            })
            ->get();
        return view('mypage.index', compact('profile', 'activeTab', 'sellItems', 'purchaseItems'));
    }

    public function search(Request $request)
    {
        $activeTab = $request->input('tab', 'sell');


        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $profile = Auth::user();

        $query = Item::with(['images', 'purchase'])
            ->orderBy('created_at', 'desc');

            $query->where('user_id', auth()->id());

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $sellItems = $query->get();

        $purchaseItems = collect();
        
        $userId = auth()->id();

        $purchaseQuery = Item::with(['images', 'purchase'])
            ->whereHas('purchase', function ($q) use ($userId) {
                $q->where('user_id', $userId);
                });

            
        if ($request->keyword) {
            $purchaseQuery->where('name', 'like', '%' . $request->keyword . '%');
            }
                
            $purchaseItems = $purchaseQuery->get();

        return view('mypage.index', compact('profile', 'sellItems', 'purchaseItems', 'activeTab'));
    }

    public function edit()
    {
        $profile = Auth::user();
        return view('mypage.edit', compact('profile'));
    }

    public function update(ProfileRequest $request)
    {
        if ($request->hasFile('profile_image_path') && $request->file('profile_image_path')->isValid()) {
            if (session('temp_image_path')) {
                Storage::disk('public')->delete(session('temp_image_path'));
            }
            $tempPath = $request->file('profile_image_path')->store('temp', 'public');
            session(['temp_image_path' => $tempPath]);
        }
        $validated = $request->validated();
        $profile = Auth::user();

        $profile->name = $validated['name'];
        $profile->postal_code = $validated['postal_code'];
        $profile->address = $validated['address'];
        $profile->building = $validated['building'];


        if (session('temp_image_path')) {
                $tempPath = session('temp_image_path');
                $finalPath = 'profile/' . basename($tempPath);
                Storage::disk('public')->move($tempPath, $finalPath);
                session()->forget(['temp_image_path']);


            if ($profile->profile_image_path) {
                Storage::disk('public')->delete($profile->profile_image_path);
            }
            $profile->profile_image_path = $finalPath;
        }

        $profile->save();

        return redirect()->route('items.index',['tab' => 'mylist']);
    }

}
