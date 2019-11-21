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
     * @param string[] $priorityList
     * @param bool     $useDefaults
     */
    public function __construct(array $priorityList, bool $useDefaults = true)
    {
        parent::__construct($priorityList, new CharsetNegotiator(), $useDefaults);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderName(): string
    {
        return 'accept-charset';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return new AcceptCharset(\implode(';', $this->priorityList));
    }
}
