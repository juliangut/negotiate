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

use Negotiation\AcceptEncoding;
use Negotiation\AcceptHeader;
use Negotiation\EncodingNegotiator;

/**
 * Encoding scope.
 */
class Encoding extends AbstractScope
{
    /**
     * Encoding scope constructor.
     *
     * @param string[] $priorityList
     * @param bool     $useDefaults
     */
    public function __construct(array $priorityList, bool $useDefaults = true)
    {
        parent::__construct($priorityList, new EncodingNegotiator(), $useDefaults);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderName(): string
    {
        return 'accept-encoding';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return new AcceptEncoding(\implode(';', $this->priorityList));
    }
}
