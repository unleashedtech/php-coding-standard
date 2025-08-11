# ! IMPORTANT !

We are moving this repository to [jonnyeom/php-coding-standard](https://github.com/jonnyeom/php-coding-standard) for it is no longer maintained by Unleashed.
It will maintain the original name and will be accessible through composer with, `unleashed/php-coding-standard`

The transition is expected to be finished and live by August 25, 2025. Thank You!

# Unleashed Technologies PHP_CodeSniffer Coding Standard

[![Latest Version](https://img.shields.io/packagist/v/unleashedtech/php-coding-standard.svg?style=flat-square)](https://packagist.org/packages/unleashedtech/php-coding-standard)
[![Total Downloads](https://img.shields.io/packagist/dt/unleashedtech/php-coding-standard.svg?style=flat-square)](https://packagist.org/packages/unleashedtech/php-coding-standard)
[![Build Status](https://img.shields.io/travis/unleashedtech/php-coding-standard/master.svg?style=flat-square)](https://travis-ci.org/unleashedtech/php-coding-standard)
[![Software License](https://img.shields.io/badge/License-MIT-brightgreen.svg?style=flat-square)](LICENSE)

A PHP coding standard for Unleashed Technologies, originally based on [doctrine/coding-standard](https://github.com/doctrine/coding-standard).

## Overview

This coding standard is based on [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md) and [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md),
with some noticeable exceptions/differences/extensions based on best-practices adopted by Symfony, Doctrine, and the wider community:

- Keep the nesting of control structures per method as small as possible
- Align equals (``=``) signs in assignments
- Add spaces around a concatenation operator ``$foo = 'Hello ' . 'World!';``
- Add spaces between assignment, control and return statements
- Add spaces after the colon in return type declaration ``function (): void {}``
- Add spaces after a type cast ``$foo = (int) '12345';``
- Use single-quotes for enclosing strings
- Always use strict comparisons
- Always add ``declare(strict_types=1)`` at the beginning of a file
- Always add native types where possible
- Omit phpDoc for parameters/returns with native types, unless adding description
- Don't use ``@author``, ``@since`` and similar annotations that duplicate Git information
- Use parentheses when creating new instances that do not require arguments ``$foo = new Foo()``
- Use Null Coalesce Operator ``$foo = $bar ?? $baz``
- Prefer early exit over nesting conditions or using else
- Always use fully-qualified global functions (without needing `use function` statements)
- Forbids the use of `\DateTime`

For full reference of enforcements, go through ``src/Unleashed/ruleset.xml`` where each sniff is briefly described.

## Installation

You can install the Unleashed Coding Standard as a [Composer](https://getcomposer.org/) dependency in your project:

```sh
composer require --dev unleashedtech/php-coding-standard
```

Then you can use it like this:

```sh
vendor/bin/phpcs --standard=Unleashed /path/to/some/files.php
```

You can also use `phpcbp` to automatically find and fix any violations:

```sh
vendor/bin/phpcbf --standard=Unleashed /path/to/some/files.php
```

### Project-Level Ruleset

To enable the Unleashed Coding Standard for your project, create a `phpcs.xml.dist` file with the following content:

```xml
<?xml version="1.0"?>
<ruleset>
    <arg name="basepath" value="."/>
    <arg name="extensions" value="php"/>
    <arg name="parallel" value="80"/>
    <arg name="cache" value=".phpcs-cache"/>
    <arg name="colors"/>

    <!-- Ignore warnings, show progress of the run and show sniff names -->
    <arg value="nps"/>

    <!-- Directories to be checked -->
    <file>src</file>
    <file>tests</file>

    <!-- Include full Unleashed Coding Standard -->
    <rule ref="Unleashed"/>
</ruleset>
```


This will enable the full Unleashed Coding Standard with all rules included with their defaults.
From now on you can just run `vendor/bin/phpcs` and `vendor/bin/phpcbf` without any arguments.

Don't forget to add `.phpcs-cache` and `phpcs.xml` (without `.dist` suffix) to your `.gitignore`.
The first ignored file is a cache used by PHP CodeSniffer to speed things up,
the second one allows any developer to adjust configuration locally without touching the versioned file.

For further reading about the CodeSniffer configuration, please refer to
[the configuration format overview](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Annotated-Ruleset)
and [the list of configuration options](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Configuration-Options).
