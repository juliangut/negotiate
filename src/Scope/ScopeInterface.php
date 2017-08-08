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

use Negotiation\AcceptHeader;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Scope interface.
 */
interface ScopeInterface
{
    /**
     * Get negotiated accept header.
     *
     * @param ServerRequestInterface $request
     *
     * @return AcceptHeader
     */
    public function getAccept(ServerRequestInterface $request): AcceptHeader;
}
