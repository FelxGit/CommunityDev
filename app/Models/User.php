<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\PostFavorite;
use App\Models\Post;

class User extends \TCG\Voyager\Models\User
{
    use Notifiable, HasApiTokens;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id',
        'google_id',
        'email',
        'username',
        'password',
        'name',
        'nick_name',
        'birth_date',
        'gender',
        'zip_code',
        'address',
        'tel',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    const ADMIN_USER = 1;
    const NORMAL_USER = 2;

    const PASSWORD_DEFAULT = 'password';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at',
        'deleted_at'
    ];

    /**
     * This method return mulitple user relation to post.
     *
     * @return collection
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * This method return mulitple favorites relation to user.
     *
     * @return collection
     */
    public function favorites()
    {
        return $this->hasMany(PostFavorite::class);
    }


    public function receivesBroadcastNotificationsOn()
    {
        return 'usernotify';
    }
}
