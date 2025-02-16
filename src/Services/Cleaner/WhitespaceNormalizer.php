<?php

namespace CloudStudio\HtmlCrawler\Services\Cleaner;

/**
 * Class WhitespaceNormalizer
 *
 * This class implements the CleanerInterface and provides functionality
 * to normalize whitespace in HTML content by collapsing multiple spaces
 * and optionally preserving newlines.
 */
class WhitespaceNormalizer implements CleanerInterface
{
    /**
     * @var bool Indicates whether to preserve newlines in the HTML content.
     */
    protected bool $preserveNewlines;

    /**
     * WhitespaceNormalizer constructor.
     *
     * @param  bool  $preserveNewlines  Indicates whether to preserve newlines.
     */
    public function __construct(bool $preserveNewlines)
    {
        $this->preserveNewlines = $preserveNewlines;
    }

    /**
     * Clean the given HTML content by normalizing whitespace.
     *
     * This method collapses multiple spaces into a single space and
     * preserves newlines if specified.
     *
     * @param  string  $html  The HTML content to clean.
     * @return string The cleaned HTML content with normalized whitespace.
     */
    public function clean(string $html): string
    {
        // Collapse multiple spaces.
        $html = preg_replace('/\s+/', ' ', $html);

        if ($this->preserveNewlines) {
            $html = preg_replace('/\s*\n\s*/', "\n", $html);
        }

        return $html;
    }
}
