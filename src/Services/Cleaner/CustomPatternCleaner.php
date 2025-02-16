<?php

namespace CloudStudio\HtmlCrawler\Services\Cleaner;

/**
 * Class CustomPatternCleaner
 *
 * This class implements the CleanerInterface and provides functionality
 * to clean HTML content based on a custom regex pattern.
 */
class CustomPatternCleaner implements CleanerInterface
{
    /**
     * @var string|null The regex pattern used for cleaning.
     */
    protected ?string $pattern;

    /**
     * CustomPatternCleaner constructor.
     *
     * @param  string|null  $pattern  The regex pattern to apply for cleaning.
     */
    public function __construct(?string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Clean the given HTML content by applying the custom regex pattern.
     *
     * @param  string  $html  The HTML content to clean.
     * @return string The cleaned HTML content.
     */
    public function clean(string $html): string
    {
        if ($this->pattern) {
            return preg_replace($this->pattern, '', $html);
        }

        return $html;
    }
}
