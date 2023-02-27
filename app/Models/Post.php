<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;
use App\Models\PostLike;

class Post extends MainModel
{
    use SoftDeletes;

    /**
    * @var $table
    */
    protected $table = 'posts';

    /**
    * @var $primaryKey
    */
    protected $primaryKey = 'id';

    /**
    * @var $fillable
    */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'plain_description',
        'html_description',
        'status',
        'created_at',
        'updated_at',
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
    const STATUS_PUBLISHED = 0;
    const STATUS_PENDING = 1;
    const STATUS_REMOVED = 2;

    const POST_DISPLAY_RELEVANT = 'RELEVANT';
    const POST_DISPLAY_LATEST = 'LATEST';
    const POST_DISPLAY_TOP = 'TOP';

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
     * This method return mulitple Category relation to post.
     *
     * @return collection
     */
    public function category()
    {
        return $this->BelongsTo(Category::class, 'category_id');
    }

    /**
     * This method return mulitple Tag relation to post.
     *
     * @return collection
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    /**
     * This method return mulitple User relation to post.
     *
     * @return collection
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * This method return mulitple Likes relation to post.
     *
     * @return collection
     */
    public function likes()
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    /**
     * This method return mulitple favorites relation to post.
     *
     * @return collection
     */
    public function favorites()
    {
        return $this->hasMany(PostFavorite::class, 'post_id')
            ->whereNull('deleted_at');
    }


    /**
     * This method return mulitple comments relation to post.
     *
     * @return collection
     */
    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id')->with([ 'user' ]);
    }

    /*======================================================================
     * SCOPES
     *======================================================================*/

     /**
     * where not deleted
     */
    // public function scopeWhereNotDeleted($query)
    // {
    //     $query->whereNull('deleted_at');
    //     return $query;
    // }
}
