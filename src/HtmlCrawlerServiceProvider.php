<?php

namespace CloudStudio\HtmlCrawler;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Class HtmlCrawlerServiceProvider
 *
 * Service provider for the HtmlCrawler package.
 * Responsible for configuring the package, including
 * setting up configuration files and views.
 */
class HtmlCrawlerServiceProvider extends PackageServiceProvider
{
    /**
     * Configure the package.
     *
     * @param  Package  $package  The package instance to configure.
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-html-crawler')
            ->hasConfigFile();
    }

    /**
     * Register the package in the application container.
     *
     * This method binds the HtmlCrawler class to the service container
     * and creates an alias for easier access.
     */
    public function packageRegistered(): void
    {
        $this->app->singleton(HtmlCrawler::class, function ($app) {
            return new HtmlCrawler;
        });

        $this->app->alias(HtmlCrawler::class, 'html-crawler');
    }
}
