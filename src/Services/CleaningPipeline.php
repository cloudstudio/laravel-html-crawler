<?php

namespace CloudStudio\HtmlCrawler\Services;

use CloudStudio\HtmlCrawler\Services\Cleaner\CleanerInterface;

class CleaningPipeline
{
    /**
     * @var CleanerInterface[]
     */
    protected array $cleaners = [];

    /**
     * Add a cleaning step to the pipeline.
     *
     * @return $this
     */
    public function addCleaner(CleanerInterface $cleaner): self
    {
        $this->cleaners[] = $cleaner;

        return $this;
    }

    /**
     * Run all cleaning steps sequentially.
     */
    public function run(string $html): string
    {
        foreach ($this->cleaners as $cleaner) {
            $html = $cleaner->clean($html);
        }

        return $html;
    }
}
