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
     * @param string[] $priorityList
     * @param bool     $useDefaults
     */
    public function __construct(array $priorityList, bool $useDefaults = true)
    {
        parent::__construct($priorityList, new LanguageNegotiator(), $useDefaults);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderName(): string
    {
        return 'accept-language';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAccept(): AcceptHeader
    {
        return new AcceptLanguage(\implode(';', $this->priorityList));
    }
}
