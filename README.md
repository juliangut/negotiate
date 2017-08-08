[![PHP version](https://img.shields.io/badge/PHP-%3E%3D7-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/juliangut/negotiate.svg?style=flat-square)](https://packagist.org/packages/juliangut/negotiate)
[![License](https://img.shields.io/github/license/juliangut/negotiate.svg?style=flat-square)](https://github.com/juliangut/negotiate/blob/master/LICENSE)

[![Build Status](https://img.shields.io/travis/juliangut/negotiate.svg?style=flat-square)](https://travis-ci.org/juliangut/negotiate)
[![Style Check](https://styleci.io/repos/XXXXXXXX/shield)](https://styleci.io/repos/XXXXXXXX)
[![Code Quality](https://img.shields.io/scrutinizer/g/juliangut/negotiate.svg?style=flat-square)](https://scrutinizer-ci.com/g/juliangut/negotiate)
[![Code Coverage](https://img.shields.io/coveralls/juliangut/negotiate.svg?style=flat-square)](https://coveralls.io/github/juliangut/negotiate)

[![Total Downloads](https://img.shields.io/packagist/dt/juliangut/negotiate.svg?style=flat-square)](https://packagist.org/packages/juliangut/negotiate/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/juliangut/negotiate.svg?style=flat-square)](https://packagist.org/packages/juliangut/negotiate/stats)

# Negotiate

Simple and flexible negotiation middleware based on [willdurand/negotiation](https://github.com/willdurand/Negotiation)

## Installation

### Composer

```
composer require juliangut/negotiate
```

## Usage

Require composer autoload file

```php
require './vendor/autoload.php';

use Jgut\Negotiate\Negotiator;
use Negotiation\LanguageNegotiator;
use Negotiation\Negotiator as MediaTypeNegotiator;

$mediaTypeScope = new MediaType(['text/html', 'application/json'], new MediaTypeNegotiator());
$languageScope = new Language(['en', 'es'], new LanguageNegotiator(), false); // If 

$middleware = new Negotiator(['media' => $mediaTypeScope, 'language' => $languageScope]);
$middleware->setAttributeName('negotiate');

$next = function ($request, $response, ...) {
    $negotiator = $request->getAttribute('negotiate');

    $negotiator->getMedia(); // \Negotiation\Accept
    $negotiator->getLanguage(); // \Negotiation\AcceptLanguage
    $negotiator->getCharset(); // null, not defined
}

$middleware($request, $response, $next);
```

### Scopes

Encapsulate negotiation in a context, for example media type or character set. Give it a list of priorities and a willdurand negotiator and you are good to go.

Additionally a third parameter controls behaviour if request header is empty or negotiation could not be determined successfully. By default your list of priorities will be used to create a `\Negotiation\AcceptHeader` you can use. If set to false a `\Jgut\Negotiation\Exception` will be thrown and captured by the middleware

### Middleware

Middleware requires a list of scopes with a name. Negotiation will take place in the middleware and:

* If everything goes well request will have an attribute with a `\Jgut\Negotiate\Provider`
* If negotiation raises an error a 406 response will be returned from the middleware

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/negotiate/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/juliangut/negotiate/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/juliangut/negotiate/blob/master/LICENSE) included with the source code for a copy of the license terms.
