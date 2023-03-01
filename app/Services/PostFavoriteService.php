<?php
namespace App\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PostFavorite;
use App\Interfaces\PostFavoriteRepositoryInterface;
use App\Interfaces\NotificationRepositoryInterface;
use Exception;

class PostFavoriteService
{
    public $repository;
    public $notiRepository;

    /**
     * @param CategoryService $service
     * @return void
     */
    public function __construct(
        PostFavoriteRepositoryInterface $repository,
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

        $favorite = $this->repository->add($data);

        if ($favorite) {

            try {
                \DB::beginTransaction();

                $this->notiRepository->notifyPostUsers($favorite);

                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::error('Exception: ' . $e->getMessage());
            }

            \Cache::pull('posts');
        }

        return $favorite;
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

        $favorite = $this->repository->adjust($id, $data);

        if ($favorite) {

            try {
                \DB::beginTransaction();

                $this->notiRepository->notifyPostUsers($favorite);

                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::error('Exception: ' . $e->getMessage());
            }

            \Cache::pull('posts');
        }

        return $favorite;
    }

    /**
     *
     * @return JSON $category
     */
    public function annul()
    {
        $id = request()->get('id');

        $favorite = $this->repository->annul($id);
        \Cache::pull('posts');

        return $favorite;
    }
}
?>

