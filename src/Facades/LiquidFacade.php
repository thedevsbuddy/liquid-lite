<?php

namespace Devsbuddy\LiquidLite\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Devsbuddy\Laravelcms\Skeleton\SkeletonClass
 */
class LiquidFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'liquid';
    }
}
