<?php

/*
 * (c) 2017-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 */

declare(strict_types=1);

namespace Jgut\Negotiate\Tests\Scope;

use Jgut\Negotiate\NegotiatorException;
use Jgut\Negotiate\Provider;
use Jgut\Negotiate\Scope\Language;
use Laminas\Diactoros\ServerRequest;
use Negotiation\AcceptLanguage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class LanguageTest extends TestCase
{
    public function testNegotiationMissing(): void
    {
        $this->expectException(NegotiatorException::class);
        $this->expectExceptionMessage('"Accept-Language" header missing or refused.');

        $scope = new Language(['es']);

        $scope->negotiateRequest(new ServerRequest(), 'provider');
    }

    public function testNegotiationFailure(): void
    {
        $this->expectException(NegotiatorException::class);
        $this->expectExceptionMessage('"Accept-Language" header missing or refused.');

        $scope = new Language(['es']);

        $request = (new ServerRequest())
            ->withAddedHeader('Accept-Language', 'en');

        $scope->negotiateRequest($request, 'provider');
    }

    public function testNegotiationDefault(): void
    {
        $scope = new Language(['es'], 'es');

        $request = (new ServerRequest())
            ->withAddedHeader('Accept-Language', 'en');

        $request = $scope->negotiateRequest($request, 'provider');

        $negotiationProvider = $request->getAttribute('provider');
        static::assertInstanceOf(Provider::class, $negotiationProvider);
        static::assertInstanceOf(AcceptLanguage::class, $negotiationProvider->get('Accept-Language'));
        static::assertSame('es', $negotiationProvider->get('Accept-Language')->getValue());
        static::assertSame('es', $request->getHeaderLine('Accept-Language'));
    }

    public function testNegotiationSuccess(): void
    {
        $scope = new Language(['en'], 'es');

        $request = (new ServerRequest())
            ->withAddedHeader('Accept-Language', 'en');

        $request = $scope->negotiateRequest($request, 'provider');

        $negotiationProvider = $request->getAttribute('provider');
        static::assertInstanceOf(Provider::class, $negotiationProvider);
        static::assertInstanceOf(AcceptLanguage::class, $negotiationProvider->get('Accept-Language'));
        static::assertSame('en', $negotiationProvider->get('Accept-Language')->getValue());
        static::assertSame('en', $request->getHeaderLine('Accept-Language'));
    }
}
