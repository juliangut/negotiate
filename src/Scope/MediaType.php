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
 * Media type scope.
 */
class MediaType extends AbstractScope
{
    /**
     * MediaType scope constructor.
     *
     * @param string[] $priorityList
     * @param bool     $useDefaults
     */
    public function __construct(array $priorityList, bool $useDefaults = true)
    {
        parent::__construct($priorityList, new Negotiator(), $useDefaults);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderName(): string
    {
        return 'accept';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return new Accept(\implode(';', $this->priorityList));
    }
}
