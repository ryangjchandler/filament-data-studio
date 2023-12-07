<?php

namespace RyanChandler\EasyExport\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RyanChandler\EasyExport\EasyExport
 */
class EasyExport extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \RyanChandler\EasyExport\EasyExport::class;
    }
}
