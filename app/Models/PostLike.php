<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class PostLike extends MainModel
{
    /**
    * @var $table
    */
    protected $table = 'post_likes';

    /**
    * @var $primaryKey
    */
    protected $primaryKey = 'id';

    /**
    * @var $fillable
    */
    protected $fillable = [
        'user_id',
        'post_id',
        'deleted_at'
    ];

    /**
    * @var $cast
    */
    protected $casts = [
        //
    ];

    /**
    * @var $appends
    */
    protected $appends = [
        //
    ];

    /*======================================================================
     * CONSTANTS
     *======================================================================*/
    /*======================================================================
     * ACCESSORS
     *======================================================================*/
    /*======================================================================
     * MUTATORS
     *======================================================================*/
    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

     /**
     * This method return one post.
     *
     * @return collection
     */
    public function post()
    {
        return $this->BelongsTo(Post::class);
    }


    /*======================================================================
     * SCOPES
     *======================================================================*/
}
