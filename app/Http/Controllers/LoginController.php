<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @return Application|Factory|View|RedirectResponse
     */
    public function index() {
        if (Auth::user()) return $this->redirectTop();
        return view('login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->redirectTop();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * ログアウト
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->intended('/login');
    }

    /**
     * トップページにリダイレクトします。(権限毎)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectTop()
    {
        switch(Auth::user()->authority)
        {
            case 1:
                return redirect()->intended('/');
            case 2:
                return redirect()->intended('/');
            case 3:
                return redirect('appraisers');
            case 4:
                return redirect('adcodes');
            default:
                return redirect()->intended('/');
        }
    }
}
