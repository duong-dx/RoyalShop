<?php

namespace App\Http\Controllers\CustomerAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/customer/test';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('customer.guest')->except('logout');
    }
    public function showLoginForm()
    {
        return view('customer_auth.login');
    }
   
    public function logout()
    {
        $this->guard()->logout();

        // $request->session()->invalidate();

        return redirect('/customer/login');
    }

    protected function guard()
    {
        return Auth::guard('customer');
    }
     protected function authenticated()
    {
        return redirect('/customer/test');
    }
}
