<?php

namespace IBroStudio\FileDataManager\Facades;

use Illuminate\Support\Facades\Facade;

class FileDataManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'file-data-manager';
    }
}
