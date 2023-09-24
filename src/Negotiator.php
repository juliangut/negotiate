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

namespace Jgut\Negotiate;

use Jgut\Negotiate\Scope\ScopeInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Negotiator implements MiddlewareInterface
{
    protected string $attributeName = 'negotiation';

    /**
     * @var array<string, ScopeInterface>
     */
    protected array $scopes = [];

    /**
     * @param list<ScopeInterface> $scopes
     */
    public function __construct(
        array $scopes,
        protected readonly ResponseFactoryInterface $responseFactory,
    ) {
        $this->setScopes($scopes);
    }

    /**
     * @param list<ScopeInterface> $scopes
     */
    public function setScopes(array $scopes): void
    {
        $this->scopes = [];

        foreach ($scopes as $scope) {
            $this->setScope($scope);
        }
    }

    public function setScope(ScopeInterface $scope): void
    {
        $name = str_replace(' ', '', ucwords(str_replace('-', ' ', mb_strtolower($scope->getHeaderName()))));

        $this->scopes[$name] = $scope;
    }

    final public function setAttributeName(string $attributeName): void
    {
        $this->attributeName = $attributeName;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $negotiated = [];

        foreach ($this->scopes as $name => $scope) {
            try {
                $negotiated[$name] = $scope->getAccept($request);
            } catch (NegotiatorException) {
                if (mb_strtolower($scope->getHeaderName()) === 'content-type') {
                    return $this->responseFactory->createResponse(415);
                }

                return $this->responseFactory->createResponse(406);
            }

            $request = $request->withAddedHeader($scope->getHeaderName(), $negotiated[$name]->getValue());
        }

        $request = $request->withAttribute($this->attributeName, new Provider($negotiated));

        return $handler->handle($request);
    }
}
