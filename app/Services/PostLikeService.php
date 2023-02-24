<?php
namespace App\Services;

use App\Helpers\Globals;
use App\Helpers\Websocket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Models\Post;
use App\Models\Notification as NotifModel;
use App\Notifications\PostLikeNotification;
use App\Interfaces\PostLikeRepositoryInterface;
use App\Interfaces\NotificationRepositoryInterface;

class PostLikeService
{
    public $repository;
    public $notiRepository;

    /**
     * @param PostLikeRepositoryInterface $service
     * @param NotificationRepositoryInterface $service
     * @return void
     */
    public function __construct(
        PostLikeRepositoryInterface $repository,
        NotificationRepositoryInterface $notiRepository
    ) {
        $this->repository = $repository;
        $this->notiRepository = $notiRepository;
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

                $this->notiRepository->notifyUsers($like);

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

                $this->notiRepository->notifyUsers($like);

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

