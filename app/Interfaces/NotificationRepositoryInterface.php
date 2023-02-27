<?php

namespace App\Interfaces;

interface NotificationRepositoryInterface
{
    /**
     * @return Model
     */
    public function acquireAll();

    /**
     * @param Null/Int $id
     * @return Model
     */
    public function acquire($id);

    /**
     * @param Array $attributes
     * @return Bool/Model
     */
    public function add(array $attributes);

    /**
     * @param Int $id
     * @param Array $attributes
     * @return Bool/Model
     */
    public function adjust(int $id, array $attributes);

    /**
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id);
}
