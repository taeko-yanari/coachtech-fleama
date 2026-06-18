<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
  public function toResponse($request)
  {
    $user = Auth::user();

    $isFirstLogin = $user->last_login_at === null;

    $user->last_login_at = now();
    $user->save();

    if ($isFirstLogin) {
      return redirect()->route('mypage.edit');
      } else {
        return redirect()->route('items.index',['tab' => 'mylist']);
    }
  }
}