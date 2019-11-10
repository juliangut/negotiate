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

use Jgut\Negotiate\Scope\Encoding;
use Negotiation\AcceptEncoding;
use Negotiation\EncodingNegotiator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Encoding scope tests.
 */
class EncodingTest extends TestCase
{
    public function testDefaultAccept(): void
    {
        $negotiator = $this->getMockBuilder(EncodingNegotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $negotiator->expects(static::once())
            ->method('getBest')
            ->will(static::returnValue(null));
        /* @var EncodingNegotiator $negotiator */

        $scope = new Encoding(['gzip'], $negotiator, true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->will(static::returnValue('application/json'));
        /* @var ServerRequestInterface $request */

        static::assertInstanceOf(AcceptEncoding::class, $scope->getAccept($request));
    }
}
