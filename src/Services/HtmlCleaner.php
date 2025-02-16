<?php

namespace CloudStudio\HtmlCrawler\Services;

use CloudStudio\HtmlCrawler\Services\Cleaner\CustomPatternCleaner;
use CloudStudio\HtmlCrawler\Services\Cleaner\ScriptRemover;
use CloudStudio\HtmlCrawler\Services\Cleaner\StyleRemover;
use CloudStudio\HtmlCrawler\Services\Cleaner\TagCleaner;
use CloudStudio\HtmlCrawler\Services\Cleaner\WhitespaceNormalizer;

class HtmlCleaner
{
    /**
     * Clean the given HTML content using a modular cleaning pipeline.
     *
     * @param  string  $html  The HTML content to clean.
     * @param  array  $allowedTags  Array of tags to preserve.
     * @param  string|null  $customPattern  Custom regex pattern for removal (takes precedence).
     * @param  bool  $preserveNewlines  Whether to preserve newlines.
     * @param  bool  $removeScripts  Whether to remove <script> blocks.
     * @param  bool  $removeStyles  Whether to remove <style> blocks.
     * @return string The cleaned HTML.
     */
    public function clean(
        string $html,
        array $allowedTags = [],
        ?string $customPattern = null,
        bool $preserveNewlines = true,
        bool $removeScripts = true,
        bool $removeStyles = true
    ): string {
        $pipeline = new CleaningPipeline;

        // Apply custom pattern if provided.
        if ($customPattern) {
            $pipeline->addCleaner(new CustomPatternCleaner($customPattern));
        }

        // Remove scripts if required.
        $pipeline->addCleaner(new ScriptRemover($removeScripts, $allowedTags));

        // Remove styles if required.
        $pipeline->addCleaner(new StyleRemover($removeStyles, $allowedTags));

        // Clean HTML tags based on allowed tags.
        $pipeline->addCleaner(new TagCleaner($allowedTags));

        // Normalize whitespace.
        $pipeline->addCleaner(new WhitespaceNormalizer($preserveNewlines));

        return trim($pipeline->run($html));
    }
}
