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

class ContentType extends AbstractScope
{
    /**
     * @param array<string> $priorityList
     */
    public function __construct(array $priorityList, bool $useDefaults = false)
    {
        parent::__construct($priorityList, new Negotiator(), $useDefaults);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderName(): string
    {
        return 'content-type';
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return new Accept(implode(';', $this->priorityList));
    }
}
