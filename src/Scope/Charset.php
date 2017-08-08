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

use Negotiation\AcceptCharset;
use Negotiation\AcceptHeader;
use Negotiation\CharsetNegotiator;

/**
 * Character set scope.
 */
class Charset extends AbstractScope
{
    /**
     * Charset scope constructor.
     *
     * @param array             $priorityList
     * @param CharsetNegotiator $negotiator
     * @param bool              $useDefaults
     */
    public function __construct(array $priorityList, CharsetNegotiator $negotiator, bool $useDefaults = true)
    {
        parent::__construct('accept-charset', $priorityList, $negotiator, $useDefaults);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return new AcceptCharset(implode(';', $this->priorityList));
    }
}
