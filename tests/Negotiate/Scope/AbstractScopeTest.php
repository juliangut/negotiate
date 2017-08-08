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
    /**
     * @expectedException \Jgut\Negotiate\Exception
     * @expectedExceptionMessage "accept" header refused
     */
    public function testNoAccept()
    {
        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var Negotiator $negotiator */

        $scope = new ScopeStub('accept', [], $negotiator);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects($this->once())
            ->method('getHeaderLine')
            ->will($this->returnValue(''));
        /* @var ServerRequestInterface $request */

        $scope->getAccept($request);
    }

    public function testDefaultAccept()
    {
        $accept = $this->getMockBuilder(AcceptHeader::class)
            ->getMock();

        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $negotiator->expects($this->once())
            ->method('getBest')
            ->will($this->returnValue(null));
        /* @var Negotiator $negotiator */

        $scope = new ScopeStub('accept', [], $negotiator, $accept, true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects($this->once())
            ->method('getHeaderLine')
            ->will($this->returnValue('application/json'));
        /* @var ServerRequestInterface $request */

        $this->assertEquals($accept, $scope->getAccept($request));
    }

    public function testNegotiated()
    {
        $accept = $this->getMockBuilder(AcceptHeader::class)
            ->getMock();

        $negotiator = $this->getMockBuilder(Negotiator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $negotiator->expects($this->once())
            ->method('getBest')
            ->will($this->returnValue($accept));
        /* @var Negotiator $negotiator */

        $scope = new ScopeStub('accept', [], $negotiator);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->getMock();
        $request->expects($this->once())
            ->method('getHeaderLine')
            ->will($this->returnValue('application/json'));
        /* @var ServerRequestInterface $request */

        $this->assertEquals($accept, $scope->getAccept($request));
    }
}
