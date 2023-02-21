<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $models = array(
            'User',
            'Admin',
            'Post',
            'Category',
            'Tag',
            'PostTag',
            'PostLike',
            'PostFavorite',
            'PostComment',
            'Notification'
        );

        foreach ($models as $index => $model) {
            $this->app->bind("App\Interfaces\\{$model}RepositoryInterface", "App\Repositories\\{$model}EloquentRepository");
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
