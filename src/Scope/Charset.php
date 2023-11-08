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

use Negotiation\AbstractNegotiator;
use Negotiation\AcceptCharset;
use Negotiation\BaseAccept;
use Negotiation\CharsetNegotiator;

final class Charset extends AbstractScope
{
    /**
     * @param list<string> $priorityList
     */
    public function __construct(
        array $priorityList,
        private ?string $default = null,
    ) {
        parent::__construct($priorityList);
    }

    public function getHeaderName(): string
    {
        return 'Accept-Charset';
    }

    protected function getNegotiator(): AbstractNegotiator
    {
        return new CharsetNegotiator();
    }

    protected function getDefaultAccept(): ?BaseAccept
    {
        return $this->default !== null ? new AcceptCharset($this->default) : null;
    }
}
