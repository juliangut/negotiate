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

class Charset extends AbstractScope
{
    /**
     * @param array<string> $priorityList
     */
    public function __construct(array $priorityList, bool $useDefaults = true)
    {
        parent::__construct($priorityList, new CharsetNegotiator(), $useDefaults);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderName(): string
    {
        return 'accept-charset';
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return new AcceptCharset(implode(';', $this->priorityList));
    }
}
