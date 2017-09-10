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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Negotiator middleware.
 */
class Negotiator
{
    /**
     * List of negotiation scopes.
     *
     * @var ScopeInterface[]
     */
    protected $scopes = [];

    /**
     * Request attribute name.
     *
     * @var string
     */
    protected $attributeName = 'negotiation';

    /**
     * Negotiator constructor.
     *
     * @param ScopeInterface[] $scopes
     */
    public function __construct(array $scopes)
    {
        foreach ($scopes as $name => $scope) {
            $this->addScope($name, $scope);
        }
    }

    /**
     * Add negotiation scope.
     *
     * @param string         $name
     * @param ScopeInterface $scope
     */
    protected function addScope(string $name, ScopeInterface $scope)
    {
        $this->scopes[$name] = $scope;
    }

    /**
     * Set request attribute name.
     *
     * @param string $attributeName
     */
    final public function setAttributeName(string $attributeName)
    {
        $this->attributeName = $attributeName;
    }

    /**
     * Negotiate request.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @throws Exception
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface {
        $provider = new Provider();

        foreach ($this->scopes as $name => $scope) {
            try {
                $provider->addAccept($name, $scope->getAccept($request));
            } catch (Exception $exception) {
                if ($scope instanceof ContentType) {
                    return $response->withStatus(415);
                }

                return $response->withStatus(406);
            }
        }

        return $next($request->withAttribute($this->attributeName, $provider), $response);
    }
}
