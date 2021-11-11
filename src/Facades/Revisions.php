<?php

namespace Lilbase\Revisions\Facades;

use Illuminate\Support\Facades\Facade;

class Revisions extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'revisions';
    }
}
