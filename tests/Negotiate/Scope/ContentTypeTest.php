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

use Jgut\Negotiate\Scope\ContentType;
use Negotiation\Accept;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @internal
 */
class ContentTypeTest extends TestCase
{
    public function testDefaultAccept(): void
    {
        $scope = new ContentType(['text/html'], true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->willReturn('application/json');

        /** @var Accept $accept */
        $accept = $scope->getAccept($request);

        static::assertInstanceOf(Accept::class, $accept);
        static::assertSame('text/html', $accept->getValue());
    }
}
