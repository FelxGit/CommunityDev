<?php

namespace App\Http\Controllers\Api;

// use Illuminate\Http\Request;
// use Laravel\Socialite\Facades\Socialite;
// use Exception;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Socialite;
use Exception;

class GoogleController extends Controller
{
    public $userService;

    /**
     * @param UserService $userService
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        $data = $this->userService->loginGoogle();
        return redirect()->route('landing-page', $data);
    }
}