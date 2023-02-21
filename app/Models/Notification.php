<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Notification extends Model
{
    /**
    * @var $table
    */
    protected $table = 'notifications';

    /**
    * @var $primaryKey
    */
    protected $primaryKey = 'id';

    /**
    * @var $primaryKey
    */
    protected $keyType = 'string';

    /**
    * @var $fillable
    */
    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
    * @var $cast
    */
    protected $casts = [
        'data' => 'array'
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

    public function setDataAttribute($data)
    {
        $this->attributes['data'] = json_encode($data);
    }

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * This method return mulitple User relation to post.
     *
     * @return collection
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }

    /*======================================================================
     * SCOPES
     *======================================================================*/
}
