<?php

namespace App\Helpers;

use App\Models\Tag;

class Globals
{
    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    CONST NOTIFIABLE_TYPE = [
        'post_likes' => 'App\\Models\\PostLike',
        'post_favorites' => 'App\\Models\\PostFavorite',
    ];

    /*======================================================================
     * STATIC METHODS
     *======================================================================*/

    /**
     * Globals::__()
     * return a concatenated by lang/locale by space (" ")
     *
     * @param Strin/Int/Object/Array $trans
     * @return String $rtn
     */
    public static function __($trans)
    {
        $rtn = '';

        if (!is_array($trans)) {
            $trans = [$trans];
        }

        foreach ($trans as $ind => $tran) {
            if ($ind != 0) {
                $rtn .= ' ';
            }

            $rtn .= __($tran);
        }

        return $rtn;
    }

    /**
     * Globals::__values()
     * return an array with translated values
     *
     * @param Array $data
     * @return Array $rtn
     */
    public static function __values(array $data)
    {
        $rtn = $data;

        foreach ($data as $key => $val) {
            $rtn[$key] = __($val);
        }

        return $rtn;
    }

    /**
     * Globals::mTag()
     * return a model class (Tag)
     *
     * @return Tag
     */
    public static function mTag()
    {
        return Tag::class;
    }
}
