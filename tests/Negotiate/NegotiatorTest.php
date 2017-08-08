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

namespace Jgut\Negotiate\Tests;

use Jgut\Negotiate\Exception;
use Jgut\Negotiate\Negotiator;
use Jgut\Negotiate\Scope\AbstractScope;
use Jgut\Negotiate\Tests\Stub\AcceptStub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Negotiator middleware tests.
 */
class NegotiatorTest extends TestCase
{
    public function testNotAcceptable()
    {
        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var ServerRequestInterface $request */

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $response->expects($this->once())
            ->method('withStatus')
            ->with(406)
            ->will($this->returnSelf());
        /* @var ResponseInterface $response */

        $next = function ($request, $response) {
            return $response;
        };

        $scope = $this->getMockBuilder(AbstractScope::class)
            ->disableOriginalConstructor()
            ->getMock();
        $scope->expects($this->once())
            ->method('getAccept')
            ->will($this->throwException(new Exception()));
        /* @var \Jgut\Negotiate\Scope\ScopeInterface $scope */

        $middleware = new Negotiator(['accept' => $scope]);

        $middleware($request, $response, $next);
    }

    public function testAttribute()
    {
        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())
            ->method('withAttribute')
            ->with('negotiated')
            ->will($this->returnSelf());
        /* @var ServerRequestInterface $request */

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var ResponseInterface $response */

        $next = function ($request, $response) {
            return $response;
        };

        $scope = $this->getMockBuilder(AbstractScope::class)
            ->disableOriginalConstructor()
            ->getMock();
        $scope->expects($this->once())
            ->method('getAccept')
            ->will($this->returnValue(new AcceptStub('application/json')));
        /* @var \Jgut\Negotiate\Scope\ScopeInterface $scope */

        $middleware = new Negotiator(['accept' => $scope]);
        $middleware->setAttributeName('negotiated');

        $middleware($request, $response, $next);
    }
}
