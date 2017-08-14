# Personal Identification Number validation

[![Latest Version](https://img.shields.io/packagist/v/vimishor/cnp-php.svg?style=flat-square)](https://packagist.org/packages/vimishor/cnp-php)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/vimishor/cnp-php/develop.svg?style=flat-square)](https://travis-ci.org/vimishor/cnp-php)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/vimishor/cnp-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/vimishor/cnp-php/?branch=develop)
[![Code quality](https://img.shields.io/scrutinizer/g/vimishor/cnp-php.svg?style=flat-square)](https://scrutinizer-ci.com/g/vimishor/cnp-php/?branch=develop)

PHP implementation of [Personal Identification Number specification - draft 0.1](https://github.com/vimishor/cnp-spec), 
in order to validate Personal Identification Number of Romanian citizens and residents.

## Install

Via Composer

``` bash
$ composer require vimishor/cnp-php
```

## Usage

``` php
use Gentle\Embeddable\Date;
use Vimishor\Cnp\Checksum;
use Vimishor\Cnp\Cnp;
use Vimishor\Cnp\County;
use Vimishor\Cnp\Gender;
use Vimishor\Cnp\Serial;

try {
    $cnp = Cnp::fromString('2791219470034');
} catch (\Vimishor\Cnp\Exception\InvalidCnpException $e) {
    // do something
}

// you can also instantiate everything yourself
try {
    $cnp = new Cnp(
        new Gender(2), Date::fromString('1979-12-19T19:10:23+00:00'), new County(47), new Serial(003), new Checksum(4)
    );
} catch (\Vimishor\Cnp\Exception\InvalidCnpException $e) {
    // do something
}
```

## Testing

``` bash
$ make test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

For any security related issues, please email send an email at [alex@gentle.ro][maintainer-pgp] instead of using the issue tracker.

## License

Licensed under the MIT License - see the LICENSE file for details.

[maintainer-pgp]: https://keybase.io/vimishor/key.asc
