<?php

/*
 * (c) 2017-2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 */

declare(strict_types=1);

namespace Jgut\Negotiate\Scope;

use Negotiation\AbstractNegotiator;
use Negotiation\AcceptEncoding;
use Negotiation\BaseAccept;
use Negotiation\EncodingNegotiator;

final class Encoding extends AbstractScope
{
    /**
     * @param list<string> $priorityList
     */
    public function __construct(
        array $priorityList,
        private ?string $default = null,
    ) {
        parent::__construct($priorityList);
    }

    public function getHeaderName(): string
    {
        return 'Accept-Encoding';
    }

    protected function getNegotiator(): AbstractNegotiator
    {
        return new EncodingNegotiator();
    }

    protected function getDefaultAccept(): ?BaseAccept
    {
        return $this->default !== null ? new AcceptEncoding($this->default) : null;
    }
}
