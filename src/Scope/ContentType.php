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

namespace Jgut\Negotiate\Scope;

use Negotiation\Accept;
use Negotiation\AcceptHeader;
use Negotiation\Negotiator;

/**
 * Content type scope.
 */
class ContentType extends AbstractScope
{
    /**
     * MediaType scope constructor.
     *
     * @param array      $priorityList
     * @param Negotiator $negotiator
     * @param bool       $useDefaults
     */
    public function __construct(array $priorityList, Negotiator $negotiator, bool $useDefaults = false)
    {
        parent::__construct('content-type', $priorityList, $negotiator, $useDefaults);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return new Accept(implode(';', $this->priorityList));
    }
}
