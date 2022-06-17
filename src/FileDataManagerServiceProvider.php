<?php

namespace IBroStudio\FileDataManager;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FileDataManagerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-file-content-manager');
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();
        /*
                $this->app->bind('file-content-manager', function () {
                    return new FileDataManager();
                });
                */
    }
}
