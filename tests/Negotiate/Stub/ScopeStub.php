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

namespace Jgut\Negotiate\Tests\Stub;

use Jgut\Negotiate\Scope\AbstractScope;
use Negotiation\AbstractNegotiator;
use Negotiation\Accept;
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
        AbstractNegotiator $negotiator,
        protected ?BaseAccept $defaultAccept = null,
        bool $useDefaults = false,
    ) {
        parent::__construct($priorityList, $negotiator, $useDefaults);
    }

    /**
     * @return array<mixed>
     */
    public function getPriorityList(): array
    {
        return $this->priorityList;
    }

    protected function getDefaultAccept(): BaseAccept
    {
        return $this->defaultAccept ?? new Accept('empty');
    }

    public function getHeaderName(): string
    {
        return $this->headerName;
    }
}
