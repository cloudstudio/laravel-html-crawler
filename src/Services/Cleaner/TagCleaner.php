<?php

namespace CloudStudio\HtmlCrawler\Services\Cleaner;

/**
 * Class TagCleaner
 *
 * This class implements the CleanerInterface and provides functionality
 * to clean HTML content by allowing only specified HTML tags.
 */
class TagCleaner implements CleanerInterface
{
    /**
     * @var array List of allowed HTML tags.
     */
    protected array $allowedTags;

    /**
     * TagCleaner constructor.
     *
     * @param array $allowedTags List of tags that are allowed in the HTML.
     */
    public function __construct(array $allowedTags)
    {
        $this->allowedTags = $allowedTags;
    }

    /**
     * Clean the given HTML content by stripping disallowed tags.
     *
     * @param string $html The HTML content to clean.
     * @return string The cleaned HTML content.
     */
    public function clean(string $html): string
    {
        if (empty($this->allowedTags)) {
            return strip_tags($html);
        }

        $allowed = '';
        foreach ($this->allowedTags as $tag) {
            $allowed .= "<{$tag}>";
        }
        return strip_tags($html, $allowed);
    }
}
