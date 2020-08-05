# PHPStan error formatter for Markdown

Output errors as Markdown with PHPStan.

## Install

Install this package with

```shell script
composer require supermetrics/phpstan-error-formatter-md
```

## Configuration

In your `phpstan.neon` add configuration

```neon
services:
    errorFormatter.markdown:
        class: Supermetrics\PHPStan\Command\ErrorFormatter\MarkdownErrorFormatter
```

## Usage

```shell script
phpstan --error-format=markdown analyse src 
```
