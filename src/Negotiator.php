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

/**
 * Negotiator middleware.
 */
class Negotiator implements MiddlewareInterface
{
    /**
     * Request attribute name.
     *
     * @var string
     */
    protected $attributeName = 'negotiation';

    /**
     * List of negotiation scopes.
     *
     * @var ScopeInterface[]
     */
    protected $scopes = [];

    /**
     * @var ResponseFactoryInterface
     */
    protected $responseFactory;

    /**
     * Negotiator constructor.
     *
     * @param ScopeInterface[]         $scopes
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(array $scopes, ResponseFactoryInterface $responseFactory)
    {
        foreach ($scopes as $name => $scope) {
            $this->addScope($name, $scope);
        }

        $this->responseFactory = $responseFactory;
    }

    /**
     * Add negotiation scope.
     *
     * @param string         $name
     * @param ScopeInterface $scope
     */
    protected function addScope(string $name, ScopeInterface $scope): void
    {
        $this->scopes[$name] = $scope;
    }

    /**
     * Set request attribute name.
     *
     * @param string $attributeName
     */
    final public function setAttributeName(string $attributeName): void
    {
        $this->attributeName = $attributeName;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $provider = new Provider();

        foreach ($this->scopes as $name => $scope) {
            try {
                $provider->addAccept($name, $scope->getAccept($request));
            } catch (Exception $exception) {
                if ($scope instanceof ContentType) {
                    return $this->responseFactory->createResponse(415);
                }

                return $this->responseFactory->createResponse(406);
            }
        }

        return $handler->handle($request->withAttribute($this->attributeName, $provider));
    }
}
