<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;

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
     * @param Array $attributes
     * @return Bool/Model
     */
    public function add(array $attributes)
    {

    }

}
