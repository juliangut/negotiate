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

/**
 * Scope stub.
 */
class ScopeStub extends AbstractScope
{
    /**
     * @var string
     */
    protected $headerName;

    /**
     * @var AcceptHeader|null
     */
    protected $defaultAccept;

    /**
     * ScopeStub constructor.
     *
     * @param string             $headerName
     * @param array<string>      $priorityList
     * @param AbstractNegotiator $negotiator
     * @param AcceptHeader|null  $defaultAccept
     * @param bool               $useDefaults
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
     * @return mixed[]
     */
    public function getPriorityList(): array
    {
        return $this->priorityList;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return $this->defaultAccept ?? new Accept('empty');
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderName(): string
    {
        return $this->headerName;
    }
}
