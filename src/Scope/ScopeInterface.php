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

namespace Jgut\Negotiate\Scope;

use Jgut\Negotiate\NegotiatorException;
use Psr\Http\Message\ServerRequestInterface;

interface ScopeInterface
{
    /**
     * @throws NegotiatorException
     */
    public function negotiateRequest(ServerRequestInterface $request, string $attributeName): ServerRequestInterface;

    public function getHeaderName(): string;
}
