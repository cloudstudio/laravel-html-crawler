<?php

namespace CloudStudio\HtmlCrawler\Services\Cleaner;

/**
 * Class ScriptRemover
 *
 * This class implements the CleanerInterface and provides functionality
 * to remove <script> tags from HTML content based on specified conditions.
 */
class ScriptRemover implements CleanerInterface
{
    /**
     * @var bool Indicates whether to remove <script> tags.
     */
    protected bool $remove;

    /**
     * @var array List of allowed HTML tags.
     */
    protected array $allowedTags;

    /**
     * ScriptRemover constructor.
     *
     * @param  bool  $remove  Indicates whether to remove <script> tags.
     * @param  array  $allowedTags  List of tags that are allowed in the HTML.
     */
    public function __construct(bool $remove, array $allowedTags)
    {
        $this->remove = $remove;
        $this->allowedTags = $allowedTags;
    }

    /**
     * Clean the given HTML content by removing <script> tags if specified.
     *
     * @param  string  $html  The HTML content to clean.
     * @return string The cleaned HTML content.
     */
    public function clean(string $html): string
    {
        if ($this->remove && ! in_array('script', $this->allowedTags)) {
            return preg_replace('#<script.*?</script>#is', '', $html);
        }

        return $html;
    }
}
