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
use Negotiation\BaseAccept;
use Negotiation\Negotiator;

final class ContentType extends AbstractScope
{
    /**
     * @param list<string> $priorityList
     */
    public function __construct(array $priorityList, bool $useDefaults = false)
    {
        parent::__construct($priorityList, new Negotiator(), $useDefaults);
    }

    public function getHeaderName(): string
    {
        return 'Content-Type';
    }

    protected function getDefaultAccept(): BaseAccept
    {
        return new Accept(implode(';', $this->priorityList));
    }
}
