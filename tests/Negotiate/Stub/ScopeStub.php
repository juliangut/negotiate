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
    /**
     * @var AcceptHeader
     */
    protected $defaultAccept;

    /**
     * ScopeStub constructor.
     *
     * @param string             $header
     * @param array              $priorityList
     * @param AbstractNegotiator $negotiator
     * @param AcceptHeader       $defaultAccept
     * @param bool               $useDefaults
     */
    public function __construct(
        string $header,
        array $priorityList,
        AbstractNegotiator $negotiator,
        AcceptHeader $defaultAccept = null,
        bool $useDefaults = false
    ) {
        parent::__construct($header, $priorityList, $negotiator, $useDefaults);

        $this->defaultAccept = $defaultAccept;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return $this->defaultAccept;
    }
}
