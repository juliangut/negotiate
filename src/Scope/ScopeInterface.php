<?php

/*
 * (c) 2017-2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
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
