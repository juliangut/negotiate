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
use Negotiation\Accept;
use Negotiation\AcceptCharset;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Character set scope tests.
 */
class CharsetTest extends TestCase
{
    public function testDefaultAccept(): void
    {
        $scope = new Charset(['utf-8'], true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->willReturn('application/json');

        /** @var Accept $accept */
        $accept = $scope->getAccept($request);

        static::assertInstanceOf(AcceptCharset::class, $accept);
        static::assertSame('utf-8', $accept->getValue());
    }
}
