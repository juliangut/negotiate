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
use Jgut\Negotiate\Scope\Encoding;
use Laminas\Diactoros\ServerRequest;
use Negotiation\AcceptEncoding;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class EncodingTest extends TestCase
{
    public function testNegotiationMissing(): void
    {
        $this->expectException(NegotiatorException::class);
        $this->expectExceptionMessage('"Accept-Encoding" header missing or refused.');

        $scope = new Encoding(['gzip']);

        $scope->negotiateRequest(new ServerRequest(), 'provider');
    }

    public function testNegotiationFailure(): void
    {
        $this->expectException(NegotiatorException::class);
        $this->expectExceptionMessage('"Accept-Encoding" header missing or refused.');

        $scope = new Encoding(['gzip']);

        $request = (new ServerRequest())
            ->withAddedHeader('Accept-Encoding', 'deflate');

        $scope->negotiateRequest($request, 'provider');
    }

    public function testNegotiationDefault(): void
    {
        $scope = new Encoding(['gzip'], 'gzip');

        $request = (new ServerRequest())
            ->withAddedHeader('Accept-Encoding', 'deflate');

        $request = $scope->negotiateRequest($request, 'provider');

        $negotiationProvider = $request->getAttribute('provider');
        static::assertInstanceOf(Provider::class, $negotiationProvider);
        static::assertInstanceOf(AcceptEncoding::class, $negotiationProvider->get('Accept-Encoding'));
        static::assertSame('gzip', $negotiationProvider->get('Accept-Encoding')->getValue());
        static::assertSame('gzip', $request->getHeaderLine('Accept-Encoding'));
    }

    public function testNegotiationSuccess(): void
    {
        $scope = new Encoding(['deflate'], 'gzip');

        $request = (new ServerRequest())
            ->withAddedHeader('Accept-Encoding', 'deflate');

        $request = $scope->negotiateRequest($request, 'provider');

        $negotiationProvider = $request->getAttribute('provider');
        static::assertInstanceOf(Provider::class, $negotiationProvider);
        static::assertInstanceOf(AcceptEncoding::class, $negotiationProvider->get('Accept-Encoding'));
        static::assertSame('deflate', $negotiationProvider->get('Accept-Encoding')->getValue());
        static::assertSame('deflate', $request->getHeaderLine('Accept-Encoding'));
    }
}
