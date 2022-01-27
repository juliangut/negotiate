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

use Jgut\Negotiate\Exception;
use Jgut\Negotiate\Tests\Stub\ScopeStub;
use Negotiation\AcceptHeader;
use Negotiation\Negotiator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @internal
 */
class AbstractScopeTest extends TestCase
{
    public function testPriorityList(): void
    {
        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $scope = new ScopeStub('accept', ['one', 'two'], $negotiator);

        static::assertSame(['one', 'two'], $scope->getPriorityList());

        $scope->prependPriority('zero');
        $scope->appendPriority('last');

        static::assertSame(['zero', 'one', 'two', 'last'], $scope->getPriorityList());
    }

    public function testNoAccept(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('"accept" header refused.');

        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $scope = new ScopeStub('accept', [], $negotiator);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->willReturn('');

        $scope->getAccept($request);
    }

    public function testDefaultAccept(): void
    {
        $accept = $this->getMockBuilder(AcceptHeader::class)
            ->getMock();

        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $scope = new ScopeStub('accept', [], $negotiator, $accept, true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->willReturn('application/json');

        static::assertSame($accept, $scope->getAccept($request));
    }

    public function testNegotiated(): void
    {
        $accept = $this->getMockBuilder(AcceptHeader::class)
            ->getMock();

        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $negotiator->expects(static::once())
            ->method('getBest')
            ->willReturn($accept);

        $scope = new ScopeStub('accept', ['application/json'], $negotiator);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->willReturn('application/json');

        static::assertSame($accept, $scope->getAccept($request));
    }
}
