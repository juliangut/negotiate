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
use Negotiation\BaseAccept;
use Negotiation\EncodingNegotiator;

final class Encoding extends AbstractScope
{
    /**
     * @param list<string> $priorityList
     */
    public function __construct(array $priorityList, bool $useDefaults = true)
    {
        parent::__construct($priorityList, new EncodingNegotiator(), $useDefaults);
    }

    public function getHeaderName(): string
    {
        return 'Accept-Encoding';
    }

    protected function getDefaultAccept(): BaseAccept
    {
        return new AcceptEncoding(implode(';', $this->priorityList));
    }
}
