<?php

namespace CloudStudio\HtmlCrawler\Services;

use League\HTMLToMarkdown\HtmlConverter;

/**
 * Class MarkdownConverter
 *
 * Service for converting HTML content to Markdown using League's HtmlConverter.
 */
class MarkdownConverter
{
    /**
     * Convert the provided HTML to Markdown.
     *
     * @param  string  $html  The HTML content.
     * @return string The converted Markdown.
     */
    public static function convert(string $html): string
    {
        // Decode HTML entities
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $converter = new HtmlConverter;

        return $converter->convert($html);
    }
}
