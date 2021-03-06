<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Over authenticated function from AuthenticatesUsers trait
     * Check whether user is admin after user's credentials are correct
     *
     * @param Request $request
     * @param [type] $user
     * @return void
     */
    protected function authenticated(Request $request, $user)
    {
        if (! $user->isAdmin()) {
            // if not admin user then, kick out and return forbidden error
            Auth::logout();

            return response()->json([
                'error' => 'Sorry only admin can access!'
            ], 403);
        }

        return new UserResource($user);
    }
}
