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
use Negotiation\Accept;
use Negotiation\BaseAccept;
use Negotiation\Negotiator;

final class MediaType extends AbstractScope
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
        return 'Accept';
    }

    protected function getNegotiator(): AbstractNegotiator
    {
        return new Negotiator();
    }

    protected function getDefaultAccept(): ?BaseAccept
    {
        return $this->default !== null ? new Accept($this->default) : null;
    }
}
