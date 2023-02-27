<?php

namespace App\Http\Controllers\Api;

use App\Services\NotificationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public $service;

    /**
     * @param Notification $service
     * @return void
     */
    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    /**
     * @return $rtn
     */
    public function index()
    {
        $rtn = [];
        $rtn = $this->service->all();
        return $rtn;
    }
}
