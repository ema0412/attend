<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function create()
    {
        return view('auth.admin-login');
    }

    public function store(LoginRequest $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'ログイン情報が登録されていません']);
        }

        if (! $request->user()->is_admin) {
            Auth::logout();

            return back()->withErrors(['email' => '管理者権限がありません']);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.attendance.list');
    }
}
