<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Helpers\Websocket;
use App\Helpers\Globals;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostFavorite;
use App\Models\User;
use App\Models\Notification as NotifModel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\NotificationRepositoryInterface;
use App\Notifications\PostNotification;

class NotificationEloquentRepository extends MainEloquentRepository implements NotificationRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var Notification $Model
     */
    public $Model = NotifModel::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    public function acquireAll()
    {
        $rtn = $this->arrayToCollection([]);

        try {
            $rtn = $this->NTCacquireAll();
        } catch (\Error $e) {
            \Log::error($e->getMessage());
        }

        return $rtn;

    }

    /**
     * acquire all model records
     * NTC (No Try Catch) method
     *
     * @return Collection
     */
    public function NTCacquireAll()
    {
        $rtn = $this->arrayToCollection([]);

        $user = auth()->guard('api')->user();

        if (!empty($this->Model)) {

            $rtn = $this->Model::where('data->user_id', $user->id)->limit(10)->get();

            return $rtn;
        }
    }

    /**
     * @param Collection $notifiable
     * @return void
     */
    public function notifyPostUsers($noti_medium)
    {
        $notifiable_ids  = [];

        $post = Post::find($noti_medium->post_id);

        $postLikesUserIds = $post
            ->likes()
            ->where('user_id', '!=', $noti_medium->user_id)
            ->pluck('user_id')
            ->toArray();

        $postFavoritesUserIds = $post
            ->favorites()
            ->where('user_id', '!=', $noti_medium->user_id)
            ->pluck('user_id')
            ->toArray();

        if ($post->user_id != $noti_medium->user_id) {
            $notifiable_ids = array_merge($postLikesUserIds, $postFavoritesUserIds, [ $post->user_id ]);
        } else {
            $notifiable_ids = array_merge($postLikesUserIds, $postFavoritesUserIds);
        }

        $notifiableUsers = User::whereIn('id', $notifiable_ids)
            ->get();

        if (basename(PostLike::class) == Globals::NOTIFIABLE_TYPE[$noti_medium->getTable()]) {
            $this->notifyUsersFromLike($notifiableUsers, $noti_medium);
        } else if (basename(PostFavorite::class) == Globals::NOTIFIABLE_TYPE[$noti_medium->getTable()]) {

            $notifiableUsers = $notifiableUsers->filter(function ($user) use ($post) {
                return $user->id == $post->user_id;
            });

            $this->notifyUsersFromFavorite($notifiableUsers, $noti_medium);
        } else {
            //
        }
    }

    /**
     * @param Collection $notifiableUsers
     * @param Collection $noti_medium
     * @return void
     */
    public function notifyUsersFromLike($notifiableUsers, $noti_medium)
    {
        $user = auth()->guard('api')->user();
        $pusher = Websocket::connect();

        foreach ($notifiableUsers as $to_user) {

            $post = Post::find($noti_medium->post_id);

            $notiOriginUser = User::find($post->user_id);

            $event = 'UserNotify';
            $totalLikes = $notifiableUsers->count();
            $message = '';

            if ($to_user->id == $post->user_id) {
                $message = __('messages.LikeNotiOriginUser', [
                    'name' => '<span class="font-semibold text-gray-900 dark:text-white">' . $user->name . '</span>' . ($totalLikes > 1? 'and ' . $totalLikes . ' others ' : ''),
                    'action' => 'liked',
                    'media_type' => 'post'
                ]);
            } else {
                $message = __('messages.LikeNotiOriginRelatedUsers', [
                    'name' => '<span class="font-semibold text-gray-900 dark:text-white">' . $user->name . '</span>' . ($totalLikes > 1? 'and ' . $totalLikes . ' others ' : ''),
                    'action' =>  'liked',
                    'notiOriginUser' => $notiOriginUser->name,
                    'media_type' => 'post'
                ]);
            }
            $data = [
                'user_id' => $to_user->id,
                'like_id' => $noti_medium->id,
                'link' => url("/posts/{$noti_medium->post_id}"),
                'profileImage' => '',
                'post' => $post,
                'message' => $message
            ];

            $hasNotification = $this->Model::where(
                [
                    [ 'notifiable_id', $user->id ],
                    [ 'data->user_id', $to_user->id ],
                    [ 'data->like_id', $noti_medium->id ]
                ]
            )->exists();

            // save and broadcast
            if (empty($hasNotification)) {
                Notification::send($user, new PostNotification($user, $data));

                $notification = $this->Model::where(
                    [
                        [ 'notifiable_id', $user->id ],
                        [ 'data->user_id', $to_user->id ]
                    ]
                )->latest()->first()->toArray();

                $pusher->broadcast(['usernotify.' . $to_user->id], $event, $notification);
            }
        }
    }

    /**
     * @param Collection $notifiableUsers
     * @param Collection $noti_medium
     * @return void
     */
    public function notifyUsersFromFavorite($notifiableUsers, $noti_medium)
    {
        $user = auth()->guard('api')->user();
        $pusher = Websocket::connect();

        foreach ($notifiableUsers as $to_user) {

            $post = Post::find($noti_medium->post_id);

            $event = 'UserNotify';
            $totalLikes = $notifiableUsers->count();
            $message = '';

            if ($to_user->id == $post->user_id) {
                $message = __('messages.FavoriteNotiOriginUser', [
                    'name' => '<span class="font-semibold text-gray-900">' . $user->name . '</span>' . ($totalLikes > 1 ? 'and ' . $totalLikes . ' others ' : ''),
                    'action' => 'favorite',
                    'media_type' => 'post'
                ]);
            }

            $data = [
                'user_id' => $to_user->id,
                'favorite_id' => $noti_medium->id,
                'link' => url("/posts/{$noti_medium->post_id}"),
                'profileImage' => '',
                'post' => $post,
                'message' => $message
            ];

            $hasNotification = $this->Model::where(
                [
                    [ 'notifiable_id', $user->id ],
                    [ 'data->user_id', $to_user->id ],
                    [ 'data->favorite_id', $noti_medium->id ]
                ]
            )->exists();

            // save and broadcast
            if (empty($hasNotification)) {
                Notification::send($user, new PostNotification($user, $data));

                $notification = $this->Model::where(
                    [
                        [ 'notifiable_id', $user->id ],
                        [ 'data->user_id', $to_user->id ]
                    ]
                )->latest()->first()->toArray();

                $pusher->broadcast(['usernotify.' . $to_user->id], $event, $notification);
            }
        }
    }

}
