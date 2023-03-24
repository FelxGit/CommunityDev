<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * @param UserService $service
     * @return void
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param User $user
     * @return JSON $user
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * @param Reqeust $request
     * @return JSON $rtn
     */
    public function login()
    {
        $rtn = null;
        $rtn = $this->service->login();
        return $rtn;
        // throw new Exception("Invalid credentials.");
    }

    /**
     * @param Request $request
     * @return JSON $rtn
     */
    public function register(UserRequest $request)
    {
        $rtn = null;
        $rtn = $this->service->registerUser();
        return $rtn;
    }

    /**
     * @param Request $request
     * @return JSON $rtn
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users'
        ]);

        $rtn = null;
        $rtn = $this->service->sendResetLinkEmail();
        return $rtn;
    }

    /**
     * @param Request $request
     * @return JSON $rtn
     */
    public function reset(Request $request)
    {
        $validated = $request->validate([
            'new_password' => 'required',
            'password_confirmation' => 'required|same:new_password'
        ]);

        $rtn = null;
        $rtn = $this->service->reset();
        return response()->json($rtn, 200);
    }
}
