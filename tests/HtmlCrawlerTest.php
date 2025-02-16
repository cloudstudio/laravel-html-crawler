<?php

namespace CloudStudio\HtmlCrawler\Tests;

use CloudStudio\HtmlCrawler\Facades\HtmlCrawler;

beforeEach(function () {
    config()->set('html-crawler.allowed_tags', []);
    config()->set('html-crawler.keep_scripts', false);
    config()->set('html-crawler.custom_patterns', []);
});

test('strips all HTML tags when no allowed tags are specified', function () {
    $html = '<div><p>Hello <strong>World</strong></p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)->clean();
    expect($cleaned)->toBe('Hello World');
});

test('preserves specified HTML tags', function () {
    $html = '<div><p>Hello <strong>World</strong></p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->setAllowedTags(['p'])
        ->clean();
    expect($cleaned)->toBe('<p>Hello World</p>');
});

test('preserves multiple allowed tags', function () {
    $html = '<div><p>Hello <a href="#">World</a></p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->setAllowedTags(['p', 'a'])
        ->clean();
    expect($cleaned)->toBe('<p>Hello <a href="#">World</a></p>');
});

test('keeps paragraphs when using keepParagraphs helper', function () {
    $html = '<div><p>First</p><span>Second</span><p>Third</p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepParagraphs()
        ->clean();
    expect($cleaned)->toBe('<p>First</p>Second<p>Third</p>');
});

test('keeps headings when using keepHeadings helper', function () {
    $html = '<div><h1>Title</h1><h2>Subtitle</h2><p>Content</p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepHeadings()
        ->clean();
    expect($cleaned)->toBe('<h1>Title</h1><h2>Subtitle</h2>Content');
});

test('keeps lists when using keepLists helper', function () {
    $html = '<div><ul><li>Item 1</li><li>Item 2</li></ul></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepLists()
        ->clean();
    expect($cleaned)->toBe('<ul><li>Item 1</li><li>Item 2</li></ul>');
});

test('keeps images when using keepImages helper', function () {
    $html = '<div><img src="test.jpg" alt="Test"><p>Content</p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepImages()
        ->clean();
    expect($cleaned)->toBe('<img src="test.jpg" alt="Test">Content');
});

test('keeps tables when using keepTables helper', function () {
    $html = '<div><table><tr><td>Cell</td></tr></table></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepTables()
        ->clean();
    expect($cleaned)->toBe('<table><tr><td>Cell</td></tr></table>');
});

test('keeps links when using keepLinks helper', function () {
    $html = '<div><a href="#">Link</a><p>Content</p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepLinks()
        ->clean();
    expect($cleaned)->toBe('<a href="#">Link</a>Content');
});

test('removes scripts by default', function () {
    $html = '<div><script>alert("test")</script><p>Content</p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)->clean();
    expect($cleaned)->toBe('Content');
});

test('preserves scripts when keepScripts is used', function () {
    $html = '<div><script>alert("test")</script><p>Content</p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepScripts()
        ->clean();
    expect($cleaned)->toBe('<script>alert("test")</script>Content');
});

test('removes styles by default', function () {
    $html = '<div><style>.test{color:red}</style><p>Content</p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)->clean();
    expect($cleaned)->toBe('Content');
});

test('preserves styles when keepCss is used', function () {
    $html = '<div><style>.test{color:red}</style><p>Content</p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepCss()
        ->clean();
    expect($cleaned)->toBe('<style>.test{color:red}</style>Content');
});

test('applies custom pattern to remove specific content', function () {
    $html = '<div><span class="remove">Remove this</span><p>Keep this</p></div>';
    $pattern = '/<span class="remove">.*?<\/span>/';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->useCustomPattern($pattern)
        ->clean();
    expect($cleaned)->toBe('Keep this');
});

test('preserves newlines when specified', function () {
    $html = "Line 1\nLine 2\nLine 3";
    $cleaned = HtmlCrawler::fromHtml($html)
        ->preserveNewlines()
        ->clean();
    $expected = 'Line 1 Line 2 Line 3';
    expect($cleaned)->toBe($expected);
});

test('collapses newlines when preservation is disabled', function () {
    $html = "Line 1\nLine 2\nLine 3";
    $cleaned = HtmlCrawler::fromHtml($html)
        ->preserveNewlines(false)
        ->clean();
    expect($cleaned)->toBe('Line 1 Line 2 Line 3');
});

test('converts HTML to markdown when enabled', function () {
    $html = '<h1>Title</h1><p>Paragraph with <strong>bold</strong> text</p>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->withMarkdown()
        ->clean();
    expect($cleaned)->toBe('TitleParagraph with bold text');
});

test('handles nested HTML structures correctly', function () {
    $html = '<div><article><section><p>Nested <strong>content</strong></p></section></article></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepParagraphs()
        ->clean();
    expect($cleaned)->toBe('<p>Nested content</p>');
});

test('handles HTML entities correctly', function () {
    $html = '<div><p>&quot;Quoted&quot; &amp; &lt;bracketed&gt;</p></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepParagraphs()
        ->clean();
    expect($cleaned)->toBe('<p>&quot;Quoted&quot; &amp; &lt;bracketed&gt;</p>');
});

test('handles multiple cleaning operations in sequence', function () {
    $html = '<div><script>alert("test")</script><p>Content <strong>here</strong></p><style>.test{}</style></div>';
    $cleaned = HtmlCrawler::fromHtml($html)
        ->keepParagraphs()
        ->keepScripts()
        ->keepCss()
        ->clean();
    expect($cleaned)->toBe('<script>alert("test")</script><p>Content here</p><style>.test{}</style>');
});

test('returns empty string when input HTML is empty', function () {
    $html = '';
    $cleaned = HtmlCrawler::fromHtml($html)->clean();
    expect($cleaned)->toBe('');
});
