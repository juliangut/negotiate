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

use Jgut\Negotiate\Negotiator;
use Jgut\Negotiate\Provider;
use Jgut\Negotiate\Scope\ContentType;
use Jgut\Negotiate\Scope\Language;
use Jgut\Negotiate\Scope\MediaType;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Negotiation\Accept;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @internal
 */
class NegotiatorTest extends TestCase
{
    protected RequestHandlerInterface $requestHandler;

    protected function setUp(): void
    {
        $this->requestHandler = new class () implements RequestHandlerInterface {
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $response = new Response('php://temp');
                $response->getBody()
                    ->write($request->getMethod());

                return $response;
            }
        };
    }

    public function testUnsupportedMediaType(): void
    {
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

        $scope = new ContentType(['text/html']);

        $middleware = new Negotiator([$scope], $responseFactory);

        $request = (new ServerRequest())
            ->withAddedHeader('Content-Type', 'application/json');

        $middleware->process($request, $this->requestHandler);
    }

    public function testNotAcceptable(): void
    {
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

        $scope = new Language(['es']);

        $middleware = new Negotiator([$scope], $responseFactory);

        $request = (new ServerRequest())
            ->withAddedHeader('Accept-Language', 'en');

        $middleware->process($request, $this->requestHandler);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function testRequestModify(): void
    {
        $responseFactory = $this->getMockBuilder(ResponseFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $scope = new MediaType(['application/json']);

        $middleware = new Negotiator([$scope], $responseFactory);
        $middleware->setAttributeName('negotiated');

        $requestHandler = new class ($this) implements RequestHandlerInterface {
            public function __construct(
                private TestCase $phpunitAssert,
            ) {}

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $negotiationProvider = $request->getAttribute('negotiated');
                $this->phpunitAssert::assertInstanceOf(Provider::class, $negotiationProvider);

                $negotiationAccept = $negotiationProvider->get('Accept');
                $this->phpunitAssert::assertInstanceOf(Accept::class, $negotiationAccept);
                $this->phpunitAssert::assertSame('application/json', $negotiationAccept->getValue());
                $this->phpunitAssert::assertSame('application/json', $request->getHeaderLine('Accept'));

                $response = new Response('php://temp');
                $response->getBody()
                    ->write($request->getMethod());

                return $response;
            }
        };

        $request = (new ServerRequest())
            ->withAddedHeader('Accept', 'application/json');

        $middleware->process($request, $requestHandler);
    }
}
