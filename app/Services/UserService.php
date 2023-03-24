<?php
namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\ResetPasswordLink;
use App\Interfaces\UserRepositoryInterface;
use Exception;
use Socialite;

class UserService
{
    public $repository;

    /**
     * @param UserService $service
     * @return void
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * @param Int $id
     * @return JSON $user
     */
    public function login()
    {
        $rtn = [];

        $data = request()->only('email', 'password');
        // $user = $this->service->acquireByAttributes($data);

        if (\Auth::attempt($data)) {
            $user = \Auth::user();

            $rtn = [
                'token' => $user->createToken('chronoknowledge')->accessToken,
                'user' => $user
            ];
        } else {
            throw new \Exception("The given data was invalid.");
        }

        return response()->json($rtn, 200);
    }

    /**
     * Login google user
     *
     * @return Array $data
     */
    public function loginGoogle()
    {
        $data = [];

        try {
            \DB::beginTransaction();

            $user = null;
            $g_user = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $g_user->email)->first();
            $existEmailButNotLinked = $user && empty($user->google_id);
            $existEmailAndLinked = $user && $user->google_id;

            if ($existEmailButNotLinked) {
                $adjustAttributes = [ 'google_id' => $g_user->id ];
                $user = $this->repository->adjust($user->id, $adjustAttributes);
            } else if ($existEmailAndLinked) {
                // do nothing
            } else {
                // non existent
                $attributes = [
                    'google_id' => $g_user->id,
                    'role_id' => User::NORMAL_USER,
                    'email' => $g_user->email,
                    'username' => $g_user->name,
                    'password' => \Hash::make(User::PASSWORD_DEFAULT),
                    'name' => $g_user->name,
                    'zip_code' => 0000,
                    'address' => '--',
                    'tel' => '00000000000',
                    'email_verified_at' => Carbon::now()
                ];

                $user = $this->repository->add($attributes);
            }

            \Auth::login($user);

            if ($user = \Auth::user()) {
                $data = [
                    'token' => $user->createToken('chronoknowledge')->accessToken,
                    'user' => $user
                ];
            }

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollback();
            \Log::error($e->getMessage());
        }

        return $data;
    }

    /**
     *
     * @param Int $id
     * @return JSON $user
     */
    public function registerUser()
    {
        $user = false;

        $data = [
            'role_id' => User::NORMAL_USER,
            'email' => request()->get('email'),
            'username' => request()->get('username'),
            'password' => \Hash::make(request()->get('password')),
            'name' => request()->get('name'),
            'nick_name' => request()->get('nick_name'),
            'birth_date' => request()->get('birth_date'),
            'gender' => request()->get('gender'),
            'zip_code' => request()->get('zip_code'),
            'address' => request()->get('address'),
            'tel' => request()->get('tel'),
            'email_verified_at' => request()->get('email_verified_at'),
            'deleted_at' => request()->get('deleted_at')
        ];

        \DB::beginTransaction();
        try {
            $user = $this->repository->add($data);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
        }

        return $user;
    }

    /**
     *
     * @return JSON $reset_token
     */
    public function sendResetLinkEmail()
    {
        $reset_token = $this->randStr(10);
        $data = [
            'url' => env('APP_URL') . '?reset_token=' . $reset_token,
            'reset_token' => $reset_token,
            'email' => request()->get('email')
        ];

        $user = User::where('email', $data['email'])->get();

        try {
            $send = \Notification::send($user, new ResetPasswordLink($data));
            return $data;
        } catch (\Exception $e) {
            \Log::error('Exception: ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

    /**
     *
     * @return JSON $randomString
     */
    public function randStr($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    /**
     *
     * @return JSON $rtn
     */
    public function reset()
    {
        $rtn = [];

        $adjustAttributes = [
            'password' => \Hash::make(request()->get('new_password'))
        ];
        $whereAttributes = [
            'email' => request()->get('email')
        ];

        try {
            $rtn = $this->repository->adjustByAttributes($whereAttributes, $adjustAttributes);
            return $rtn;
        } catch (\Exception $e) {
            \Log::error('Exception: ' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
?>

