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
 * Abstract scope tests.
 */
class AbstractScopeTest extends TestCase
{
    public function testPriorityList(): void
    {
        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var Negotiator $negotiator */

        $scope = new ScopeStub('accept', ['one', 'two'], $negotiator);

        static::assertEquals(['one', 'two'], $scope->getPriorityList());

        $scope->prependPriority('zero');
        $scope->appendPriority('last');

        static::assertEquals(['zero', 'one', 'two', 'last'], $scope->getPriorityList());
    }

    public function testNoAccept(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('"accept" header refused');

        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var Negotiator $negotiator */

        $scope = new ScopeStub('accept', [], $negotiator);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->will(static::returnValue(''));
        /* @var ServerRequestInterface $request */

        $scope->getAccept($request);
    }

    public function testDefaultAccept(): void
    {
        $accept = $this->getMockBuilder(AcceptHeader::class)
            ->getMock();

        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $negotiator->expects(static::once())
            ->method('getBest')
            ->will(static::returnValue(null));
        /* @var Negotiator $negotiator */

        $scope = new ScopeStub('accept', [], $negotiator, $accept, true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->will(static::returnValue('application/json'));
        /* @var ServerRequestInterface $request */

        static::assertEquals($accept, $scope->getAccept($request));
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
            ->will(static::returnValue($accept));
        /* @var Negotiator $negotiator */

        $scope = new ScopeStub('accept', [], $negotiator);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects(static::once())
            ->method('getHeaderLine')
            ->will(static::returnValue('application/json'));
        /* @var ServerRequestInterface $request */

        static::assertEquals($accept, $scope->getAccept($request));
    }
}
