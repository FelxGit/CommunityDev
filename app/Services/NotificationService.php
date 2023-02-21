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
use App\Interfaces\NotificationRepositoryInterface;
use Exception;

class NotificationService
{
    public $repository;

    /**
     * @param CategoryService $service
     * @return void
     */
    public function __construct(NotificationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * @return JSON $category
     */
    public function all()
    {
        $data = [
          'user' => auth()->user()->id,
        ];

        $notif = $this->repository->acquireAll();
        return $notif;
    }
}
?>

