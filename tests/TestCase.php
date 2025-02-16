<?php

namespace CloudStudio\HtmlCrawler\Tests;

use CloudStudio\HtmlCrawler\HtmlCrawlerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            HtmlCrawlerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('html-crawler.allowed_tags', ['p', 'a']);
        config()->set('html-crawler.keep_scripts', false);
        config()->set('html-crawler.custom_patterns', []);
    }
}
