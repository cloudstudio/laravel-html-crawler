# Laravel HTML Crawler

A Laravel package for cleaning and transforming HTML content. It provides a fluent interface to remove unwanted elements like CSS, scripts, and more, with options to preserve specific elements and even convert the cleaned HTML to Markdown.

## Features

- **Remove CSS** (inline styles and `<style>` blocks)
- **Remove JavaScript** (inline scripts and `<script>` blocks)
- **Preserve allowed tags** through a configurable list or helper methods
- **Convert to Markdown** for quick text transformations
- **Custom Regex Patterns** to remove specific parts of the HTML
- **Whitespace Normalization** with an option to preserve newlines

## Installation

Install the package using Composer:

```bash
composer require cloudstudio/laravel-html-crawler
```

The package will automatically register itself in Laravel.

To publish the configuration file, run:

```bash
php artisan vendor:publish --provider="CloudStudio\HtmlCrawler\HtmlCrawlerServiceProvider"
```

## Usage

### 1. Basic HTML Cleaning

By default, the package removes disallowed tags (for example, it will strip `<div>` tags and any tags not explicitly allowed):

```php
use CloudStudio\HtmlCrawler\HtmlCrawler;

$html = '<div><p>Hello <strong>World</strong></p></div>';
$cleanHtml = HtmlCrawler::fromHtml($html)->clean();

// Expected output: "Hello World"
```

### 2. Preserving Allowed Tags

You can explicitly specify which tags to preserve:

#### Using `setAllowedTags`

```php
use CloudStudio\HtmlCrawler\HtmlCrawler;

$html = '<div><p>Hello <a href="#">World</a></p></div>';
$cleanHtml = HtmlCrawler::fromHtml($html)
    ->setAllowedTags(['p', 'a'])
    ->clean();

// Expected output: '<p>Hello <a href="#">World</a></p>'
```

#### Using Helper Methods

The package offers helper methods to preserve groups of tags:

```php
use CloudStudio\HtmlCrawler\HtmlCrawler;

$html = '<div><p>Hello <a href="#">World</a></p></div>';
$cleanHtml = HtmlCrawler::fromHtml($html)
    ->keepParagraphs()   // Preserves <p> tags
    ->keepLinks()        // Preserves <a> tags
    ->clean();

// Expected output: '<p>Hello <a href="#">World</a></p>'
```

### 3. Handling Scripts

#### Removing `<script>` by Default

By default, `<script>` blocks are removed:

```php
use CloudStudio\HtmlCrawler\HtmlCrawler;

$html = '<div><script>alert("x")</script><p>Test</p></div>';
$cleanHtml = HtmlCrawler::fromHtml($html)->clean();

// Expected output: "Test"
```

#### Preserving `<script>` with `keepScripts()`

If you wish to keep `<script>` blocks, use the `keepScripts()` method:

```php
use CloudStudio\HtmlCrawler\HtmlCrawler;

$html = '<div><script>alert("x")</script><p>Test</p></div>';
$cleanHtml = HtmlCrawler::fromHtml($html)
    ->keepScripts()
    ->clean();

// Expected output: '<script>alert("x")</script><p>Test</p>'
```

### 4. Handling CSS

By default, `<style>` blocks and CSS links are removed. To preserve them, use `keepCss()`:

```php
use CloudStudio\HtmlCrawler\HtmlCrawler;

$html = '<div><style>.text { color: red; }</style><p>Styled text</p></div>';
$cleanHtml = HtmlCrawler::fromHtml($html)
    ->keepCss()
    ->clean();

// Expected output: '<style>.text { color: red; }</style><p>Styled text</p>'
```

### 5. Using a Custom Regex Pattern

If you need to remove specific parts of the HTML using a regular expression:

```php
use CloudStudio\HtmlCrawler\HtmlCrawler;

$html = '<div><span class="remove">Remove me</span><p>Keep me</p></div>';
$pattern = '/<span class="remove">.*?<\/span>/';
$cleanHtml = HtmlCrawler::fromHtml($html)
    ->useCustomPattern($pattern)
    ->clean();

// Expected output: '<p>Keep me</p>'
```

### 6. Converting to Markdown

You can convert the cleaned HTML to Markdown:

```php
use CloudStudio\HtmlCrawler\HtmlCrawler;

$html = '<h1>Title</h1><p>Paragraph text</p>';
$markdown = HtmlCrawler::fromHtml($html)
    ->withMarkdown()
    ->clean();

```

### 7. Handling Newlines

Control how newlines are handled in the HTML:

```php
use CloudStudio\HtmlCrawler\HtmlCrawler;

$html = "Line 1\nLine 2";
$cleanHtml = HtmlCrawler::fromHtml($html)
    ->preserveNewlines(false)  // Set to false to replace newlines with spaces
    ->clean();

// Expected output: "Line 1 Line 2"
```

### 8. Loading HTML from a URL

You can also load HTML directly from a URL:

```php
use CloudStudio\HtmlCrawler\HtmlCrawler;

$cleanHtml = HtmlCrawler::fromUrl('https://example.com')
    ->clean();

// Output: the cleaned HTML content retrieved from the URL.
```

## Configuration

The package includes a configuration file that allows you to define default options. After publishing the configuration file, you will find it at `config/html-crawler.php`:

```php
return [
    'preserve_newlines'   => true,
    'allowed_tags'        => [],
    'convert_to_markdown' => false,
    'remove_scripts'      => true,
    'remove_styles'       => true,
];
```

You can modify these values according to your needs.

## Troubleshooting

If you encounter the error:

```
BindingResolutionException: Target class [config] does not exist.
```

make sure your tests are running in a Laravel environment using **orchestra/testbench**. For package testing, install Testbench with:

```bash
composer require --dev orchestra/testbench
```

Then, set up your base test case to extend Testbench (see the package documentation for more details).

## Testing

To run the tests, you can use:

```bash
./vendor/bin/pest
```

or if using PHPUnit:

```bash
./vendor/bin/phpunit
```

## Changelog

Please see the [CHANGELOG](CHANGELOG.md) for detailed information on recent changes.

## Contributing

Please refer to [CONTRIBUTING](.github/CONTRIBUTING.md) for details on how to contribute to this package.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Cloud Studio](https://github.com/cloudstudio)
- [All Contributors](../../contributors)

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).