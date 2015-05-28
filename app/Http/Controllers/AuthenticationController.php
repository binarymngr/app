<?php namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

final class AuthenticationController extends Controller
{
    /**
     * Tries to login the user.
     *
     * @param \Illuminate\Http\Request $rqst the incoming request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $rqst)
    {
        $remember = $rqst->input('remember') === 'on';
        if (Auth::attempt($rqst->only('email', 'password'), $remember)) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('login')
                         ->with('login_failed', true)
                         ->with('email', $rqst->input('email'));
    }

    /**
     * Logs the current user out.
     *
     * @param \Illuminate\Http\Request $rqst the incoming request
     *
     * @return \Illuminate\Http\Response the login form
     */
    public function logout(Request $rqst)
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->route('login')
                             ->with('logged_out', true);
        }
        return redirect()->route('login');
    }

    /**
     * Shows the login form.
     *
     * @return \Illuminate\Http\Response the login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }
}
