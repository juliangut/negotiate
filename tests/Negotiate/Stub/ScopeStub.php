<?php

/*
 * (c) 2017-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 */

declare(strict_types=1);

namespace Jgut\Negotiate\Tests\Stub;

use Jgut\Negotiate\Scope\AbstractScope;
use Negotiation\AbstractNegotiator;
use Negotiation\BaseAccept;

/**
 * @internal
 */
final class ScopeStub extends AbstractScope
{
    /**
     * @param list<string> $priorityList
     */
    public function __construct(
        protected string $headerName,
        array $priorityList,
        private AbstractNegotiator $negotiator,
        protected ?BaseAccept $defaultAccept = null,
    ) {
        parent::__construct($priorityList);
    }

    /**
     * @return list<string>
     */
    public function getPriorityList(): array
    {
        return $this->priorityList;
    }

    public function getHeaderName(): string
    {
        return $this->headerName;
    }

    protected function getNegotiator(): AbstractNegotiator
    {
        return $this->negotiator;
    }

    protected function getDefaultAccept(): ?BaseAccept
    {
        return $this->defaultAccept;
    }
}
