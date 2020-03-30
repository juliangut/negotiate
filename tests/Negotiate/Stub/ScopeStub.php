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
use Negotiation\AcceptHeader;

/**
 * Scope stub.
 */
class ScopeStub extends AbstractScope
{
    protected $headerName;

    /**
     * @var AcceptHeader
     */
    protected $defaultAccept;

    /**
     * ScopeStub constructor.
     *
     * @param string             $headerName
     * @param array              $priorityList
     * @param AbstractNegotiator $negotiator
     * @param AcceptHeader       $defaultAccept
     * @param bool               $useDefaults
     */
    public function __construct(
        string $headerName,
        array $priorityList,
        AbstractNegotiator $negotiator,
        AcceptHeader $defaultAccept = null,
        bool $useDefaults = false
    ) {
        parent::__construct($priorityList, $negotiator, $useDefaults);

        $this->headerName = $headerName;
        $this->defaultAccept = $defaultAccept;
    }

    /**
     * @return array
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
        return $this->defaultAccept;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderName(): string
    {
        return $this->headerName;
    }
}
