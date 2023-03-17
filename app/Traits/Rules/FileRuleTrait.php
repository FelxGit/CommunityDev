<?php

namespace App\Traits\Rules;
use App\Helpers\Globals;

trait FileRuleTrait
{
    /*======================================================================
     * STATIC METHODS
     *======================================================================*/

    /**
     * Merge the default global rule and custom rule additional rule to be use in validation
     *
     * @param Array ...$additionalRule
     * @return Array $rtn
     */
    public function imageRule(...$additionalRule)
    {
        $rtn = ['max:2000', 'mimes:' . implode(',', Globals::IMG_ACCEPTEDEXTENSION)];
        $rtn = array_merge($rtn, $additionalRule);

        return $rtn;
    }
}
