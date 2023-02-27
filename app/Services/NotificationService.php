<?php
namespace App\Services;

use App\Interfaces\NotificationRepositoryInterface;

class NotificationService
{
    public $repository;

    /**
     * @param NotificationRepositoryInterface $service
     * @return void
     */
    public function __construct(NotificationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * @return Collection $rtn
     */
    public function all()
    {
        $rtn = [];
        $rtn = $this->repository->acquireAll();
        return $rtn;
    }
}
?>

