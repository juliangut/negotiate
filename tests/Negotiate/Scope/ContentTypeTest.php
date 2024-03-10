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
use Jgut\Negotiate\Scope\ContentType;
use Laminas\Diactoros\ServerRequest;
use Negotiation\Accept;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class ContentTypeTest extends TestCase
{
    public function testNegotiationFailure(): void
    {
        $this->expectException(NegotiatorException::class);
        $this->expectExceptionMessage('"Content-Type" header refused');

        $scope = new ContentType(['text/html']);

        $request = (new ServerRequest())
            ->withAddedHeader('Content-Type', 'application/json');

        $scope->negotiateRequest($request, 'provider');
    }

    public function testNegotiationDefault(): void
    {
        $scope = new ContentType(['text/html'], 'text/html');

        $request = (new ServerRequest())
            ->withAddedHeader('Content-Type', 'application/json');

        $request = $scope->negotiateRequest($request, 'provider');

        $negotiationProvider = $request->getAttribute('provider');
        static::assertInstanceOf(Provider::class, $negotiationProvider);
        static::assertInstanceOf(Accept::class, $negotiationProvider->get('Content-Type'));
        static::assertSame('text/html', $negotiationProvider->get('Content-Type')->getValue());
        static::assertSame('text/html', $request->getHeaderLine('Content-Type'));
    }

    public function testNegotiationSuccess(): void
    {
        $scope = new ContentType(['application/xml', 'application/json'], 'text/html');

        $request = (new ServerRequest())
            ->withAddedHeader('Content-Type', 'application/xml;q=0.5, application/json;q=0.8');

        $request = $scope->negotiateRequest($request, 'provider');

        $negotiationProvider = $request->getAttribute('provider');
        static::assertInstanceOf(Provider::class, $negotiationProvider);
        static::assertInstanceOf(Accept::class, $negotiationProvider->get('Content-Type'));
        static::assertSame('application/json', $negotiationProvider->get('Content-Type')->getValue());
        static::assertSame('application/json', $request->getHeaderLine('Content-Type'));
    }
}
