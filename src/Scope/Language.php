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

use Negotiation\AcceptHeader;
use Negotiation\AcceptLanguage;
use Negotiation\LanguageNegotiator;

/**
 * Language scope.
 */
class Language extends AbstractScope
{
    /**
     * Language scope constructor.
     *
     * @param array              $priorityList
     * @param LanguageNegotiator $negotiator
     * @param bool               $useDefaults
     */
    public function __construct(array $priorityList, LanguageNegotiator $negotiator, bool $useDefaults = true)
    {
        parent::__construct('accept-language', $priorityList, $negotiator, $useDefaults);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return new AcceptLanguage(implode(';', $this->priorityList));
    }
}
