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
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Encoding scope tests.
 */
class EncodingTest extends TestCase
{
    public function testDefaultAccept(): void
    {
        $scope = new Encoding(['gzip'], true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->willReturn('application/json');

        /** @var AcceptEncoding $accept */
        $accept = $scope->getAccept($request);

        static::assertInstanceOf(AcceptEncoding::class, $accept);
        static::assertSame('gzip', $accept->getValue());
    }
}
