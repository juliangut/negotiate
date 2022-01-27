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

use Jgut\Negotiate\Exception;
use Negotiation\AcceptHeader;
use Psr\Http\Message\ServerRequestInterface;

interface ScopeInterface
{
    /**
     * Get negotiated accept header.
     *
     * @throws Exception
     */
    public function getAccept(ServerRequestInterface $request): AcceptHeader;

    /**
     * Get handled header name.
     */
    public function getHeaderName(): string;
}
