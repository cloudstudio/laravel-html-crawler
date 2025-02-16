<?php

namespace CloudStudio\HtmlCrawler;

use CloudStudio\HtmlCrawler\Exceptions\InvalidUrlException;
use CloudStudio\HtmlCrawler\Services\HtmlCleaner;
use CloudStudio\HtmlCrawler\Services\MarkdownConverter;

class HtmlCrawler
{
    /**
     * The original HTML content.
     */
    protected string $html;

    /**
     * Array of allowed HTML tags (e.g. ['p', 'a', 'h1']).
     */
    protected array $allowedTags = [];

    /**
     * A custom regex pattern to remove parts of the HTML.
     */
    protected ?string $customPattern = null;

    /**
     * Whether to preserve newlines in the output.
     */
    protected bool $preserveNewlines;

    /**
     * Whether to convert the cleaned HTML to Markdown.
     */
    protected bool $convertToMarkdown;

    /**
     * Whether to remove script blocks by default.
     */
    protected bool $removeScripts;

    /**
     * Whether to remove style blocks by default.
     */
    protected bool $removeStyles;

    /**
     * HtmlCrawler constructor.
     *
     * Loads default options from configuration.
     */
    public function __construct()
    {
        $this->preserveNewlines = config('htmlcrawler.preserve_newlines', true);
        $this->allowedTags = config('htmlcrawler.allowed_tags', []);
        $this->convertToMarkdown = config('htmlcrawler.convert_to_markdown', false);
        $this->removeScripts = config('htmlcrawler.remove_scripts', true);
        $this->removeStyles = config('htmlcrawler.remove_styles', true);
    }

    /**
     * Initialize the crawler with an HTML string.
     *
     * @return static
     */
    public static function fromHtml(string $html): self
    {
        $instance = new self;
        $instance->html = $html;

        return $instance;
    }

    /**
     * Initialize the crawler with HTML loaded from a URL.
     *
     * @return static
     *
     * @throws InvalidUrlException
     * @throws \RuntimeException
     */
    public static function fromUrl(string $url): self
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException("Invalid URL provided: {$url}");
        }

        $html = file_get_contents($url);
        if ($html === false) {
            throw new \RuntimeException("Failed to get content from: {$url}");
        }

        return self::fromHtml($html);
    }

    /**
     * Specify which HTML tags should be preserved.
     *
     * @param  string|array  $tags  A tag or an array of tags (e.g., 'p' or ['p', 'a']).
     * @return $this
     */
    public function keepTags(string|array $tags): self
    {
        $tags = is_array($tags) ? $tags : [$tags];
        $this->allowedTags = array_unique(array_merge($this->allowedTags, $tags));

        return $this;
    }

    /**
     * Replace the allowed tags list with a new set.
     *
     * @return $this
     */
    public function setAllowedTags(array $tags): self
    {
        $this->allowedTags = $tags;

        return $this;
    }

    /**
     * Preserve paragraph tags (<p>).
     *
     * @return $this
     */
    public function keepParagraphs(): self
    {
        return $this->keepTags('p');
    }

    /**
     * Preserve heading tags (<h1> to <h6>).
     *
     * @return $this
     */
    public function keepHeadings(): self
    {
        return $this->keepTags(['h1', 'h2', 'h3', 'h4', 'h5', 'h6']);
    }

    /**
     * Preserve list tags (<ul>, <ol>, <li>).
     *
     * @return $this
     */
    public function keepLists(): self
    {
        return $this->keepTags(['ul', 'ol', 'li']);
    }

    /**
     * Preserve image tags (<img>).
     *
     * @return $this
     */
    public function keepImages(): self
    {
        return $this->keepTags('img');
    }

    /**
     * Preserve table tags (<table>, <thead>, <tbody>, <tr>, <th>, <td>).
     *
     * @return $this
     */
    public function keepTables(): self
    {
        return $this->keepTags(['table', 'thead', 'tbody', 'tr', 'th', 'td']);
    }

    /**
     * Preserve anchor tags (<a>).
     *
     * @return $this
     */
    public function keepLinks(): self
    {
        return $this->keepTags('a');
    }

    /**
     * Allow script blocks to be preserved.
     * By default, scripts are removed.
     *
     * @return $this
     */
    public function keepScripts(): self
    {
        $this->removeScripts = false;
        if (! in_array('script', $this->allowedTags)) {
            $this->allowedTags[] = 'script';
        }

        return $this;
    }

    /**
     * Allow style blocks (and CSS links) to be preserved.
     * By default, they are removed.
     *
     * @return $this
     */
    public function keepCss(): self
    {
        $this->removeStyles = false;
        if (! in_array('style', $this->allowedTags)) {
            $this->allowedTags[] = 'style';
        }
        if (! in_array('link', $this->allowedTags)) {
            $this->allowedTags[] = 'link';
        }

        return $this;
    }

    /**
     * Set a custom regex pattern to remove parts of the HTML.
     * This pattern takes precedence over allowed tags.
     *
     * @return $this
     */
    public function useCustomPattern(string $pattern): self
    {
        $this->customPattern = $pattern;

        return $this;
    }

    /**
     * Set whether to preserve newlines.
     *
     * @return $this
     */
    public function preserveNewlines(bool $preserve = true): self
    {
        $this->preserveNewlines = $preserve;

        return $this;
    }

    /**
     * Enable Markdown conversion for the cleaned HTML.
     *
     * @return $this
     */
    public function withMarkdown(): self
    {
        $this->convertToMarkdown = true;

        return $this;
    }

    /**
     * Execute the cleaning (and Markdown conversion, if enabled)
     * and return the resulting string.
     *
     * @return string Cleaned (and possibly converted) content.
     */
    public function clean(): string
    {
        $cleaner = new HtmlCleaner;
        $result = $cleaner->clean(
            $this->html,
            $this->allowedTags,
            $this->customPattern,
            $this->preserveNewlines,
            $this->removeScripts,
            $this->removeStyles
        );

        if ($this->convertToMarkdown) {
            $result = MarkdownConverter::convert($result);
        }

        return $result;
    }

    /**
     * Magic method to return the cleaned content when the object is treated as a string.
     */
    public function __toString(): string
    {
        return $this->clean();
    }
}
