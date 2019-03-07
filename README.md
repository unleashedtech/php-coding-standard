[![Build Status](https://secure.travis-ci.org/unleashedtech/php-coding-standard.png)](http://travis-ci.org/unleashedtech/php-coding-standard)

# Unleashed Technologies PHP CodeSniffer Coding Standard

A PHP coding standard for Unleashed Technologies, originally based on [djoos/Symfony-coding-standard](https://www.github.com/djoos/Symfony-coding-standard).

## Installation

### Composer

This standard can be installed with the [Composer](https://getcomposer.org/) dependency manager.

1. [Install Composer](https://getcomposer.org/doc/00-intro.md)

2. Install the coding standard as a dependency of your project

        composer require --dev unleashedtech/php-coding-standard:^1.0

3. Add the coding standard to the PHP_CodeSniffer install path

        vendor/bin/phpcs --config-set installed_paths vendor/unleashedtech/php-coding-standard

4. Check the installed coding standards for "Unleashed"

        vendor/bin/phpcs -i

5. Done!

        vendor/bin/phpcs /path/to/code

### Stand-alone

1. Install [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

2. Checkout this repository 

        git clone git://github.com/unleashedtech/php-coding-standard.git

3. Add the coding standard to the PHP_CodeSniffer install path

        phpcs --config-set installed_paths /path/to/php-coding-standard

   Or copy/symlink this repository's "Unleashed"-folder inside the phpcs `Standards` directory

4. Check the installed coding standards for "Unleashed"

        phpcs -i

5. Done!

        phpcs /path/to/code
