<?php

return [

    /*
    |--------------------------------------------------------------------------
    | HTML Crawler Defaults
    |--------------------------------------------------------------------------
    |
    | These options define the default behavior of the HTML Crawler package.
    |
    */

    // Preserve newlines in the cleaned HTML output.
    'preserve_newlines' => true,

    // Remove <script> blocks by default.
    'remove_scripts' => true,

    // Remove <style> blocks by default.
    'remove_styles' => true,

    // Default allowed HTML tags. If empty, all HTML tags are removed.
    'allowed_tags' => [],

    // Convert cleaned HTML to Markdown by default.
    'convert_to_markdown' => false,
];
