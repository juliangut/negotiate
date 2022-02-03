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

use Jgut\Negotiate\Scope\ContentType;
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

    protected ResponseFactoryInterface $responseFactory;

    /**
     * @param array<ScopeInterface> $scopes
     */
    public function __construct(array $scopes, ResponseFactoryInterface $responseFactory)
    {
        $this->setScopes($scopes);

        $this->responseFactory = $responseFactory;
    }

    /**
     * Set negotiation scopes.
     *
     * @param array<ScopeInterface> $scopes
     */
    public function setScopes(array $scopes): void
    {
        $this->scopes = [];

        foreach ($scopes as $scope) {
            $this->setScope($scope);
        }
    }

    /**
     * Set negotiation scope.
     */
    public function setScope(ScopeInterface $scope): void
    {
        $name = str_replace(' ', '', ucwords(str_replace('-', ' ', $scope->getHeaderName())));

        $this->scopes[$name] = $scope;
    }

    /**
     * Set request attribute name.
     */
    final public function setAttributeName(string $attributeName): void
    {
        $this->attributeName = $attributeName;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $negotiated = [];

        foreach ($this->scopes as $name => $scope) {
            try {
                $negotiated[$name] = $scope->getAccept($request);
            } catch (Exception $exception) {
                if ($scope instanceof ContentType) {
                    return $this->responseFactory->createResponse(415);
                }

                return $this->responseFactory->createResponse(406);
            }
        }

        return $handler->handle($request->withAttribute($this->attributeName, new Provider($negotiated)));
    }
}
