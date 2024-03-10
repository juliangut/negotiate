<?php

/*
 * (c) 2017-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/negotiate
 */

declare(strict_types=1);

namespace Jgut\Negotiate\Scope;

use Negotiation\AbstractNegotiator;
use Negotiation\AcceptLanguage;
use Negotiation\BaseAccept;
use Negotiation\LanguageNegotiator;

final class Language extends AbstractScope
{
    public function getHeaderName(): string
    {
        return 'Accept-Language';
    }

    protected function getNegotiator(): AbstractNegotiator
    {
        return new LanguageNegotiator();
    }

    protected function getDefaultAccept(): ?BaseAccept
    {
        return $this->default !== null ? new AcceptLanguage($this->default) : null;
    }
}
