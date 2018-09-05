# lara-ore-exporter

[![Build Status](https://travis-ci.org/railken/lara-ore-exporter.svg?branch=master)](https://travis-ci.org/railken/lara-ore-exporter)

This is a [lara-ore](https://github.com/railken/lara-ore) package.

I've got bored while creating exports: 
- Bob wants all users given a range of dates (created_at). 
- John wants the same but only the users that have at least 2 orders.
- Frank wants all the orders that needed to be shipped between dates
- Thomas wants all the orders paid between dates
- Charlie wants all orders that are coming from a specific user, and somehow he wants an export for it
- David want the same export as Bob but without the updated_at field, it bothers him, apparently

And these are the easy one. So here we are, with a package that works with [lara-ore-repository](https://github.com/railken/lara-ore) and manage exports. Now a super-user can manipulate exporters through apis and i have a clean code.

# Requirements

PHP 7.1 and later.

## Installation

You can install it via [Composer](https://getcomposer.org/) by typing the following command:

```bash
composer require railken/lara-ore-exporter
```

The package will automatically register itself.

## Documentation

[Read](docs/index.md)

## Testing

Configure the .env file before launching `./vendor/bin/phpunit`
