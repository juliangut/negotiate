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
use Negotiation\BaseAccept;
use Psr\Http\Message\ServerRequestInterface;

interface ScopeInterface
{
    /**
     * Get negotiated header.
     *
     * @throws NegotiatorException
     */
    public function getAccept(ServerRequestInterface $request): BaseAccept;

    /**
     * Get handled header name.
     */
    public function getHeaderName(): string;
}
