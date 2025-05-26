<?php

namespace IBroStudio\FileDataManager;

use IBroStudio\FileDataManager\Managers\JsManager;
use IBroStudio\FileDataManager\Managers\JsonManager;
use IBroStudio\FileDataManager\Managers\PhpManager;
use Illuminate\Support\Manager;

class FileTypes extends Manager
{
    public function getDefaultDriver()
    {
        return 'json';
    }

    public function createJsDriver()
    {
        return new JsManager;
    }

    public function createJsonDriver()
    {
        return new JsonManager;
    }

    public function createPhpDriver()
    {
        return new PhpManager;
    }
}
