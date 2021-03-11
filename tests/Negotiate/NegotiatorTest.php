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
use Jgut\Negotiate\Scope\ContentType;
use Jgut\Negotiate\Tests\Stub\AcceptStub;
use Laminas\Diactoros\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Negotiator middleware tests.
 */
class NegotiatorTest extends TestCase
{
    /**
     * @var RequestHandlerInterface
     */
    protected $requestHandler;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $this->requestHandler = new class() implements RequestHandlerInterface {
            /**
             * {@inheritdoc}
             */
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $response = new Response('php://temp');
                $response->getBody()->write($request->getMethod());

                return $response;
            }
        };
    }

    public function testUnsupportedMediaType(): void
    {
        $scope = $this->getMockBuilder(ContentType::class)
            ->disableOriginalConstructor()
            ->getMock();
        $scope->expects(static::once())
            ->method('getAccept')
            ->willThrowException(new Exception());

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $responseFactory = $this->getMockBuilder(ResponseFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $responseFactory->expects(static::once())
            ->method('createResponse')
            ->with(415)
            ->willReturn($response);
        $middleware = new Negotiator([$scope], $responseFactory);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $middleware->process($request, $this->requestHandler);
    }

    public function testNotAcceptable(): void
    {
        $scope = $this->getMockBuilder(AbstractScope::class)
            ->disableOriginalConstructor()
            ->getMock();
        $scope->expects(static::once())
            ->method('getAccept')
            ->willThrowException(new Exception());

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $responseFactory = $this->getMockBuilder(ResponseFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $responseFactory->expects(static::once())
            ->method('createResponse')
            ->with(406)
            ->willReturn($response);
        $middleware = new Negotiator([$scope], $responseFactory);

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $middleware->process($request, $this->requestHandler);
    }

    public function testAttribute(): void
    {
        $scope = $this->getMockBuilder(AbstractScope::class)
            ->disableOriginalConstructor()
            ->getMock();
        $scope->expects(static::once())
            ->method('getAccept')
            ->willReturn(new AcceptStub('application/json'));
        $scope->expects(static::any())
            ->method('getHeaderName')
            ->willReturn('accept');

        $responseFactory = $this->getMockBuilder(ResponseFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $middleware = new Negotiator([$scope], $responseFactory);
        $middleware->setAttributeName('negotiated');

        $request = $this->getMockBuilder(ServerRequestInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects(static::once())
            ->method('withAttribute')
            ->with('negotiated')
            ->willReturnSelf();
        $request->expects(static::once())
            ->method('getMethod')
            ->willReturn('GET');

        $middleware->process($request, $this->requestHandler);
    }
}
