<?php

namespace CloudStudio\HtmlCrawler\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \CloudStudio\HtmlCrawler\HtmlCrawler fromUrl(string $url)
 * @method static \CloudStudio\HtmlCrawler\HtmlCrawler fromHtml(string $html)
 * @method static \CloudStudio\HtmlCrawler\HtmlCrawler clean()
 * @method static string|null getHtml()
 *
 * @see \CloudStudio\HtmlCrawler\HtmlCrawler
 */
class HtmlCrawler extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'html-crawler';
    }
}
