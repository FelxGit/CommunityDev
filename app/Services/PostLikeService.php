<?php
namespace App\Services;

use App\Helpers\Globals;
use App\Helpers\Websocket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\Notification as NotifModel;
use App\Notifications\PostLikeNotification;
use App\Interfaces\PostLikeRepositoryInterface;
use Exception;

class PostLikeService
{
    public $repository;

    /**
     * @param CategoryService $service
     * @return void
     */
    public function __construct(PostLikeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * @return JSON $category
     */
    public function add()
    {
        $data = [
          'user_id' => auth()->user()->id,
          'post_id' => request()->get('post_id'),
        ];

        $like = $this->repository->add($data);

        if ($like) {

            try {
                \DB::beginTransaction();

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

                    $event = 'UserNotify';
                    $data = [
                        'user_id' => $to_user->id,
                        'like_id' => $like->id,
                        'postLikesCount' => count($postLikesUserIds),
                        'link' => url("/posts/{$like->post_id}"),
                        'profileImage' => '',
                        'post' => $post,
                        'message' => __('messages.Notification', [
                            'name' => '<span class="font-semibold text-gray-900 dark:text-white">' . $user->name . '</span>',
                            'action' => count($postLikesUserIds) > 1? 'and others ' : '' . Globals::ACTION["LIKED"],
                            'media_type' => Globals::MEDIA_TYPE['POST']
                        ])
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
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::error('Exception: ' . $e->getMessage());
            }

            \Cache::pull('posts');
        }

        return $like;
    }

    /**
     *
     * @return JSON $category
     */
    public function adjust()
    {
        $id = request()->get('id');
        $data = [
            'deleted_at' => null
        ];

        $like = $this->repository->adjust($id, $data);

        if ($like) {

            try {
                \DB::beginTransaction();

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

                    $event = 'UserNotify';
                    $data = [
                        'user_id' => $to_user->id,
                        'like_id' => $like->id,
                        'postLikesCount' => count($postLikesUserIds),
                        'link' => url("/posts/{$like->post_id}"),
                        'profileImage' => '',
                        'post' => $post,
                        'message' => __('messages.Notification', [
                            'name' => '<span class="font-semibold text-gray-900 dark:text-white">' . $user->name . '</span>',
                            'action' => count($postLikesUserIds) > 1? 'and others ' : '' . Globals::ACTION["LIKED"],
                            'media_type' => Globals::MEDIA_TYPE['POST']
                        ])
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
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::error('Exception: ' . $e->getMessage());
            }
        }

        \Cache::pull('posts');

        return $like;
    }

    /**
     *
     * @return JSON $category
     */
    public function annul()
    {
        $id = request()->get('id');

        $like = $this->repository->annul($id);
        \Cache::pull('posts');
        return $like;
    }
}
?>

