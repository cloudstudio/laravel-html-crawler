<?php

namespace CloudStudio\HtmlCrawler\Services\Cleaner;

interface CleanerInterface
{
    /**
     * Clean the given HTML and return the modified content.
     */
    public function clean(string $html): string;
}
