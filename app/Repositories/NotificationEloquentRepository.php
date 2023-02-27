<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Helpers\Websocket;
use App\Helpers\Globals;
use App\Models\Post;
use App\Models\User;
use App\Models\Notification as NotifModel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\NotificationRepositoryInterface;
use App\Notifications\PostLikeNotification;

class NotificationEloquentRepository extends MainEloquentRepository implements NotificationRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var Notification $Model
     */
    public $Model = Notification::class;

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
     * @param Collection $like
     * @return void
     */
    public function notifyUsers($like)
    {
        $notifiable_ids  = [];

        $user = auth()->guard('api')->user();
        $post = Post::find($like->post_id);

        $postLikesUserIds = $post
            ->likes()
            ->where('user_id', '!=', $like->user_id)
            ->pluck('user_id')
            ->toArray();

        $postFavoritesUserIds = $post
            ->favorites()
            ->where('user_id', '!=', $like->user_id)
            ->pluck('user_id')
            ->toArray();

        if ($post->user_id != $like->user_id) {
            $notifiable_ids = array_merge($postLikesUserIds, $postFavoritesUserIds, [ $post->user_id ]);
        } else {
            $notifiable_ids = array_merge($postLikesUserIds, $postFavoritesUserIds);
        }

        $userLikedList = User::whereIn('id', $notifiable_ids)->get();

        $pusher = Websocket::connect();

        foreach ($userLikedList as $to_user) {

            $post = Post::find($like->post_id);

            $notiOriginUser = User::find($post->user_id);

            $event = 'UserNotify';
            $message = '';

            if ($to_user->id == $post->user_id) {
                $message = __('messages.NotiOriginUser', [
                    'name' => '<span class="font-semibold text-gray-900 dark:text-white">' . $user->name . '</span>' . (count($postLikesUserIds) > 1? 'and others ' : ''),
                    'action' => Globals::ACTION["LIKED"],
                    'media_type' => Globals::MEDIA_TYPE['POST']
                ]);
            } else {
                $message = __('messages.NotiOriginRelatedUsers', [
                    'name' => '<span class="font-semibold text-gray-900 dark:text-white">' . $user->name . '</span>' . (count($postLikesUserIds) > 1? 'and others ' : ''),
                    'action' =>  Globals::ACTION["LIKED"],
                    'notiOriginUser' => $notiOriginUser->name,
                    'media_type' => Globals::MEDIA_TYPE['POST']
                ]);
            }
            $data = [
                'user_id' => $to_user->id,
                'like_id' => $like->id,
                'postLikesCount' => count($postLikesUserIds),
                'link' => url("/posts/{$like->post_id}"),
                'profileImage' => '',
                'post' => $post,
                'message' => $message
            ];

            $hasNotification = NotifModel::where(
                [
                    [ 'notifiable_id', $user->id ],
                    [ 'data->user_id', $to_user->id ]
                ]
            )->exists();

            if (empty($hasNotification)) {
                Notification::send($user, new PostLikeNotification($user, $data));

                $notification = NotifModel::where(
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
