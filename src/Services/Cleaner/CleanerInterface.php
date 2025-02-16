<?php

namespace CloudStudio\HtmlCrawler\Services\Cleaner;

interface CleanerInterface
{
    /**
     * Clean the given HTML and return the modified content.
     *
     * @param string $html
     * @return string
     */
    public function clean(string $html): string;
}
