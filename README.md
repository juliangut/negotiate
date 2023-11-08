[![PHP version](https://img.shields.io/badge/PHP-%3E%3D8.0-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/juliangut/negotiate.svg?style=flat-square)](https://packagist.org/packages/juliangut/negotiate)
[![License](https://img.shields.io/github/license/juliangut/negotiate.svg?style=flat-square)](https://github.com/juliangut/negotiate/blob/master/LICENSE)

[![Total Downloads](https://img.shields.io/packagist/dt/juliangut/negotiate.svg?style=flat-square)](https://packagist.org/packages/juliangut/negotiate/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/juliangut/negotiate.svg?style=flat-square)](https://packagist.org/packages/juliangut/negotiate/stats)

# Negotiate

Simple and flexible negotiation middleware using [willdurand/negotiation](https://github.com/willdurand/Negotiation)

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
use Jgut\Negotiate\Scope\Language;
use Jgut\Negotiate\Scope\MediaType;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

$scopes = [
    new MediaType(['text/html', 'application/json'], 'text/html'),
    new Language(['en', 'es']),
];
/* @var \Psr\Http\Message\ResponseFactoryInterface $responseFactory */

$middleware = new Negotiator($scopes, $responseFactory);
$middleware->setAttributeName('negotiationProvider');

// Request handler
new class () implements RequestHandlerInterface {
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $negotiationProvider = $request->getAttribute('negotiationProvider');

        $negotiationProvider->get('Accept-Language'); // \Negotiation\Accept
        $negotiationProvider->getAcceptLanguage(); // \Negotiation\AcceptLanguage
        $negotiationProvider->getAcceptLanguageLine(); // Negotiated language string
        $negotiationProvider->getAcceptCharset(); // null, not defined
        
        // ...
    }
};
```

### Scopes

Encapsulate negotiation in a context, for example media type or character set. Give it a list of priorities, and you are good to go

An optional second parameter controls behaviour if request header is empty or negotiation could not be determined successfully. If set, it will be used to create a `\Negotiation\AcceptHeader`, A `\Jgut\Negotiation\NegotiatorException` will be thrown and captured by the middleware otherwise

### Middleware

Middleware requires a list of negotiation scopes. Negotiation will take place in the middleware

* If everything goes well request will have 
  * Headers of each scope overridden with negotiation result
  * An attribute with a `\Jgut\Negotiate\Provider` object with the result of the whole negotiation
* If negotiation goes south
  * A 415 response will be returned if Content-Type header negotiation fails
  * A 406 response will be returned if any other negotiation fails

## Migration from 1.x

* PHP minimum required version is PHP 8.0
* Scope's default should be specified in each constructor should it be needed
* Scope's negotiateRequest now returns modified request (internal change)

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/juliangut/negotiate/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/juliangut/negotiate/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/juliangut/negotiate/blob/master/LICENSE) included with the source code for a copy of the license terms.
