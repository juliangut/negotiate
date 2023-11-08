<?php

/*
 * negotiate (https://github.com/juliangut/negotiate).
 * Negotiation middleware.
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

declare(strict_types=1);

namespace Jgut\Negotiate\Tests\Scope;

use Jgut\Negotiate\NegotiatorException;
use Jgut\Negotiate\Provider;
use Jgut\Negotiate\Scope\MediaType;
use Laminas\Diactoros\ServerRequest;
use Negotiation\Accept;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class MediaTypeTest extends TestCase
{
    public function testNegotiationFailure(): void
    {
        $this->expectException(NegotiatorException::class);
        $this->expectExceptionMessage('"Accept" header refused');

        $scope = new MediaType(['application/json']);

        $request = (new ServerRequest())
            ->withAddedHeader('Accept', 'application/xml');

        $scope->negotiateRequest($request, 'provider');
    }

    public function testNegotiationDefault(): void
    {
        $scope = new MediaType(['application/json'], 'application/json');

        $request = (new ServerRequest())
            ->withAddedHeader('Accept', 'application/xml');

        $request = $scope->negotiateRequest($request, 'provider');

        $negotiationProvider = $request->getAttribute('provider');
        static::assertInstanceOf(Provider::class, $negotiationProvider);
        static::assertInstanceOf(Accept::class, $negotiationProvider->get('Accept'));
        static::assertSame('application/json', $negotiationProvider->get('Accept')->getValue());
        static::assertSame('application/json', $request->getHeaderLine('Accept'));
    }

    public function testNegotiationSuccess(): void
    {
        $scope = new MediaType(['application/xml', 'application/json'], 'application/json');

        $request = (new ServerRequest())
            ->withAddedHeader('Accept', 'application/xml;q=0.8, application/json;q=0.5');

        $request = $scope->negotiateRequest($request, 'provider');

        $negotiationProvider = $request->getAttribute('provider');
        static::assertInstanceOf(Provider::class, $negotiationProvider);
        static::assertInstanceOf(Accept::class, $negotiationProvider->get('Accept'));
        static::assertSame('application/xml', $negotiationProvider->get('Accept')->getValue());
        static::assertSame('application/xml', $request->getHeaderLine('Accept'));
    }
}
