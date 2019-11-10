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

use Jgut\Negotiate\Scope\Charset;
use Negotiation\AcceptCharset;
use Negotiation\CharsetNegotiator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Character set scope tests.
 */
class CharsetTest extends TestCase
{
    public function testDefaultAccept(): void
    {
        $negotiator = $this->getMockBuilder(CharsetNegotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $negotiator->expects(static::once())
            ->method('getBest')
            ->will(static::returnValue(null));
        /* @var CharsetNegotiator $negotiator */

        $scope = new Charset(['utf-8'], $negotiator, true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->will(static::returnValue('application/json'));
        /* @var ServerRequestInterface $request */

        static::assertInstanceOf(AcceptCharset::class, $scope->getAccept($request));
    }
}
