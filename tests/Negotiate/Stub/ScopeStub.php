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
use Negotiation\AcceptHeader;

class ScopeStub extends AbstractScope
{
    protected string $headerName;

    protected ?AcceptHeader $defaultAccept;

    /**
     * ScopeStub constructor.
     *
     * @param array<string> $priorityList
     */
    public function __construct(
        string $headerName,
        array $priorityList,
        AbstractNegotiator $negotiator,
        ?AcceptHeader $defaultAccept = null,
        bool $useDefaults = false
    ) {
        parent::__construct($priorityList, $negotiator, $useDefaults);

        $this->headerName = $headerName;
        $this->defaultAccept = $defaultAccept;
    }

    /**
     * @return array<mixed>
     */
    public function getPriorityList(): array
    {
        return $this->priorityList;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return $this->defaultAccept ?? new Accept('empty');
    }

    /**
     * @inheritDoc
     */
    public function getHeaderName(): string
    {
        return $this->headerName;
    }
}
