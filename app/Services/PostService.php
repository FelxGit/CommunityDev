<?php
namespace App\Services;

use App\Helpers\Websocket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use App\Models\Post;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\PostRepositoryInterface;
use App\Interfaces\TagRepositoryInterface;
use App\Interfaces\PostTagRepositoryInterface;
use App\Events\UserNotify;
use Exception;

class PostService
{
    /**
     * @param PostService $service
     * @return void
     */
    public function __construct(
        PostRepositoryInterface $repository,
        UserRepositoryInterface $userRepository,
        TagRepositoryInterface $tagRepository,
        PostTagRepositoryInterface $postTagRepository
    ) {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->tagRepository = $tagRepository;
        $this->postTagRepository = $postTagRepository;
    }

    public function acquire($id)
    {
        $rtn = [];

        $rtn = $this->repository->acquire($id);

        return $rtn;
    }

    /**
     *
     * @return JSON $tags
     */
    public function all()
    {
        $rtn = [];

        $data = [
            'categoryTitle' => request()->get('categoryTitle'),
            'post_display_type' => request()->get('post_display_type')
        ];

        $expiry = 604800; // 1 week
        // $rtn = \Cache::remember('posts', $expiry, function () use ($data) {

            if (!empty(request()->get('favorites'))) {
                $favorites = auth()->guard('api')->user()->favorites()->whereNull('deleted_at')->get();
                $postIds = [];

                foreach ($favorites as $favorite) {
                    array_push($postIds, $favorite->post_id);
                }

                $posts = $this->repository->acquireByUserFavoritePosts($postIds);
            } else {
                $posts = $this->repository->acquireAllByDisplayTypeAndCategory($data);
            }

            return $posts;
        // });

        return $rtn;
    }

    /**
     *
     * @return JSON $Post
     */
    public function add()
    {
        $post = false;
        $user = \Auth::guard('api')->user();

        $data = [
            'user_id' => $user->id,
            'category_id' => request()->get('category_id'),
            'title' => request()->get('title'),
            'plain_description' => strip_tags(request()->get('plain_description')),
            'html_description' => request()->get('plain_description'),
            'status' => Post::STATUS_PUBLISHED
        ];

        $tagData = array_map(function($tag) {
            return [ 'title' => $tag['title']];
        }, request()->get('tags'));
        $postTagData = [];

        \DB::beginTransaction();
        try {
            $post = $this->repository->add($data);
            $tags = $this->tagRepository->addBulk($tagData);

            foreach ($tags as $tag) {
                $postTagData[] = [
                    'post_id' => $post->id,
                    'tag_id' => $tag->id
                ];
            }

            $this->postTagRepository->addBulk($postTagData);

            \Cache::pull('tagsByPopularity');
            \Cache::pull('tags');
            \Cache::pull('posts');
            \DB::commit();
        } catch (\Exception $e) {
            \Log::error('Exception: ' . $e->getMessage());
            \DB::rollback();
        }

        return $post;
    }
}
?>

