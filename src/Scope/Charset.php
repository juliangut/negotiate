<?php

/*
 * (c) 2017-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 */

declare(strict_types=1);

namespace Jgut\Negotiate\Scope;

use Negotiation\AbstractNegotiator;
use Negotiation\AcceptCharset;
use Negotiation\BaseAccept;
use Negotiation\CharsetNegotiator;

final class Charset extends AbstractScope
{
    public function getHeaderName(): string
    {
        return 'Accept-Charset';
    }

    protected function getNegotiator(): AbstractNegotiator
    {
        return new CharsetNegotiator();
    }

    protected function getDefaultAccept(): ?BaseAccept
    {
        return $this->default !== null ? new AcceptCharset($this->default) : null;
    }
}
