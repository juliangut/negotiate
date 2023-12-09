<?php

/*
 * (c) 2017-2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 */

declare(strict_types=1);

namespace Jgut\Negotiate\Tests\Scope;

use Jgut\Negotiate\NegotiatorException;
use Jgut\Negotiate\Provider;
use Jgut\Negotiate\Scope\Charset;
use Laminas\Diactoros\ServerRequest;
use Negotiation\AcceptCharset;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class CharsetTest extends TestCase
{
    public function testNegotiationFailure(): void
    {
        $this->expectException(NegotiatorException::class);
        $this->expectExceptionMessage('"Accept-Charset" header refused');

        $scope = new Charset(['utf-8']);

        $request = (new ServerRequest())
            ->withAddedHeader('Accept-Charset', 'iso-8601');

        $scope->negotiateRequest($request, 'provider');
    }

    public function testNegotiationDefault(): void
    {
        $scope = new Charset(['utf-8'], 'utf-8');

        $request = (new ServerRequest())
            ->withAddedHeader('Accept-Charset', 'iso-8859-1');

        $request = $scope->negotiateRequest($request, 'provider');

        $negotiationProvider = $request->getAttribute('provider');
        static::assertInstanceOf(Provider::class, $negotiationProvider);
        static::assertInstanceOf(AcceptCharset::class, $negotiationProvider->get('Accept-Charset'));
        static::assertSame('utf-8', $negotiationProvider->get('Accept-Charset')->getValue());
        static::assertSame('utf-8', $request->getHeaderLine('Accept-Charset'));
    }

    public function testNegotiationSuccess(): void
    {
        $scope = new Charset(['iso-8859-1'], 'utf-8');

        $request = (new ServerRequest())
            ->withAddedHeader('Accept-Charset', 'iso-8859-1');

        $request = $scope->negotiateRequest($request, 'provider');

        $negotiationProvider = $request->getAttribute('provider');
        static::assertInstanceOf(Provider::class, $negotiationProvider);
        static::assertInstanceOf(AcceptCharset::class, $negotiationProvider->get('Accept-Charset'));
        static::assertSame('iso-8859-1', $negotiationProvider->get('Accept-Charset')->getValue());
        static::assertSame('iso-8859-1', $request->getHeaderLine('Accept-Charset'));
    }
}
